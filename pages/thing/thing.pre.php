<?php

	//check for fb hit
	
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'facebook') !== false) {
		$redirect = false;
	}
	else {
		$redirect = true;
	}
	
	$Posts = new Posts();

	if ($Page->args['hash'] == 'random') {
		$SelectedPost = $Posts->get_random_post();
	}
	else {
		$data 			= array();
		$data['id']		= $Page->args['hash'];
		$SelectedPost	= $Posts->find($data, 1);
	}
	
	if (!is_object($SelectedPost) || $SelectedPost->moderated() == false) {
		Util::redirect('/error/404');	
	}

	if (!$Posts->has_posted($Session) && (!isset($CurrentUser) || $CurrentUser == false) && $redirect == true) {
		$Alert->set_alert_cookie('error', 'This site works through the good nature of the people who use it. Once you add a nice thing, you will be able to see the nice thing you asked for.');
		$Session->set('selected_post', $SelectedPost->id());
		Util::redirect('/');
	}
	else {
		//delete selected post
		
		$Session->delete('selected_post');
	}

	
	
	//check for upload
	
	$Uploads = new Uploads();
	
	$data = array();
	$data['post_id'] = $SelectedPost->id();
	
	$Upload = $Uploads->find($data, 1);
	
	//get tags
	
	$Tags = new Tags();
	$AllTags = $Tags->get_from_post($SelectedPost);
	
?>