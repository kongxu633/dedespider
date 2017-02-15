<?php

include "init.php";


$search_word = isset($_GET['q']) ? $_GET['q'] : 'qq头像女生带字';
$typeid = isset($_GET['cid']) ? $_GET['cid'] : '1';
//$search_word = 'qq头像女生带字';

$search_word = rawurlencode($search_word);

$json_url = 'http://image.baidu.com/search/acjson?tn=resultjson_com&ipn=rj&word='.$search_word.'&rn='.$def_num;
$json_ret = curl_request($json_url);
$json_arr = json_decode($json_ret,true);

if($json_arr === null){
	$err_msg = 'cant get baidu json';
	err_log( $err_msg .' '.$json_url );
	die( $err_msg );
}

dd($json_url);
dd($json_arr);

$obj_urls = get_obj_urls($json_arr);

dd($obj_urls);

//这里需要过滤
$pic_urls = filter_url($obj_urls);
   
$upload_urls = rolling_curl($pic_urls,'down_img');
//$upload_urls = curl_check_url($pic_urls);
dd($upload_urls);

$upload_urls = array_slice($upload_urls, 0, $down_num);

//$typeid = 1; //1,2,3,4分别对应4个分类
$click = mt_rand(100,500);
$pubdate = date("Y-m-d H:i:s");
$title = handle_title($search_word);
$picname = '';
$body = handle_body($title,$upload_urls);


$data_my = [
  "typeid"=> $typeid,
  "title"=> $title,
  "picname" => $picname,
  "body"=> $body,
  "click"=> $click,
  "pubdate"=> $pubdate
];

$data = array_merge($data_normal,$data_my);

dd($data);

$ret = curl_request($post_url,$data,$login_cookie);

if(strpos($ret, '成功发布文章')!==false){
	echo "post success words:" . $search_word . "<br/>\r\n";
}else{
	die('post article error');
}
