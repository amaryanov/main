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
$trans_chars = array();
$trans_chars['ru'] = array(
	'а' => 'a',
	'б' => 'b',
	'в' => 'v',
	'г' => 'g',
	'д' => 'd',
	'е' => 'e',
	'ё' => 'jo',
	'ж' => 'zh',
	'з' => 'z',
	'и' => 'i',
	'й' => 'j',
	'к' => 'k',
	'л' => 'l',
	'м' => 'm',
	'н' => 'n',
	'о' => 'o',
	'п' => 'p',
	'р' => 'r',
	'с' => 's',
	'т' => 't',
	'у' => 'u',
	'ф' => 'f',
	'х' => 'h',
	'ц' => 'c',
	'ч' => 'ch',
	'ш' => 'sh',
	'щ' => 'shh',
	'ы' => 'y',
	'э' => 'eh',
	'ю' => 'yu',
	'я' => 'ya',
	' ' => $space_char,
	'-' => '-',
	'a' => 'a',
	'b' => 'b',
	'c' => 'c',
	'd' => 'd',
	'e' => 'e',
	'f' => 'f',
	'g' => 'g',
	'h' => 'h',
	'i' => 'i',
	'j' => 'j',
	'k' => 'k',
	'l' => 'l',
	'm' => 'm',
	'n' => 'n',
	'o' => 'o',
	'p' => 'p',
	'q' => 'q',
	'r' => 'r',
	's' => 's',
	't' => 't',
	'u' => 'u',
	'v' => 'v',
	'w' => 'w',
	'x' => 'x',
	'y' => 'y',
	'z' => 'z',
	'0' => '0',
	'1' => '1',
	'2' => '2',
	'3' => '3',
	'4' => '4',
	'5' => '5',
	'6' => '6',
	'7' => '7',
	'8' => '8',
	'9' => '9',
	);
define(NO_DESCRIPTION, 'На данный момент нет описания для этого товара.');
define(INCORRECT_VALUE_FOR_SELECT, '<h3>Incorrect value \'%s\' for attribute \'%s\' for row №%d</h3>');
define(META_KEYWORDS, ' купить');
define(DEFAULT_QTY, 10);
define(NO_GOODS_MESSAGE, '<h2>Этих товаров нет в магазине:</h2>');
define(GOODS_ARE_IN_DB, '<h2>Эти товары уже есть в магазине:</h2>');
