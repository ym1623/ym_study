<?php
	//test
	ini_set('max_execution_time', '0');
	require_once dirname(__FILE__).'./phpQuery.php';
	require_once dirname(__FILE__).'./Snoopy.class.php';

	$url = 'http://hk.finance.yahoo.com/currency/convert';
	//$url = 'http://localhost/collection_work/app/controllers/ym/20110816/test2.php';
	$post_data = array (
		"amt" => 1,
		"from" => 'CNY',
		"to" => 'HKD'
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//指定post数据
	curl_setopt($ch, CURLOPT_POST, 1);
	//添加变量
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output = curl_exec($ch);
	curl_close($ch);
	$results=iconv('big5','utf-8',$output);
	preg_match_all('/<table class="yfnc_datamodoutline1" width="100%" cellpadding="0" cellspacing="0" border="0">(.*)<\/table>/',$results,$matches);
	print_r($matches[1][0]);exit;

//	exit;
//	
//	$snoopy = new Snoopy();
//	$vars['amt'] = 1;
//	$vars['from'] = 'CNY';
//	$vars['to'] = 'USD';
//	$snoopy->submit($url,$vars);
//	echo iconv('big5','utf-8',$snoopy->results); //获取表单提交后的 返回的结果
