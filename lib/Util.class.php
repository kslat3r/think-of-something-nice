<?php

class Util {
	
	public function debug($msg, $type='log') {
		global $Page;
		global $Conf;

		$message_styles	= array();
		$message_styles['error']	= 'color: red; font-weight: bold;';
		$message_styles['notice']	= 'color: orange;';
		$message_styles['success']	= 'color: green;';
		$message_styles['db']		= 'color: purple; margin: 0.5em 0; padding-left: 0.5em; border-left: 2px solid silver; display: block;';
		$message_styles['post']		= 'color: teal; margin: 0.5em 0; padding-left: 0.5em; border-left: 2px solid silver; display: block;';
		$message_styles['xmlrpc']	= 'color: navy;';
		$message_styles['stats']    = 'color: black;';
		$message_styles['backtrace']    = 'color: #7094be;';

		$debug_messages	= '';
		$style = 'color: #787878;';

		if ($Conf->debug == true) {

			if (isset($message_styles[$type])){ $style	= $message_styles[$type];}
			$debug_messages .= '<span style="'.$style.'">';

			if (isset($msg) && (is_array($msg) || is_object($msg))){
				$msg = '<pre>'.print_r($msg, 1).'</pre>';
			}

			$debug_messages .= ((isset($msg)) ? $msg : 'Something errored (no message sent).') . "\n";

			$debug_messages .= '</span>';

			if ($type == 'db') {
				$backtrace = debug_backtrace();
				$debug_messages .= '<span style="'.$message_styles['backtrace'].'">Backtrace: ';
				foreach ($backtrace as $bt) {
					if (isset($bt['class'])) {
						$debug_messages .= $bt['class'].'::'.$bt['function'].' ('.(isset($bt['file']) ? $bt['file'] : 'Unknown').')';
					}
					else {
						$debug_messages .= $bt['function'].' ('.$bt['file'].')';
					}
					if ($bt != end($backtrace)) {
						$debug_messages .= ' &larr; ';
					}
				}
				$debug_messages .= '<br/><br/></span>';
			}

			$Page->debug	.= $debug_messages;

		}

	}
	
	public function output_debug() {
		global $Page;
		if ($Page->output_debug==true) {
			echo "\n<div class=\"debug\">\nDIAGNOSTICS:<br />\n".nl2br($Page->debug)."\n</div>";
		}
	}

	public function set_cookie($name, $value = '', $expires=0, $path='', $domain='', $secure=false, $http_only=false) {
		 header('Set-Cookie: ' . rawurlencode($name) . '=' . rawurlencode($value)
	         .(empty($expires) ? '' : '; expires=' . gmdate('D, d-M-Y H:i:s \\G\\M\\T', $expires))
	         .(empty($path)    ? '' : '; path=' . $path)
	         .(empty($domain)  ? '' : '; domain=' . $domain)
	         .(!$secure        ? '' : '; secure')
	         .(!$http_only    ? '' : '; HttpOnly'), false);
	}
	
	public function get_timedate_mysql() {
		return date("Y-m-d  H:i:s");
	}

	public function convert_mysql_timedate($timedate, $displaytime=false) {
		list($date, $time) = explode(' ', $timedate);
		list($yy,$mm,$dd)=explode("-", $date);
		list($hour,$min,$sec)=explode(":", $time);
		if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd) && is_numeric($hour) && is_numeric($min) && is_numeric($sec)) 
		{			
			if (date('Y-m-d') == $date) {
				return 'Today at '."$hour:$min";
			}			
			$yesterday = date('Y-m-d', time()-86400);
			if ($yesterday == $date) {
				return 'Yesterday at '."$hour:$min";
			}
			if ($displaytime==true) {
				return "$hour:$min $dd/$mm/$yy";
			}
			else if ($displaytime==false) {
				return "$dd/$mm/$yy";
			}
		}
	}

	public function generate_random_string() {
		return md5(microtime());
	}	
	
	public function microtime_float() { 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	
	public function sec2hms ($sec, $padHours = false) {
    	// holds formatted string
        $hms = "";

		//If the value is not in the last 10 hours, simply output the date
		if ((Util::microtime_float() - $sec) > 36000) {
        	$hms = date('H:i d/m/Y', $sec);
        	return $hms;
        }  
        
        //Calculate value based on current microtime
        $sec = Util::microtime_float() - $sec;
	
        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600); 

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours;
        
       	//pluralise as necessary
       	if ($hours==1) {
       		$hms .= " hour ";
       	}
       	//don't output empty hours value
       	else if ($hours==0) {
       		$hms = '';
       	}
       	else {
       		$hms .= " hours ";
       	}

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in 
        // minutes past the hour: to get that, we need to 
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60). " minutes ago"; 

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT);
        //Don't output small 'ago' values
        if ($minutes < 2 && $hours == 0) $hms = "Just now";          
        return $hms;
    }
    
    public function simple_sec2hms($sec, $padHours=false) {
    	// holds formatted string
        $hms = "";
	
        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600); 

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours;
        
       	//pluralise as necessary
       	if ($hours==1) {
       		$hms .= " hour ";
       	}
       	//don't output empty hours value
       	else if ($hours==0) {
       		$hms = '';
       	}
       	else {
       		$hms .= " hours ";
       	}

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in 
        // minutes past the hour: to get that, we need to 
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60). " minutes"; 

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT);
        //Don't output small 'ago' values       
        return $hms;
    }
    
    public function redirect($url) {	
	    global $Session;
	    if (isset($Session)) $Session->close_session();	    
		header('Location: '.$url);		
		exit();
	}
	
	public function urlify($string) {	
		$s	= strtolower($string);
		$s	= preg_replace('/[^a-z0-9\s]/', '', $s);
		$s	= trim($s);
		$s	= preg_replace('/\s+/', '-', $s);
		
		if (strlen($s)>0) {
			return $s;
		}else{
			$md5	= md5($string);
			$s		= strtolower($md5);
			return 'ra-'.substr($s, 0, 4).'-'.substr($s, 5, 4);
		}		
	}
	
	public function generate_random_alphastring($length=10) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$code = "";
		while (strlen($code) < $length) {
			$code .= $chars[mt_rand(0,strlen($chars)-1)];
		}
		return $code;
	}
	
	public function send_email($to, $from_address, $from_name, $subject, $body) {
		global $Conf;

		$headers = "From: ".$from_name." <".$from_address.">\r\n";
		$headers .= "Return-Path: ".$from_address."\r\n";
		$headers .= "Reply-To: ".$from_address."\r\n";

		return mail($to, $subject, $body, $headers, '-r '.$from_address);
	}
	
	function excerpt_char($str, $chars) {
	    $limit  = $chars;	    
	    $str 	= trim(strip_tags($str));
	  	if (strlen($str) <= $limit) return $str;	    
	    $str    = substr($str, 0, intval($limit));	      
	    return $str."...";	    
	}
	
	public function html($s) {		
		$s = htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
		$s = str_replace("\&#039;", "'", $s);
		$s = Util::convert_links($s);
		return str_replace("\'", "'", $s);
	}
	
	public function convert_links($r) {		
		preg_match_all('%(mailto\:|news|http|https|ftp)\://{1}\S+%', $r, $links);	
		if (!empty($links)) {
			foreach ($links[0] as $l) {						
				$r = preg_replace('%'.$l.'%', '<a href="'.$l.'" target="_blank">'.$l.'</a>', $r);				
			}
		}	
		return $r;
	}
	
	public function pad($s) {
		if ($s<10) {
			return '0'.$s;
		}
		else {
			return $s;
		}
	}
	
	function fragment($fragment) {
		global $Conf;        
        include($Conf->fragmentpath.'/_' . $fragment . '.php');
    }
    
        public function read_file($fileloc) {
    	if (file_exists($fileloc)) {
			$size = @filesize($fileloc);
			if ($size == 0) $size = 1;
			$fp = fopen($fileloc, "r");
			$contents = fread($fp, $size);
			fclose($fp);

			return $contents;
    	}

    	return false;
    }

    public function write_file($fileloc, $data) {
    	if (!file_exists($fileloc)) {
    		shell_exec('touch '.$fileloc);
    	}
	    $fh = fopen($fileloc, 'w');
		fwrite($fh, $data);
		fclose($fh);
    }

    public function create_dir($dirloc) {
		if (!file_exists($dirloc)) {
			$oldumask = umask(0);
			mkdir($dirloc, 0777);
			umask($oldumask);
		}
    }
    
    //eg Util::binarySearch('foo', $array, 0, count($array)-1);
    
    public function binarySearch($needle, $haystack, $starting, $ending) {	
	   	if ($ending < $starting) {
	    	return false;
	   	}
	   
	   	$mid = intval(($starting+$ending)/2);
	   
	   	$_I1 = $haystack[$mid];
	   
	   	if ($_I1 === $needle) {
	    	return $mid;
	   	}
	   	else {
			if($_I1 > $needle) {
	        	$ending = $mid-1;
	      	}
			else {		      
	      		if ($_I1 < $needle) {
			    	$starting = $mid+1;
	      		}
	   		}	
	   	}
	   	
		return Util::binarySearch($needle, $haystack, $starting, $ending);
	}
}
?>
