<?php

class Alert extends Singleton {

	private static $alerts = array();
	
	public function __construct() {
		if (!empty($_COOKIE['successalert'])) {
			$this->set_alert('success', $_COOKIE['successalert']);			
			Util::set_cookie('successalert', '', (time()-3600), '/', 0, 0);			
		}
		if (!empty($_COOKIE['erroralert'])) {
			$this->set_alert('error', $_COOKIE['erroralert']);
			Util::set_cookie('erroralert', '', (time()-3600), '/', 0, 0);			
		}
	}

	public function set_alert($type, $msg) {
		$this->alerts[] = array('type'=>$type, 'msg'=>$msg);
		Util::debug('Setting alert: '.$msg.'('.$type.')');
	}
	
	public function set_alert_cookie($type, $msg) {
		if ($type=="error") {
			Util::set_cookie('erroralert', $msg, (time()+60*60*24*60), '/', 0, 0);
		}
		else if ($type=="success") {
			Util::set_cookie('successalert', $msg, (time()+60*60*24*60), '/', 0, 0);
		}
	}

	public function output_alerts() {				
		$output = '';		
		if (!empty($this->alerts)) {		
			foreach ($this->alerts as $alert) {
				if ($alert['type']=="success") {		
					$output = '<ul class="success">';
				}
				if ($alert['type']=="error") {		
					$output = '<ul class="error">';
				}
			}	
			foreach ($this->alerts as $alert) {			
				$output .= '<li>'.$alert['msg'].'</li>';	
			}
			$output .= '</ul>';
		}		
		return $output;
	}
	
	public function check_for_alerts() {
		if (!empty($this->alerts)) {
			return true;
		}
		else {
			return false;
		}
	}
}

?>