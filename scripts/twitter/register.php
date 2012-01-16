<?php
require_once('twitteroauth.php');
$oauth = new TwitterOAuth('DaJdaJ3mDo3t7TTZWw471w','7R8Lt9n8kcUniJEVXKBQAOt9nSaBBr9h3QIdQA5VQ8');
$request = $oauth->getRequestToken();
$requestToken = $request['oauth_token'];
$requestTokenSecret = $request['oauth_token_secret'];
// place the generated request token/secret into local files
file_put_contents('request_token', $requestToken);
file_put_contents('request_token_secret', $requestTokenSecret);
// display Twitter generated registration URL
$registerURL = $oauth->getAuthorizeURL($request);
echo '<a href="' . $registerURL . '">Register with Twitter</a>';
?>