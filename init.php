<?php

include "config.php";
include "function.php";

date_default_timezone_set('PRC');
header('Content-Type:text/html;charset=UTF-8');

if (!DEBUG) {
	error_reporting(0);
}