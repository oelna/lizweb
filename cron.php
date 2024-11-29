<?php

$root = getenv("HOME");
echo('Hello World from PHP cron, '.$root);

$config['bluesky-username'] = getenv("BLUESKY_USERNAME");
$config['bluesky-password'] = getenv("BLUESKY_APP_PASSWORD");
  
var_dump($config['bluesky-username']);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://bsky.social/xrpc/com.atproto.server.createSession',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "identifier":"'.$config['bluesky-username'].'",
    "password":"'.$config['bluesky-password'].'"
  }',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);

$session = json_decode($response, true);
var_dump($session);

$new_post = 'The time is: '.date('H:i:s');

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://bsky.social/xrpc/com.atproto.repo.createRecord',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "repo":"'.$session['did'].'",
    "collection":"app.bsky.feed.post",
    "record":{
        "$type":"app.bsky.feed.post",
        "createdAt":"'.date("c").'",
        "text":"'.$new_post.'"
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$session['accessJwt']
  ),
));

$response = curl_exec($curl);
curl_close($curl);

var_dump($response);
