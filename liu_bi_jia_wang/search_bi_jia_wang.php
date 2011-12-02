<?php
	//因为执行时间较长，所以设置运行时间不被限制---编码utf-8
	ini_set('max_execution_time', '0');
	require_once dirname(__FILE__).'./phpQuery.php';
	//比价网---搜索
	$keyword = 'ipad';
	$http = 'http://search.beargoo.com.cn/search.php?kw='.$keyword;
	$html = file_get_contents($http);
	phpquery::newDocumentHTML($html,'utf-8');
	//我们真正要的数据就是全部中的数据.
	//获得产品的页数
	$page_total = pq('div.pagination li:not(:last):last')->text();
	//循环页数，拿到所有产品
	for($i=1;$i<=$page_total;$i++){
		$page_url = $http.'&page='.$i;
		$html = file_get_contents($page_url);
		phpquery::newDocumentHTML($html,'utf-8');
		$product_lists = pq('div.list>div.productlist');
		foreach($product_lists as $product_list){
			$product_list = pq($product_list);
			//产品url
			$product_url = $product_list->find('dt>a')->attr('href');
			//产品名称
			$product_title = $product_list->find('dt>a')->text();
			//产品价格
			$product_price = $product_list->find('li>b')->text();
			exit($product_title.$product_price);
		}
	}