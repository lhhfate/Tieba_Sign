<?php
if(!defined('IN_KKFRAME')) exit();

function safeguard_init(){
	if(getSetting('safeguard') > TIMESTAMP - 7200) return;
	safeguard_check();
	saveSetting('safeguard', TIMESTAMP);
}

function safeguard_check(){
	$c = file_get_contents(SYSTEM_ROOT.'./safeguard.db');
	$c = pack('H*', $c);
	$a = unserialize($c);
	unset($c);
	if(!$a) error::system_error('Oops! Illegal file: /system/safeguard.dat');
	$e = array();
	foreach($a as $f){
		list($p, $h) = explode("\t", $f);
		$c = md5(safeguard_trim(ROOT.$p));
		if($c != $h) error::system_error("KK SafeGuard have detected a threat! Please RE-INSTALL this application.<!--{$p}-->");
	}
}

function safeguard_trim($file){
	$fp = @fopen($file, 'r');
	$c = fread($fp, filesize($file));
	fclose($fp);
	$c = str_replace("\r", ' ', $c);
	$c = str_replace("\n", ' ', $c);
	$c = str_replace("\t", ' ', $c);
	while(strpos($c, '  ')) $c = str_replace('  ', ' ', $c);
	return $c;
}