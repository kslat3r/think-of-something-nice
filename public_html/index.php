<?php

	//include configuration settings
	
	require_once('config.php');

	//autoload magic function automatically include class files if they are referenced
	
 	function __autoload($name) {
 		global $Conf;
 		if (file_exists($Conf->libpath.'/'.$name.'.class.php')) {
 	  		require_once($Conf->libpath.'/'.$name.'.class.php');
 		}
 		else {
 			Util::debug('Class file not found', 'error');
 		}
	}

	//Start the timer
	
	$time_start = Util::microtime_float();

	//instantiate objects
	
	$DB = DB::fetch('DB');
	$Page = new Page();
	$Page->init();	
	$Session = new Session();
	$Alert = new Alert();
	$Posts = new Posts();
	$Auth = new Auth($Session);
	$Users = new Users();
	
	//if user is logged in, create User object
	if ($Auth->check_logged_in()) {
    	$userdata['userID'] = $Session->get('userID');
	    $CurrentUser = $Users->find($userdata, $limit=1);
	}
	else {
    	$CurrentUser = false;
	}
	
	include_once($Page->authfile);
	
	//if user is not logged in and authorisation to view page is required, redirect
	if (!$Auth->check_logged_in() && $Page->auth_req==true) {
		$Alert->set_alert('error', 'You need to be logged in to access the page you requested.');
		$Page->force_page('/login');
	}
	
	//include prefile
	
	include_once($Page->prefile);

	//caching switch
	
	if ($Page->cache == true && $Page->check_cache_file() == false && $Conf->cache == true) {
		ob_start();
	}
	else if ($Page->cache == true && $Page->check_cache_file() == true && $Conf->cache == true) {
		include_once($Page->cachefile);
		$Page->set_last_page();
		$Session->close_session();
		exit();
	}

	//include specific layout top file if request is not ajax
	
	include_once($Conf->layoutpath.'/'.$Page->layout.'/top.inc.php');
	include_once($Page->postfile);

	// Finish stats
	
	Util::debug("Queries: ".DB::$queries, 'stats');
	$time_end = Util::microtime_float();
	$time = round($time_end - $time_start, 8);
	Util::debug("Time: $time seconds", 'stats');

	//include specific layout bottom file if request is not ajax
	
	include_once($Conf->layoutpath.'/'.$Page->layout.'/bottom.inc.php');
	
	//caching switch
	
	if ($Page->cache == true && $Page->check_cache_file() == false && $Conf->cache == true) {
		$fp = @fopen($Page->cachefile, 'w');
		@fwrite($fp, ob_get_contents());
		@fclose($fp);
		ob_end_flush();
	}

	//write session values to db
	$Session->close_session();

?>