<?php

define('DEBUG', false);

//后台发布地址
$post_url = 'http://127.0.0.1/dede/article_add.php';

//登录cookies
$login_cookie = 'menuitems=1_1%2C2_1%2C3_1; DedeUserID=1; DedeUserID__ckMd5=3f75bd49b6d3f9f3; DedeLoginTime=1487163115; DedeLoginTime__ckMd5=e90a845fcc47f512; PHPSESSID=k1k2imqfmbv2h4l98vfppbtvb4';

//从百度获取json链接
$json_base_url = 'http://image.baidu.com/search/acjson?tn=resultjson_com&ipn=rj&ie=utf-8';

//默认最多采集几个关键词
$max_num = 30;

//一次从百度获取几张图片
$def_num = 10;

//需要下载几张图片
$down_num = 4;

//文章状态 0=正常, -1=需要审核
$arcrank = '-1';
//自动生成 文章状态为0时生效
$ishtml = '1';

//点击数范围
$click = mt_rand(100,500);



/*------------------------以下参数不建议修改---------------------------------*/
//提交文章中一些默认的参数(全部)
//可以用自己的参数覆盖
$data_normal = [
  'channelid' => '1',//频道
  'dopost' => 'save',//保存
  'title' => '标题',//标题
  'shorttitle' => '',//小标题
  'redirecturl' => '',//跳转页面
  'tags' => '',
  'weight' => '0',
  'picname' => '',//缩略图
  'source' => '',
  'writer' => '',
  'typeid' => '1',//栏目
  'typeid2' => '',
  'keywords' => '',
  'autokey' => '1',
  'description' => '',//简介
  'dede_addonfields' => '',
  'remote' => '0',//自动下载远程图片
  'autolitpic' => '0',//第一张图片作为缩略图
  'sptype' => 'hand',
  'spsize' => '5',
  'body' => '内容内容内容',//编辑框里的内容
  'voteid' => '',
  'notpost' => '0',
  'click' => '88',//点击数
  'sortup' => '0',
  'color' => '',
  'arcrank' => '0',//文章状态 0=正常, -1=需要审核
  'ishtml' => '1',//自动生成html 审核状态不会生成  
  'money' => '0',
  'pubdate' => '2017-02-15 20:43:36',//发布时间
  'filename' => '',
  'templet' => '',
  'needwatermark' => '0',//图片加水印  
  'imageField_x' => '16',//水印位置
  'imageField_y' => '13',//水印位置
];  