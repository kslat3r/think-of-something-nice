<?php

class Auth {

	private $logged_in = false;
	private $db;
	private $alert;
	private $session;
	public $just_logged_in;
	public $logged_in_time = 0;
	public $passwordseed = '9657';

	function __construct($session) {
		$this->db = DB::fetch('DB');
		$this->alert = Alert::fetch('Alert');
		$this->session = $session;
		if ($this->session->check_session_exists() && $this->session->get('userID') !== false) {
			$this->logged_in = true;
		}
		else {
			$this->login();
		}
		if ($this->check_logged_in()) {
            Util::debug('Authenticated user.', 'auth');
        }
        else{
        	Util::debug('Unauthenticated user.', 'auth');
        }
		if (isset($_GET['logout'])) {
			$this->logout();
			Util::debug('Logging out', 'auth');
		}
	}

	public function login($username = false, $password = false) {
		global $Page;
		global $Alert;

		//short or long session
		if (!isset($_POST['loginRememberMe'])) {
			$rememberme = 0;
		}
		else {
			$rememberme = 1;
		}

		if (isset($_POST['loginUsername']) || $username != false) {
			if ($username == false && $password == false) {
				$username = $_POST['loginUsername'];
				$password = md5($_POST['loginPassword'].$this->passwordseed);
			}
			else {
				$password = md5($password.$this->passwordseed);
			}

			$sql = "SELECT * FROM tblUsers
					WHERE userName=".$this->db->sterilise($username)."
					AND userPassword=".$this->db->sterilise($password);
			$result=$this->db->select_row($sql);
			if (!is_array($result)) {
				$this->logged_in = false;
				$this->alert->set_alert('error', 'Your account could not be found');
			}
			else {
				if (!$this->check_confirmed($result['userID'])) return false;

				$this->logged_in = true;
				//$this->session->delete_previous($result['userID']);
				$this->store_auth($username, $password, $result['userID']);
				if ($rememberme == 0) {
	 				$this->session->create_session();
	 			}
	 			else {
	 				$this->session->create_session("long");
	 			}

				$this->just_logged_in=true;
			}
		}
		else {
			$this->logged_in = false;
			$this->just_logged_in = false;
		}
	}

	private function check_confirmed($id) {
		$sql = "SELECT * FROM tblConfirmation
				WHERE userID=".$this->db->sterilise($id);
		$count = $this->db->get_count($sql);
		if ($count != 0) {
			$this->alert->set_alert('error', "It looks like you havn't confirmed your account. Why don't you check your e-mail inbox?");
			return false;
		}
		else {
			return true;
		}
	}

	private function store_auth($username, $password, $userid) {
		$this->session->set('userName', $username);
		$this->session->set('userPassword', $password);
		$this->session->set('userID', $userid);
		$this->session->set('IPAddress', $_SERVER['REMOTE_ADDR']);
		$this->session->set('logintime', Util::microtime_float());
	}

	public function logout() {
		$this->session->delete('userName');
		$this->session->delete('userPassword');
		$this->session->delete('userID');		
		Util::redirect('/');
	}

	public function check_logged_in() {
		return $this->logged_in;
	}
}

?>