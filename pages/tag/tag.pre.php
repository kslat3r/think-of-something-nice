<?php

	$Posts = new Posts();
	$Uploads = new Uploads();

	if (!$Posts->has_posted($Session) && (!isset($CurrentUser) || $CurrentUser == false)) {
		Util::redirect('/');
	}

	//get tag
	
	$Tags = new Tags();
	$finddata = array();
	$finddata['value'] = $Page->args['tag'];
	$Tag = $Tags->find($finddata, 1);
	
	//redirect if no tag
	
	if (!is_object($Tag)) {
		Util::redirect('/error/404');
	}
	
	//get posts

	$AllPosts = $Posts->get_from_tag($Tag);
	
	//redirect if no posts
	
	if (count($AllPosts) == 0) {
		Util::redirect('/error/404');
	}
	
?>