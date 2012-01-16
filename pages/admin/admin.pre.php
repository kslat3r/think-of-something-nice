<?php

	//find all posts - this may need some paging at some point
	
	$Posts = new Posts();
	$Uploads = new Uploads();
	$Tags = new Tags();

	$AllPosts = $Posts->find_all();

	$ModerateForm = new Form('ModerateForm');
	if ($ModerateForm->posted() && $ModerateForm->populated()) {
		$postvars = array('moderate', 'delete', 'tags');
		$data = $ModerateForm->receive($postvars, true);
		
		//flatten
		
		$moderates 	= array();
		$deletes 	= array();
		$tags 		= array();
		
		foreach ($data as $key=>$d) {
			if (is_numeric($key)) {
				
				if (isset($d['moderate'])) {
					$moderates[] = $d['moderate'];
				}
				
				if (isset($d['delete'])) {
					$deletes[] = $d['delete'];
				}
				
				if (isset($d['tags'])) {
					$tags[] = $d['tags'];
				}
				
				unset($data[$key]);
			}
		}
		
		//moderate
		
		foreach ($AllPosts as $Post) {
			if (in_array($Post->id(), $moderates)) {
				$Post->update(array('moderated'=>1));
			}
			else {
				$Post->update(array('moderated'=>0));
			}
			
			$alert = true;			
		}
		
		if ($alert == true) {			
			$Alert->set_alert('success', 'Things moderated');
		}
			
		//delete
		
		$alert = null;
		foreach ($deletes as $id) {
			$finddata		= array();
			$finddata['id']	= $id;
			$Post = $Posts->find($finddata, 1);
			
			if (is_object($Post)) {
				$Post->delete();
			}
			
			//get uploads
			
			$finddata				= array();
			$finddata['post_id']	= $id;
			$Upload = $Uploads->find($finddata, 1);
			
			if (is_object($Upload)) {
				$Upload->delete();
			}
			
			$alert = true;
		}
		
		if ($alert == true) {
			$Alert->set_alert('success', 'Unpleasent things deleted');
		}
		
		//tags
		
		$i = 0;
		foreach ($AllPosts as $Post) {
			$tags_string 	= $tags[$i];			
			$tags_arr 		= explode(', ', $tags_string);
			
			$Tags->delete_all($Post);
			
			foreach ($tags_arr as $val) {
				if (!empty($val)) {
					
					//attempt to find tag
					
					$finddata = array();
					$finddata['value'] = $val;
					$Tag = $Tags->find($finddata, 1);
					
					//create if doesn't exist
					
					if (!is_object($Tag)) {
						$data = array();
						$data['value'] = $val;
						$Tag = $Tags->create($data);
					}
					
					//assign
					
					$Tag->assign_post($Post);
				}
			}
			
			$i++;
			$alert = true;
		}
		
		if ($alert == true) {
			$Alert->set_alert('success', 'Tags modified');
		}
	}
	
	$AllPosts = $Posts->find_all();
?>