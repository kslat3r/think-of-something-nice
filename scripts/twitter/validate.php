<?php
// Retrieve our previously generated request token & secret
$requestToken = file_get_contents("request_token");
$requestTokenSecret = file_get_contents("request_token_secret");
// Include class file & create object passing request token/secret also
require_once("twitteroauth.php");
$oauth = new TwitterOAuth('DaJdaJ3mDo3t7TTZWw471w', '7R8Lt9n8kcUniJEVXKBQAOt9nSaBBr9h3QIdQA5VQ8', $requestToken, $requestTokenSecret);
// Generate access token by providing PIN for Twitter
$request = $oauth->getAccessToken(NULL, '5755897');
$accessToken = $request['oauth_token'];
$accessTokenSecret = $request['oauth_token_secret'];
// Save our access token/secret
file_put_contents("access_token", $accessToken);
file_put_contents("access_token_secret", $accessTokenSecret);
?>