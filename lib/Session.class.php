<?php

class Session {

	private $db;
	private $cookie_name;
	public $session_id;
	private $session_data = array();
	private $session_updated;
	private $short_session_timeout="1800";
	private $long_session_timeout="31536000";

	function __construct() {
		$this->db = DB::fetch('DB');
		$this->cookie_name = "tosncookie";

		//pseudo-daemon for checking for expired sessions
		$this->check_for_expired_sessions();

		if (!$this->check_session_exists()) {
			$this->create_session('long');
		}
	}

	//set session data
	public function set($key, $value) {
		$this->session_updated = true;
		$this->session_data[$key] = $value;
	}

	//get session data
	public function get($key) {
		if (isset($this->session_data[$key])) {
			return $this->session_data[$key];
		}
		return false;
	}

	//delete session data
	public function delete($key) {
		$this->session_updated = true;
		unset($this->session_data[$key]);
	}

	//check to see if there is an existing session
	public function check_session_exists() {
		if (isset($_COOKIE[$this->cookie_name])) {
			$sql = "SELECT * FROM tblSessions WHERE sessionID = ".$this->db->sterilise($_COOKIE[$this->cookie_name]);
			$result = $this->db->select_row($sql);
			if ($result) {
				$this->session_data = unserialize($result['sessionData']);
				$this->session_id 	= $result['sessionID'];
				
				if ($this->session_data['sessionLength'] == 'short') {
					$data['sessionExpiry'] = Util::microtime_float() + $this->short_session_timeout;
					Util::set_cookie($this->cookie_name, $this->session_id, (time()+$this->short_session_timeout), '/', 0, 0);
				}
				else {
					$data['sessionExpiry'] = Util::microtime_float() + $this->long_session_timeout;
					Util::set_cookie($this->cookie_name, $this->session_id, (time()+$this->long_session_timeout), '/', 0, 0);
				}
				$this->db->update('tblSessions', $data, 'sessionID', $this->session_id);
				
				Util::debug('SessionID: ' . $this->session_id);
				
				return true;
			}
			return false;
		}
		return false;
	}

	public function delete_previous($userid) {
		$sql = 'SELECT * from tblSessions';
		$result = $this->db->select_rows($sql);
		if ($result) {
			foreach ($result as $r) {
				$data = unserialize($r['sessionData']);
				if (isset($data['userID']) && $data['userID'] == $userid) {
					$deldata['sessionID'] = $r['sessionID'];
					$this->db->delete('tblSessions', $deldata);
				}
			}
		}
	}

	//create a user session
	public function create_session($length="long") {
		if (!$this->session_id) {
			$id = md5(uniqid());
			$data = array();
			$data['sessionID'] = $id;
			$this->session_data['sessionLength'] = $length;
			$data['sessionData'] = serialize($this->session_data);
			if ($length=="short") {
				$data['sessionExpiry'] = Util::microtime_float() + $this->short_session_timeout;
				Util::set_cookie($this->cookie_name, $id, (time()+$this->short_session_timeout), '/', 0, 0);
			}
			elseif ($length=="long") {
				$data['sessionExpiry'] = Util::microtime_float() + $this->long_session_timeout;
				Util::set_cookie($this->cookie_name, $id, (time()+$this->long_session_timeout), '/', 0, 0);
			}
			$this->db->insert('tblSessions', $data);
			$this->session_id = $id;
			Util::debug('SessionID: ' . $this->session_id);
			$this->session_updated = false;
			return true;
		}
	}

	//restore a user session
	private function restore_session() {
		$this->session_id = $_COOKIE[$this->cookie_name];
		$sql = "SELECT sessionData
				FROM tblSessions
				WHERE sessionID = ".$this->db->sterilise($this->session_id)."
				LIMIT 1";
		$result = $this->db->select_row($sql);
		if ($result) {
			$this->session_data = unserialize($result['sessionData']);

			if ($this->session_data['sessionLength'] == 'short') {
				$data['sessionExpiry'] = Util::microtime_float() + $this->short_session_timeout;
				Util::set_cookie($this->cookie_name, $this->session_id, (time()+$this->short_session_timeout), '/', 0, 0);
			}
			else {
				$data['sessionExpiry'] = Util::microtime_float() + $this->long_session_timeout;
				Util::set_cookie($this->cookie_name, $this->session_id, (time()+$this->long_session_timeout), '/', 0, 0);
			}
			$this->db->update('tblSessions', $data, 'sessionID', $this->session_id);

			Util::debug('SessionID: ' . $this->session_id);
		}
		else {
			return false;
		}
	}

	private function check_for_expired_sessions() {
		$sql = 'SELECT * from tblSessions';
		$result = $this->db->select_rows($sql);
		if ($result) {
			foreach ($result as $r) {
				$currenttime = Util::microtime_float();
				if ($currenttime >= $r['sessionExpiry']) {
					$data['sessionID'] = $r['sessionID'];
					$this->db->delete('tblSessions', $data);
				}
			}
		}
	}

	//delete a user session
	public function delete_session() {
		Util::set_cookie($this->cookie_name, '', (time()-3600), '/', 0, 0);
		$data['sessionID']=$this->session_id;
		$this->db->delete('tblSessions', $data);
		$this->session_updated = false;
	}

	//when the session is closed, update the session
	public function close_session() {
		if ($this->session_updated == true) {
			$data = array();
			$data['sessionData'] = serialize($this->session_data);
			$sql = "SELECT * FROM tblSessions
					WHERE sessionID = ".$this->db->sterilise($this->session_id)."
					LIMIT 1";
			$result = $this->db->select_rows($sql);
			if (count($result) == 1) {
				$this->db->update('tblSessions', $data, 'sessionID', $this->session_id);
			}
			else {
				$data['sessionID'] = $this->session_id;
				$this->db->insert('tblSessions', $data);
			}
		}
	}
}

?>