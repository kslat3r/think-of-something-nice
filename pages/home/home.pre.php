<?php

	include_once($Conf->libpath.'/Recaptcha.lib.php');
	
	$ThingForm = new Form('ThingForm');
	
	$req = array();
	
	if (!isset($_POST['remain_anon'])) {
		$req['name'] =	"Please enter your name";
	}	
	$req['somethingnice']	=	"Please enter your something nice";
	
	$ThingForm->set_required($req);
	
	if ($ThingForm->posted() && $ThingForm->populated()) {
		$postvars = array('name', 'remain_anon', 'somethingnice', 'recaptcha_challenge_field', 'recaptcha_response_field');
		$data = $ThingForm->receive($postvars, true);
		
		//check recaptcha
		
		if ($Session->get('human') == false) {
			$resp = recaptcha_check_answer($Conf->recaptcha_private_key, $_SERVER['REMOTE_ADDR'], $data['recaptcha_challenge_field'], $data['recaptcha_response_field']);
		}
		
		if ($resp->is_valid || $Session->get('human') == true) {
			
			//save
			
			if ($data['remain_anon'] == true) {
				$data['name'] = null;
			}
			
			unset($data['remain_anon']);
			unset($data['recaptcha_challenge_field']);
			unset($data['recaptcha_response_field']);
			
			$data['moderated'] 		= false;
			$data['created_date']	= date('Y-m-d H:i:s');
			$data['session_id']		= $Session->session_id;
			
			$Posts = new Posts();
			$Post = $Posts->create($data);
			
			//do files upload if necessary
			
			if (isset($_FILES['upload']) && isset($_FILES['upload']['tmp_name']) && !empty($_FILES['upload']['tmp_name'])) {
				//mime check
				
				if (!in_array($_FILES['upload']['type'], array('image/jpeg', 'image/png', 'image/jpg', 'image/gif'))) {
					$Alert->set_alert('error', 'The file type you uploaded is not allowed');
					$Post->delete();
					$check = false;
				}				

				//size check
				
				elseif ($_FILES['upload']['size'] > 1048576) {
					$Alert->set_alert('error', 'The size of the file you uploaded is too large');
					$Post->delete();
					$check = false;
				}
				
				//ok
				
				else {
					$filename_parts = explode('.', $_FILES['upload']['name']);
					$extension		= isset($filename_parts[1]) ? $filename_parts[1] : 'png';
					
					$data 					= array();	
					$data['name'] 			= md5(uniqid()).'.'.$extension;
					$data['mime']			= $_FILES['upload']['type'];
					$data['post_id']		= $Post->id();
					$data['date_created']	= date('Y-m-d H:i:s');
					
					if (@move_uploaded_file($_FILES['upload']['tmp_name'], $Conf->uploadpath.'/'.$data['name'])) {
						$Uploads 	= new Uploads();
						$Upload 	= $Uploads->create($data);
						
						$check = true;
					}
					else {
						$Alert->set_alert('error', 'An error occured when uploading your file');
						$Post->delete();
						$check = false;
					}
				}
			}
			else {
				$check = true;
			}
			
			//redirect
			
			if ($check == true) {
				
				//user is human
				
				$Session->set('human', true);
				
				//check for prev requested thing
				
				$id = $Session->get('selected_post');
				
				if ($id == false) {
					$Alert->set_alert_cookie('success', 'Thank-you for your something nice! You can now see the other nice things.');
					Util::redirect('/thing/random');					
				}
				else {
					$Alert->set_alert_cookie('success', 'Thank-you for your something nice! Here is the nice thing you originally clicked on.');
					Util::redirect('/thing/'.$id);
				}
			}
			
		}
		else {
			$Alert->set_alert('error', 'The reCAPTCHA wasn\'t entered correctly. Please try again.');
		}
	}

	$Tags = new Tags();

?>