<?php

define('DEBUG', false);

//每次采集几个关键词
$max_num = 30;

//一次获取几张图片
$def_num = 10;

//需要下载几张图片
$down_num = 4;


$login_cookie = 'PHPSESSID=06eniu184vsv673ait9rvalkc1; DedeUserID=1; DedeUserID__ckMd5=2f3f92a04083d253; DedeLoginTime=1487044067; DedeLoginTime__ckMd5=9d4b456298d9f5e1';
$post_url = 'http://127.0.0.1/dede/article_add.php';


//提交文章中一些默认的参数
$data_normal = [
  "channelid"=> "1",
  "dopost"=> "save",
  "shorttitle"=> "",
  "redirecturl"=> "",
  "tags"=> "",
  "weight"=> "0",
  "source"=> "",
  "writer"=> "",
  "typeid2"=> "",
  "keywords"=> "",
  "autokey"=> "1",
  "description"=> "",
  "dede_addonfields"=> "",
  "remote"=> "0",
  "autolitpic"=> "0",
  "needwatermark"=> "0",
  "sptype"=> "hand",
  "spsize"=> "5",
  "voteid"=> "",
  "notpost"=> "0",
  "sortup"=> "0",
  "color"=> "",
  "arcrank"=> "0",
  "money"=> "0",
  "ishtml"=> "1",
  "filename"=> "",
  "templet"=> "",
  "imageField_x"=> "22",
  "imageField_y"=> "14"
];