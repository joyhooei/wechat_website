<?php
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');

$info = array();
$info['uid'] = $_W['uid'];
$info['account'] = $_W['account'] ? $_W['account']['name'] : '';
$info['os'] = php_uname();
$info['php'] = phpversion();
$info['sapi'] = $_SERVER['SERVER_SOFTWARE'];
$info['sapi'] = $info['sapi'] ? $info['sapi'] : php_sapi_name();
$size = 0;
$size = @ini_get('upload_max_filesize');
if($size) {
    $size = parse_size($size);
}
if($size > 0) {
    $ts = @ini_get('post_max_size'); 
    if($ts) {
        $ts = parse_size($size);
    }
    if($ts > 0) {
        $size = min($size, $ts);
    }
    $ts = @ini_get('memory_limit');
    if($ts) {
        $ts = parse_size($size);
    }
    if($ts > 0) {
        $size = min($size, $ts);
    }
}
if(empty($size)) {
    $size = '';
} else {
    $size = $size / 1048576;
    $size = sprintf('%.2f', $size) . ' MB';
}
$info['limit'] = $size;

$sql = 'SELECT VERSION();';
$info['mysql']['version'] = pdo_fetchcolumn($sql);
$sql = "SELECT SUM(`DATA_LENGTH`+`INDEX_LENGTH`) FROM `information_schema`.`TABLES` where TABLE_SCHEMA='{$_W['config']['db']['database']}'";
$size = pdo_fetchcolumn($sql);
$size = floatval($size);
if(empty($size)) {
    $size = '';
} else {
    $size = $size / 1048576;
    $size = sprintf('%.2f', $size) . ' MB';
}
$info['mysql']['size'] = $size;
$info['attach']['url'] = $_W['attachurl'];

$path = IA_ROOT . '/' . $_W['config']['upload']['attachdir'];
$size = dir_size($path);
if(empty($size)) {
    $size = '';
} else {
    $size = $size / 1048576;
    $size = sprintf('%.2f', $size) . ' MB';
}
$info['attach']['size'] = $size;

template('home/welcome');


function dir_size($dir) { 
    $handle = opendir($dir);
    $size = 0;
    while (false !== ($entry = readdir($handle))) { 
        if($entry != '.' && $entry != '..') { 
            if(is_dir("{$dir}/{$entry}")) { 
                $size += dir_size("{$dir}/{$entry}"); 
            } else { 
                $size += filesize("{$dir}/{$entry}"); 
            }
        }    
    }
    closedir($handle);
    return $size;
}

function parse_size($str) {
    if(strtolower($str[strlen($str) -1]) == 'k') {
        return floatval($str) * 1024;
    }
    if(strtolower($str[strlen($str) -1]) == 'm') {
        return floatval($str) * 1048576;
    }
    if(strtolower($str[strlen($str) -1]) == 'g') {
        return floatval($str) * 1073741824;
    }
}
