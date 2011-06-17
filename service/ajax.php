<?php
try
{
	require_once dirname(__FILE__) . "/lib/Product.php";
	require_once dirname(__FILE__) . "/lib/Converter.php";

	function buildFilterStr($avaliable_filters, $filter_compare, $filter_values)
	{
		global $type_filter_compares;
		$res = array();
		foreach($filter_values as $col_name => $filter_value)
		{
			if(isset($filter_compare[$col_name])
				&& isset($avaliable_filters[$col_name])
				&& isset($type_filter_compares[$avaliable_filters[$col_name]['filter_type']])
				&& isset($type_filter_compares[$avaliable_filters[$col_name]['filter_type']][$filter_compare[$col_name]])
			)
			{
				switch($avaliable_filters[$col_name]['filter_type'])
				{
					case 'text':
						$filter_value = mysql_escape_string($filter_value);
						break;
					case 'int':
						$filter_value = (int)$filter_value;
						break;
					case 'float':
						$filter_value = (float)$filter_value;
						break;
				}
				$res[] = sprintf($type_filter_compares[$avaliable_filters[$col_name]['filter_type']][$filter_compare[$col_name]], $avaliable_filters[$col_name]['column_name'], $filter_value);
			}
		}
		return implode(" and ", $res);
	}

	$res = null;
	switch($_REQUEST['action'])
	{
		case 'getProvProds':
			$prov_id = (int)$_REQUEST['prov_id'];
			$our_prod_id = 0;
			if(isset($_REQUEST['our_prod_id']) && (int)$_REQUEST['our_prod_id'] > 0)
			{
				$our_prod_id = (int)$_REQUEST['our_prod_id'];
			}
			$names = null;
			if(isset($_REQUEST['names']) && strlen($_REQUEST['names']))
			{
				$names = explode(' ', $_REQUEST['names']);
				$names = array_diff($names, array(''));
			}
			$res = Product::getProvProds($prov_id, $our_prod_id, $names);
			break;
		case 'update_min_prices':
			Product::updateMinPrices();
			$res = "Минимальные цены обновленны успешно.";
			break;
		case 'get_prods':
			$avaliable_fields = array(
				"category" => array(
					"filter_type" => "text",
					"column_name" => "categories"),
				"brand" => array(
					"filter_type" => "text",
					"column_name" => "manufacturer"),
				"sku" => array(
					"filter_type" => "text",
					"column_name" => "e.sku"),
				"price" => array(
					"filter_type" => "text",
					"column_name" => "min_price"));
			$provs = &Providers::getProviders();
			foreach($provs as $id => $prov)
			{
				$avaliable_fields["providers_products_{$id}_price"] = array(
					"filter_type" => "float",
					"column_name" => "providers_products_{$id}.price"
				);
			}
			$filter_str = "";
			if(isset($_REQUEST["filter_compare"])
				&& isset($_REQUEST["filter_values"])
				&& count($_REQUEST["filter_compare"])
				&& count($_REQUEST["filter_values"]))
			{
				$filter_str = buildFilterStr($avaliable_fields, $_REQUEST["filter_compare"], $_REQUEST["filter_values"]);
			}
			if($_REQUEST['subaction'])
			{
				switch($_REQUEST['subaction'])
				{
					case 'getcount':
						$res = Product::getOurProductsCount($filter_str);
						break;
				}
			}
			else
			{
				$start = (int)$_REQUEST['start'];
				$count = (int)$_REQUEST['count'];
				$order_str = "";
				if(isset($_REQUEST["sort_column_name"]) && isset($avaliable_fields[$_REQUEST["sort_column_name"]]))
				{
					$order_str = " order by " . $avaliable_fields[$_REQUEST["sort_column_name"]]["column_name"];
					if(isset($_REQUEST["sort_order"]) && (strtolower($_REQUEST["sort_order"]) == 'asc' || strtolower($_REQUEST["sort_order"]) == 'desc'))
					{
						$order_str .= " " . strtolower($_REQUEST["sort_order"]);
					}
				}
				if($count > 0)
				{
					$res = Product::getOurProdsByPage($start, $count, $filter_str, $order_str);
				}
			}
			break;
		case 'get_shop_prods_need_for_update':
			$avaliable_fields = array(
				"sku" => array(
					"filter_type" => "text",
					"column_name" => "sku"),
				"prov_price" => array(
					"filter_type" => "float",
					"column_name" => "prov_price"),
				"new_price" => array(
					"filter_type" => "float",
					"column_name" => "(prov_price+margin)"),
				"margin" => array(
					"filter_type" => "float",
					"column_name" => "margin"),
				"price_markup" => array(
					"filter_type" => "float",
					"column_name" => "price_markup"),
				);
			$filter_str = "";
			if(isset($_REQUEST["filter_compare"])
				&& isset($_REQUEST["filter_values"])
				&& count($_REQUEST["filter_compare"])
				&& count($_REQUEST["filter_values"]))
			{
				$filter_str = buildFilterStr($avaliable_fields, $_REQUEST["filter_compare"], $_REQUEST["filter_values"]);
			}
			if($_REQUEST['subaction'])
			{
				switch($_REQUEST['subaction'])
				{
					case 'getcount':
						$res = Product::getShopProductsNeedsForUpdateCount($filter_str);
						break;
				}
			}
			else
			{
				$start = (int)$_REQUEST['start'];
				$count = (int)$_REQUEST['count'];
				$order_str = "";
				if(isset($_REQUEST["sort_column_name"]) && isset($avaliable_fields[$_REQUEST["sort_column_name"]]))
				{
					$order_str = " order by " . $avaliable_fields[$_REQUEST["sort_column_name"]]["column_name"];
					if(isset($_REQUEST["sort_order"]) && (strtolower($_REQUEST["sort_order"]) == 'asc' || strtolower($_REQUEST["sort_order"]) == 'desc'))
					{
						$order_str .= " " . strtolower($_REQUEST["sort_order"]);
					}
				}
				if($count > 0)
				{
					$res = Product::getShopProdsNeedForUpdateByPage($start, $count, $filter_str, $order_str);
				}
			}
			break;
		case 'get_prov_prods':
			$avaliable_fields = array(
				"prov_name" => array(
					"filter_type" => "text",
					"column_name" => "providers.name"),
				"prod_name" => array(
					"filter_type" => "text",
					"column_name" => "prod_name"),
				"price" => array(
					"filter_type" => "float",
					"column_name" => "price"),
				"stock" => array(
					"filter_type" => "int",
					"column_name" => "stock"),
				"last_update" => array(
					"filter_type" => "text",
					"column_name" => "last_update"),
				);
			$filter_str = "";
			if(isset($_REQUEST["filter_compare"])
				&& isset($_REQUEST["filter_values"])
				&& count($_REQUEST["filter_compare"])
				&& count($_REQUEST["filter_values"]))
			{
				$filter_str = buildFilterStr($avaliable_fields, $_REQUEST["filter_compare"], $_REQUEST["filter_values"]);
			}
			if($_REQUEST['subaction'])
			{
				switch($_REQUEST['subaction'])
				{
					case 'getcount':
						$res = Product::getProvProdsCount($filter_str);
						break;
				}
			}
			else
			{
				$start = (int)$_REQUEST['start'];
				$count = (int)$_REQUEST['count'];
				$order_str = "";
				if(isset($_REQUEST["sort_column_name"]) && isset($avaliable_fields[$_REQUEST["sort_column_name"]]))
				{
					$order_str = " order by " . $avaliable_fields[$_REQUEST["sort_column_name"]]["column_name"];
					if(isset($_REQUEST["sort_order"]) && (strtolower($_REQUEST["sort_order"]) == 'asc' || strtolower($_REQUEST["sort_order"]) == 'desc'))
					{
						$order_str .= " " . strtolower($_REQUEST["sort_order"]);
					}
				}
				if($count > 0)
				{
					$res = Product::getProvProdsByPage($start, $count, $filter_str, $order_str);
				}
			}
			break;
		case 'get_temp_prov_prods':
			$avaliable_fields = array(
				"prod_name" => array(
					"filter_type" => "text",
					"column_name" => "prod_name"),
				"price" => array(
					"filter_type" => "float",
					"column_name" => "price"),
				"stock" => array(
					"filter_type" => "int",
					"column_name" => "stock"),
				"prov_id" => array(
					"filter_type" => "int",
					"column_name" => "prov_id")
				);
			$filter_str = "";
			if(isset($_REQUEST["filter_compare"])
				&& isset($_REQUEST["filter_values"])
				&& count($_REQUEST["filter_compare"])
				&& count($_REQUEST["filter_values"]))
			{
				$filter_str = buildFilterStr($avaliable_fields, $_REQUEST["filter_compare"], $_REQUEST["filter_values"]);
			}
			if($_REQUEST['subaction'])
			{
				switch($_REQUEST['subaction'])
				{
					case 'getcount':
						$res = Product::getTempProvProdsCount($filter_str);
						break;
				}
			}
			else
			{
				$start = (int)$_REQUEST['start'];
				$count = (int)$_REQUEST['count'];
				$order_str = "";
				if(isset($_REQUEST["sort_column_name"]) && isset($avaliable_fields[$_REQUEST["sort_column_name"]]))
				{
					$order_str = " order by " . $avaliable_fields[$_REQUEST["sort_column_name"]]["column_name"];
					if(isset($_REQUEST["sort_order"]) && (strtolower($_REQUEST["sort_order"]) == 'asc' || strtolower($_REQUEST["sort_order"]) == 'desc'))
					{
						$order_str .= " " . strtolower($_REQUEST["sort_order"]);
					}
				}
				if($count > 0)
				{
					$res = Product::getTempProvProdsByPage($start, $count, $filter_str, $order_str);
				}
			}
			break;
		case 'reload_shop_prods_need_for_update':
			Product::insertShopProductsNeedForUpdate();
			break;
		case 'get_shop_prods_need_for_update_link':
			$res = Converter::getShopProdsNeedForUpdateLink();
			break;
	}
	echo json_encode($res);
}
catch(Exception $e)
{
	die($e->getMessage());
}
?>
