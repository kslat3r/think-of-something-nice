<?php

	$Posts = new Posts();

	$data 			= array();
	$data['id']		= $Page->args['hash'];
	$SelectedPost	= $Posts->find($data, 1);
	
	if (!is_object($SelectedPost)) {
		Util::redirect('/error/404');	
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