<?php

include "init.php";


$search_word = isset($_GET['q']) ? $_GET['q'] : 'qq头像女生带字';
$typeid = isset($_GET['cid']) ? $_GET['cid'] : '1';
//$search_word = 'qq头像女生带字';


$word = str_ireplace(['+',' '], [' ','%20'], $search_word);

$json_url = 'http://image.baidu.com/search/acjson?tn=resultjson_com&ipn=rj&ie=utf-8&word='.$word.'&rn='.$def_num;
$json_ret = curl_request($json_url);
$json_arr = json_decode($json_ret,true);

if( strlen($json_ret) < 50 || $json_arr === null ){
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
//$upload_urls = curl_check_url($pic_urls);//  ["remote"=> "1","autolitpic"=> "1"]
dd($upload_urls);

if( sizeof($upload_urls) < $down_num){
	$err_msg = 'pic is less then need';
	err_log( $err_msg .' '.$json_url );
	err_log(print_r($obj_urls,1));
	err_log(print_r($pic_urls,1));
	err_log(print_r($upload_urls,1));
	die( $err_msg );
}

//这里应该要优化一下
//10张图里面挑选出错误的图片 删除掉不用的图片
$upload_urls = array_slice($upload_urls, 0, $down_num);

$picname = $upload_urls[0];
$title = handle_title($search_word);
$body = handle_body($title,$upload_urls);
$pubdate = date("Y-m-d H:i:s");

//重写 typeid arcrank isthml click picname title body
$data_my = compact('arcrank','ishtml','click','typeid','picname','title','body','pubdate');

dd($data_my);

$data = array_merge($data_normal,$data_my);

dd($data);

$ret = curl_request($post_url,$data,$login_cookie);

if(strpos($ret, '成功发布文章')!==false){
	echo "post success words:" . $search_word . "<br/>\r\n";
}else{
	die('post article error');
}
