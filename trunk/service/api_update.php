<?php
$proxy = new SoapClient('http://okshop.com.ua/api/soap/?wsdl');
$sessionId = $proxy->login('product_updater', '@nt0nk3y');
for($i = 0; $i < 10; $i++)
{
	echo microtime(true)."<br>";
	$proxy->call($sessionId, 'product.update', array('FLY 2080 (black)', array('name'=>'2080 (black)'), 'admin'));
	echo microtime(true)."<br>";
}
var_dump($proxy->call($sessionId, 'product.info', 'FLY 2080 (black)'));
?>
