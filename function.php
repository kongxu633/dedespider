<?php

function dd($obj){
	if (DEBUG) {
		if(is_string($obj)){
			echo "<br/>";
			echo $obj;
		}elseif(is_array($obj)){
			echo "<br/><pre>";
			print_r($obj);
		}else{
			echo "<br/><pre>";
			var_dump($obj);
		}
	}
}


function err_log( $log ){
    file_put_contents('log/logfile.log', date("Y-m-d H:i:s"). " " . $log.PHP_EOL, FILE_APPEND | LOCK_EX);
}

function filter_url($obj_urls,$filter_file = 'data/filter.txt'){
    $ret_arr = [];

    $filter_words = file($filter_file,FILE_IGNORE_NEW_LINES);

    foreach ($obj_urls as $ourl) {
        $flag = true;
        foreach ($filter_words as $fword) {
            if( stripos($ourl, $fword) !== false){
                $flag = false;
            }
        }
        if($flag){
            $ret_arr[] = $ourl;
        }
    }
    return $ret_arr;
}

function get_obj_urls($arr=array())
{
	$tmp = [];
	if (is_array($arr) && !empty($arr)) {
		$data = $arr['data'];
		foreach ($data as $v) {
			if (!empty($v)) {
				$tmp[] = uncomplie($v['objURL']);
			}	
		}
	}
	return $tmp;
}


function uncomplie($k){
  $c = array('_z2C$q','_z&e3B','AzdH3F');
  $d = array('w'=> "a",'k'=> "b",'v'=> "c",'1'=> "d",'j'=> "e",'u'=> "f",'2'=> "g",'i'=> "h",'t'=> "i",'3'=> "j",'h'=> "k",'s'=> "l",'4'=> "m",'g'=> "n","5"=> "o",'r'=> "p",'q'=> "q","6"=> "r",'f'=> "s",'p'=> "t","7"=> "u",'e'=> "v",'o'=> "w","8"=> "1",'d'=> "2",'n'=> "3","9"=> "4",'c'=> "5",'m'=> "6","0"=> "7",'b'=> "8",'l'=> "9",'a'=> "0",'_z2C$q'=> ":",'_z&e3B'=> ".",'AzdH3F'=> "/");
  if(!$k || strpos($k, "http")){
    return $k;
  }
  $j = $k;
  foreach ($c as $value) {
    $j = str_replace($value,$d[$value],$j);
  }
  $arr = str_split($j);
  foreach ($arr as $k=>$v) {
    if(preg_match('/^[a-w\d]+$/',$v))
    $arr[$k] = $d[$v];
  }
  return implode('',$arr);
}


//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url,$post='',$cookie='', $returnCookie=0){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://www.baidu.com");
    if($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if($cookie){
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if($returnCookie){
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie']  = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    }else{
        return $data;
    }
}

function download_image($url, $fileName = '', $dirName='', $fileType = array('jpg', 'jepg', 'gif', 'png'), $type = 1)
{
    if ($url == '')
    {
        return false;
    }
 
    // 获取文件原文件名
    $defaultFileName = basename($url);
 
    // 获取文件类型
    $suffix = substr(strrchr($url, '.'), 1);
    if (!in_array($suffix, $fileType))
    {
        return false;
    }
 
    // 设置保存后的文件名
    $fileName = $fileName == '' ? time() . rand(0, 9) . '.' . $suffix : $defaultFileName;
 
    // 获取远程文件资源
    if ($type)
    {
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file = curl_exec($ch);
        curl_close($ch);
    }
    else
    {
        ob_start();
        readfile($url);
        $file = ob_get_contents();
        ob_end_clean();
    }
 
    // 设置文件保存路径
    //$dirName = $dirName . '/' . date('Y', time()) . '/' . date('m', time()) . '/' . date('d', time());
    $dirName = $dirName . '/' . date('Ym', time());
    if (!file_exists($dirName))
    {
        mkdir($dirName, 0777, true);
    }
 
    // 保存文件
    $res = fopen($dirName . '/' . $fileName, 'a');
    fwrite($res, $file);
    fclose($res);
 
    return array(
        'fileName' => $fileName,
        'saveDir' => $dirName
    );
}


function curl_check_url($urls, $check_num = 5, $custom_options = null){
    $back_arr = [];

    $arr_size = sizeof($urls); 
    $rolling_window = 5;
    $rolling_window = ($arr_size < $rolling_window) ? $arr_size : $rolling_window;

    $master = curl_multi_init();
    $curl_arr = array();  
    // add additional curl options here
    $std_options = array(   CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_FOLLOWLOCATION => false,
                            CURLOPT_HEADER => true,
                            CURLOPT_NOBODY => true,
                            CURLOPT_TIMEOUT => 10,
                            CURLOPT_REFERER => 'http://image.baidu.com/',
                            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)'
                        );
    $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;

    for ($i = 0; $i < $rolling_window; $i++) {
        $ch = curl_init();
        $options[CURLOPT_URL] = $urls[$i];
        curl_setopt_array($ch,$options);
        curl_multi_add_handle($master, $ch);
    }  

    do {
        while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
        if($execrun != CURLM_OK)
            break;
        // a request was just completed -- find out which one
        while($done = curl_multi_info_read($master)) {
            $info = curl_getinfo($done['handle']);
            curl_multi_remove_handle($master, $done['handle']);
            if ( $info['http_code'] == 200 )  {
                if( strpos($info['content_type'],'image') !== false ){
                    $back_arr[] = $info['url'];
                }
            } else {
                // request failed.  add error handling.
            }
            // start a new request (it's important to do this before removing the old one)
            if( ($i < $arr_size) && (sizeof($back_arr) < $check_num) ){
                $ch = curl_init();
                $options[CURLOPT_URL] = $urls[$i++];  // increment i
                curl_setopt_array($ch,$options);
                curl_multi_add_handle($master, $ch);
            }
        }
    } while ($running);
    
    curl_multi_close($master);

    return $back_arr;        
}


function rolling_curl($urls, $callback, $custom_options = null) {

    $back_arr = [];

    $arr_size = sizeof($urls); 
    $rolling_window = 5;
    $rolling_window = ($arr_size < $rolling_window) ? $arr_size : $rolling_window;

    $master = curl_multi_init();
    $curl_arr = array();

    // add additional curl options here
    $std_options = array(   CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_FOLLOWLOCATION => false,
                            //CURLOPT_MAXREDIRS => 3,
                            CURLOPT_CONNECTTIMEOUT => 10,
                            CURLOPT_TIMEOUT => 20,
                            CURLOPT_REFERER => 'http://image.baidu.com/',
                            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)'
                        );
    $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;

    // start the first batch of requests
    for ($i = 0; $i < $rolling_window; $i++) {
        $ch = curl_init();
        $options[CURLOPT_URL] = $urls[$i];
        curl_setopt_array($ch,$options);
        curl_multi_add_handle($master, $ch);
    }

    do {
        while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
        if($execrun != CURLM_OK)
            break;
        // a request was just completed -- find out which one
        while($done = curl_multi_info_read($master)) {
            $info = curl_getinfo($done['handle']);

            if ( $info['http_code'] == 200 ){
                if( strpos($info['content_type'],'image')!==false ){
                $output = curl_multi_getcontent($done['handle']);
                $info['output'] = $output;

                $full_path = $callback($info);
                if(!empty($full_path)){
                    $back_arr[] = $full_path;
                }                    
                }

                // start a new request (it's important to do this before removing the old one)
                if($i < $arr_size){
                    $ch = curl_init();
                    $options[CURLOPT_URL] = $urls[$i++];  // increment i
                    curl_setopt_array($ch,$options);
                    curl_multi_add_handle($master, $ch);
                }

                curl_multi_remove_handle($master, $done['handle']);
            } else {
                // request failed.  add error handling.
            }
        }
    } while ($running);
    
    curl_multi_close($master);


    return $back_arr;
}

function down_img($info,$dir='../uploads/allimg'){

    $url = $info['url'];
    $data = $info['output'];
    $ext = substr($url, strrpos($url, '.'));

    $dir = $dir . '/' . date('ymd');
    $file_name = time().mt_rand(100,999).$ext;

    $full_path = $dir . '/' . $file_name;
    if (!file_exists($dir))
    {
        mkdir($dir, 0777, true);
    }
    if(file_put_contents($full_path, $data)!==false){
        return $full_path;
    }

    return '';
}

/*
采集规则：
如果除了qq两个字母，剩下的字数大于等于8，那么标题直接用关键词+图片+我爱头像网；
如果除了qq两个字母，剩下的字数小于8，那么标题使用关键词+关键词与图片的组合词+我爱头像网；
但是如果关键词原本就有图片，那么标题直接使用关键词+我爱头像网。
*/
function handle_title($search_world)
{

    if (mb_strpos($search_world, '图片')) {
        return $search_world;
    }

    if (mb_strlen(str_ireplace('qq', '', $search_world)) >= 8  ) {
        $title = $search_world . '图片';
    }else{
        $title = $search_world . '_' . $search_world . '图片';
    }

    return $title;
}


function handle_body($title,$urls)
{
    $tmp = '';
    foreach ($urls as $v) {
        $tmp .= '<p><img alt="' .$title. '" src="' .$v. '" /></p>';
    }
    return $tmp;
}