<?php
mb_internal_encoding("UTF-8");
define('PROJECT_HOME', dirname(__FILE__) . '/../');//TODO replace all dirname(__FILE__) with PROJECT_HOME
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'manager');
define('MYSQL_PASS', 'IjemSq0K');
define('MYSQL_DB', 'providers');
define('MYSQL_DB_STORE', 'magaz');
define('ROOT_CATEGORY', '2');
define('MAGE_DIR', dirname(__FILE__).'/../../magaz/');
$STOCKS = array(
	'in_stock' => 50,    //есть в наличии(мало в наличии)
	'adjust' => 40,      //есть в наличии, цена неизвестна, уточняйте цену
	'wait' => 30,        //ожидается(в пути)
	'order' => 20,       //на заказ(т.е. нет в наличии, но можно заказать)
	'not_in_stock' => 10, //нет в наличии
	);
$implemented_convertors = array(
	'preximD',
	'SouthPalmira',
	'NTCom',
	'medterm',
	'vodovorot',
	'foxtrot');
$type_filter_compares = array(
	"text" => array(
		"contains" => "%s like '%%%s%%'",
		"does_not_contains" => "%s not like '%%%s%%'",
		"equal" => "%s = '%s'",
		"not_equal" => "%s <> '%s'"
	),
	"int" => array(
		"equal" => "%s = %s",
		"greater" => "%s > %s",
		"less" => "%s < %s",
		"not_equal" => "%s <> %s"
	),
	"float" => array(
		"equal" => "%s = %s",
		"greater" => "%s > %s",
		"less" => "%s < %s",
		"not_equal" => "%s <> %s"
	)
);
define('CATEGORY_MURKUP_ATTRIBUTE_ID', 563);
$_ = array(
	'EN' => array(
		'conv4imp' => array(
			'Каталог, поиск' => 'Catalog, Search',
			'Нет' => 'None',
			'Включено' => 'Enabled',
			'' => '',
			'' => '',
		)
	)
);
?>
