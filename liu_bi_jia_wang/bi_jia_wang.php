<?php
	//因为执行时间较长，所以设置运行时间不被限制---编码utf-8
	ini_set('max_execution_time', '0');
	//ini_set('date.timezone','Asia/Taipei');
	require_once dirname(__FILE__).'./phpQuery.php';
	//比价网---採集
	$http = 'http://www.beargoo.com.cn/';
	$html = file_get_contents($http);
	phpquery::newDocumentHTML($html,'utf-8');
	//产品目录-----大类
	$categorys = pq('div.menu li>a.drop');
	foreach($categorys as $category){
		$category = pq($category);
		//大类名称---需数据库存储
		$category_name = $category->text();
		//大类---->子类产品---需数据库存储
		$anchors = $category->next()->find('ul>li>a');
		foreach($anchors as $anchor){
			$anchor = pq($anchor);
			//需数据库存储
			$url = $anchor->attr('href');
//			$type_url = parse_url($url);
//			//产品类型
//			$type = $type_url['query'];
			//需数据库存储
			$title = $anchor->text();
			//在此处判断数据库中是否存在重复的记录，如果不存在，执行以下操作，存在就continue
			$html = file_get_contents($url);
			phpquery::newDocumentHTML($html,'utf-8');
			//拿到全部的产品，模拟点击了全部按钮
			$products_type = pq('dl.arrange')->find('dd:last');
			preg_match_all("/\'(.*)\',\'(.*)\'/si",$products_type->attr('onclick'),$infos);
			$param = $infos[1][0];
			$var = $infos[2][0];
			$all_url = $url.'&'.$param.'='.$var;
			//重新发送请求
			$html = file_get_contents($all_url);
			phpquery::newDocumentHTML($html,'utf-8');
			//获得产品的页数
			$page_total = pq('div.pagination li:not(:last):last')->text();
			//循环页数，拿到所有产品
			for($i=1;$i<=$page_total;$i++){
				$page_url = $all_url.'&page='.$i;
				$html = file_get_contents($page_url);
				phpquery::newDocumentHTML($html,'utf-8');
				$product_lists = pq('div.list>div.productlist');
				foreach($product_lists as $product_list){
					$product_list = pq($product_list);
					//产品url---需数据库存储
					$product_url = $product_list->find('dt>a')->attr('href');
					//产品名称---需数据库存储
					$product_title = $product_list->find('dt>a')->text();
					$html = file_get_contents($product_url);
					phpquery::newDocumentHTML($html,'utf-8');
					//比价网为您找到网销最低价---begin---需数据库存储
					$lower_url = pq('dl.proparam')->find('dd>a:first')->attr('href');
					$lower_price = pq('dl.proparam')->find('dd>a:first')->next()->text();
					$lower_title = pq('dl.proparam')->find('dd>a:first')->text();
					$detail_param_url = $http.pq('dl.proparam')->find('dd>a:last')->attr('href');
					//end
					//详细参数
					$html = file_get_contents($detail_param_url);
					phpquery::newDocumentHTML($html,'utf-8');
					$params_cate = pq('div.paramb>div.paramhead>dt');
					$params_paramk = pq('div.paramk');
					foreach($params_paramk as $k=>$param_paramk){
						$param_paramk = pq($param_paramk);
						//参数类别---需数据库存储
						$param_cate = $params_cate->eq($k);
						$paramks = $param_paramk->find('dl');
						foreach($paramks as $paramk){
							$paramk = pq($paramk);
							//参数名称---需数据库存储
							$param_name = $paramk->find('dt')->text();
							//参数值---需数据库存储
							$param_value = $paramk->find('dd')->text();
							//打印..此处做数据库保存工作..
							exit($param_cate.$param_name.$param_value);
						}
					}
				}
			}
		}
	}