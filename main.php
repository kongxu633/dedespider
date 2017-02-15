<?php

include "config.php";
include "lib/CURL.php";
$curl=new CUrl();

$cid = $argv[1];
$user_num = $argv[2];

if (is_null($cid)) {
	$cid = 1;
}
if (is_null($user_num)) {
	$user_num = $max_num;
}

$base_url='http://127.0.0.1/dedespider/?cid='.$cid.'&q=';

$file_name = 'data/' . $cid . '.txt';
$words = file($file_name,FILE_IGNORE_NEW_LINES);


$tmp_arr = array_slice($words, $user_num);
file_put_contents($file_name, join($tmp_arr,"\r\n"));


$i = 1;
foreach($words as $k => $v){
	if($i++ > $user_num){
		break;
	}
	$encode = urlencode(trim($v));
    $url=array($base_url.$encode);

    $callback=array('check_status',array($url[0]));
    $curl->add($url,$callback);
}
$curl->go();

function check_status($r,$url){
    global $curl;
    $curl->status();
}