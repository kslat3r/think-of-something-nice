<?php

	putenv('HTTP_HOST=thinkofsomethingnice.bowlerhat.eajk');
	
	require_once('../../public_html/config.php');
	require_once('../../lib/Singleton.class.php');
	require_once('../../lib/Util.class.php');
	require_once('../../lib/DB.class.php');

	// Read in our saved access token/secret
	$accessToken = file_get_contents("access_token");
	$accessTokenSecret = file_get_contents("access_token_secret");

	// Create our twitter API object
	require_once("twitteroauth.php");
	$oauth = new TwitterOAuth('DaJdaJ3mDo3t7TTZWw471w', '7R8Lt9n8kcUniJEVXKBQAOt9nSaBBr9h3QIdQA5VQ8', $accessToken, $accessTokenSecret);

	// Send an API request to verify credentials
	$credentials = $oauth->get("account/verify_credentials");

	//get random nice thing

	$DB = new DB();
	
	$sql = "SELECT * FROM tblPosts
			WHERE moderated = 1
			ORDER BY RAND()
			LIMIT 1";
	$result = $DB->select_rows($sql);
	
	$tweet = Util::html(Util::excerpt_char($result[0]['somethingnice'], 80)).' - http://thinkofsomethingnice.com/thing/'.$result[0]['id'];
	
	//post
	$oauth->post('statuses/update', array('status' => $tweet));
?>