<?php
require_once dirname(__FILE__) . "/DB.php";
require_once dirname(__FILE__) . "/Providers.php";
class Product
{
	public static function getCategories()
	{
		static $return_res = null;
		if(is_null($return_res))
		{
			$db = &DB::singleton();
			$get_attribute_sets = "select
				catalog_category_entity.entity_id,
				concat(repeat('&nbsp;&nbsp;&nbsp;&nbsp;', length(path) - length(replace(path, '/', '')) - 1), catalog_category_entity_varchar.value, '&nbsp;&nbsp;') catalog_name
			from
				catalog_category_entity,
				catalog_category_entity_varchar,
				eav_attribute
			where
				eav_attribute.entity_type_id = 3
				and eav_attribute.attribute_code = 'name'
				and eav_attribute.attribute_id = catalog_category_entity_varchar.attribute_id
				and path regexp '^[0-9]+/" . ROOT_CATEGORY . "(/|$)'
				and catalog_category_entity_varchar.entity_id=catalog_category_entity.entity_id";
			$db->selectDb(MYSQL_DB_STORE);
			$res = $db->getRowsAssocArray($get_attribute_sets);
			$db->selectDb(MYSQL_DB);
			$res_count = count($res);
			$return_res = array();
			for($i = 0; $i < $res_count; $i++)
			{
				$return_res[$res[$i]['entity_id']] = $res[$i]['catalog_name'];
			}
		}
		return $return_res;
	}
	public static function isProductExistInShop($sku)
	{
		$res = false;
		$db = &DB::singleton();
		$get_product = "select 1 from catalog_product_entity where sku='" . mysql_escape_string($sku) . "' limit 1";
		$db->selectDb(MYSQL_DB_STORE);
		$res = $db->getValue($get_product);
		$db->selectDb(MYSQL_DB);
		$res = (count($res) > 0);
		return $res;
	}
	public static function getAttributeSets()
	{
		static $return_res = null;
		if(is_null($return_res))
		{
			$db = &DB::singleton();
			$get_attribute_sets = "select attribute_set_id,attribute_set_name from eav_attribute_set where entity_type_id=4";
			$db->selectDb(MYSQL_DB_STORE);
			$res = $db->getRowsAssocArray($get_attribute_sets);
			$db->selectDb(MYSQL_DB);
			$res_count = count($res);
			$return_res = array();
			for($i = 0; $i < $res_count; $i++)
			{
				$return_res[$res[$i]['attribute_set_id']] = $res[$i]['attribute_set_name'];
			}
		}
		return $return_res;
	}
	public static function isProductExists($name)
	{
		$db = &DB::singleton();
		$get_prod = "select id from our_products where sku='" . mysql_escape_string($name) . "' limit 1";
		$res = $db->getValue($get_prod);
		return $res;
	}
	public static function deleteOurProduct($id)
	{
		$db = &DB::singleton();
		$del_prod = "delete from our_products where id=$id";
		$update_prods = "update providers_products set our_prod_id=0 where our_prod_id=$id";
		$res = $db->getAffectedRows($del_prod);
		$res = $db->getAffectedRows($update_prods);
		return $res;
	}
	public static function insertProduct($name, $ignore = false)
	{
		$db = &DB::singleton();
		$set_name = "insert " . ($ignore ? 'ignore ' : '') . "our_products set sku='" . mysql_escape_string($name) . "'";
		$res = $db->getAffectedRows($set_name);
		if($res)
		{
			$res = 	$db->getLastId();
		}
		return $res;
	}
	public static function setName($id, $name)
	{
		$db = &DB::singleton();
		$set_name = "update our_products set sku='" . mysql_escape_string($name) . "' where id=" . $id . " limit 1";
		$res = $db->getAffectedRows($set_name);
		return $res;
	}
	public static function setRelations($our_prod_id, $prov_prod_ids)
	{
		$db = &DB::singleton();
		$set_relations = "update providers_products set our_prod_id=$our_prod_id where id=".implode(' or id=', $prov_prod_ids);
		$res = $db->getAffectedRows($set_relations);
		return $res;
	}
	public static function removeRelations($our_prod_id)
	{
		$db = &DB::singleton();
		$set_relations = "update providers_products set our_prod_id=0 where our_prod_id=$our_prod_id";
		$res = $db->getAffectedRows($set_relations);
		return $res;
	}
	public static function getTempProvProdsByPage($start, $count, $filter_str, $order_str)
	{
		$db = &DB::singleton();
		if(strlen($filter_str))
		{
			$filter_str = " where " . $filter_str;
		}
		$res = $db->getRowsAssocArray("select prod_name, price, stock from providers_products_temp $filter_str $order_str limit $start,$count");
		return $res;
	}
	public static function getTempProvProdsCount($filter_str)
	{
		$db = &DB::singleton();
		if(strlen($filter_str))
		{
			$filter_str = " where " . $filter_str;
		}
		$res = $db->getValue("select count(*) from providers_products_temp $filter_str");
		return $res;
	}
	public static function getProvProdsByPage($start, $count, $filter_str, $order_str)
	{
		$db = &DB::singleton();
		if(strlen($filter_str))
		{
			$filter_str = " and " . $filter_str;
		}
		$res = $db->getRowsAssocArray("select
			providers.name as prov_name,
			providers_products.prod_name,
			providers_products.price,
			providers_products.stock,
			providers_products.last_update
		from
			providers_products,
			providers
		where
			providers_products.prov_id = providers.id
			$filter_str $order_str limit $start,$count");
		return $res;
	}
	public static function getProvProdsCount($filter_str)
	{
		$db = &DB::singleton();
		if(strlen($filter_str))
		{
			$filter_str = " and " . $filter_str;
		}
		$res = $db->getValue("select
			count(*)
		from
			providers_products,
			providers
		where
			providers_products.prov_id = providers.id
			$filter_str");
		return $res;
	}
	public static function getShopProdsNeedForUpdateByPage($start, $count, $filter_str, $order_str)
	{
		$db = &DB::singleton();
		if(strlen($filter_str))
		{
			$filter_str = " and " . $filter_str;
		}
		$res = $db->getRowsAssocArray("select sku, prov_price, prov_price+margin as new_price, margin, price_markup from shop_products_update_temp where prov_stock=50 $filter_str $order_str limit $start,$count");
		return $res;
	}
	public static function getShopProductsNeedsForUpdateCount($filter_str)
	{
		$db = &DB::singleton();
		if(strlen($filter_str))
		{
			$filter_str = " and " . $filter_str;
		}
		$res = $db->getValue("select count(*) from shop_products_update_temp where prov_stock=50 $filter_str");
		return $res;
	}
	public static function getOurProdsByPage($start, $count, $filter_str, $order_str)
	{
		$db = &DB::singleton();
		$db->selectDb(MYSQL_DB_STORE);
		if(strlen($filter_str))
		{
			$filter_str = " where " . $filter_str;
		}
		//$res = $db->getRowsAssocArray("select id,sku,web_price,web_stock from our_products $filter_str $order_str limit $start,$count");

		$provs = &Providers::getProviders();
		$columns = "min_prods.price as price, ";
		$join_str = "left join (select sku,price from " . MYSQL_DB . ".providers_products where is_min_price=1 group by sku) as min_prods on min_prods.sku=e.sku";
		foreach($provs as $id => $prov)
		{
			$columns .= "providers_products_$id.price as providers_products_{$id}_price, ";
			$join_str .= " left join " . MYSQL_DB . ".providers_products as providers_products_$id on e.sku=providers_products_$id.sku and providers_products_$id.prov_id=$id";
		}
		$shop_prods = "SELECT $columns `e`.`sku`, `eav_attribute_option_value`.`value` AS `brand`, group_concat(catalog_category_entity_varchar.value separator ', ') AS `category` FROM `catalog_product_entity` AS `e` LEFT JOIN `catalog_product_entity_int` AS `_table_manufacturer` ON (_table_manufacturer.entity_id = e.entity_id) AND (_table_manufacturer.attribute_id='66') AND (_table_manufacturer.store_id=0) LEFT JOIN `eav_attribute_option_value` ON _table_manufacturer.value=eav_attribute_option_value.option_id and eav_attribute_option_value.store_id = 0 LEFT JOIN `catalog_category_entity` ON e.category_ids regexp concat('(^',catalog_category_entity.entity_id, '$)|(^',catalog_category_entity.entity_id,',)|(,',catalog_category_entity.entity_id,',)|(,',catalog_category_entity.entity_id,'$)') LEFT JOIN `catalog_category_entity_varchar` ON catalog_category_entity.entity_id=catalog_category_entity_varchar.entity_id and catalog_category_entity_varchar.store_id=0 and catalog_category_entity_varchar.attribute_id=31 $join_str $filter_str GROUP BY `e`.`entity_id` $order_str limit $start,$count";
		$res = $db->getRowsAssocArray($shop_prods);
		$db->selectDb(MYSQL_DB);
		return $res;
	}
	public static function getOurProductsCount($filter_str)
	{
		$db = &DB::singleton();
		$db->selectDb(MYSQL_DB_STORE);
		if(strlen($filter_str))
		{
			$filter_str = " where " . $filter_str;
		}
		//$res = $db->getValue("select count(*) from our_products $filter_str");
		$provs = &Providers::getProviders();
		$columns = "min_prods.price as min_price, ";
		$join_str = "left join (select sku,price from " . MYSQL_DB . ".providers_products where is_min_price=1 group by sku) as min_prods on min_prods.sku=e.sku";
		foreach($provs as $id => $prov)
		{
			$columns .= "providers_products_$id.price as providers_products_{$id}_price, ";
			$join_str .= " left join " . MYSQL_DB . ".providers_products as providers_products_$id on e.sku=providers_products_$id.sku and providers_products_$id.prov_id=$id";
		}
		$shop_prods_count = "select count(*) from (SELECT `e`.`entity_id` FROM `catalog_product_entity` AS `e` LEFT JOIN `catalog_product_entity_int` AS `_table_manufacturer` ON (_table_manufacturer.entity_id = e.entity_id) AND (_table_manufacturer.attribute_id='66') AND (_table_manufacturer.store_id=0) LEFT JOIN `eav_attribute_option_value` ON _table_manufacturer.value=eav_attribute_option_value.option_id and eav_attribute_option_value.store_id = 0 LEFT JOIN `catalog_category_entity` ON e.category_ids regexp concat('(^',catalog_category_entity.entity_id, '$)|(^',catalog_category_entity.entity_id,',)|(,',catalog_category_entity.entity_id,',)|(,',catalog_category_entity.entity_id,'$)') LEFT JOIN `catalog_category_entity_varchar` ON catalog_category_entity.entity_id=catalog_category_entity_varchar.entity_id and catalog_category_entity_varchar.store_id=0 and catalog_category_entity_varchar.attribute_id=31 $join_str $filter_str GROUP BY `e`.`entity_id`)  count_table";
		$res = $db->getValue($shop_prods_count);
		$db->selectDb(MYSQL_DB);
		return $res;
	}
	public static function getOurProductData($id)
	{
		$db = &DB::singleton();
		$get_product_data = "select
			our_products.id,
			our_products.sku,
			our_products.web_price,
			our_products.web_stock,
			provs.provs,
			provs.price,
			provs.stock
		from
			our_products
			left outer join
			(select
				$id our_prod_id,
				group_concat(providers_products.prov_id order by providers_products.prov_id asc separator ',') provs,
				min_provs.price,
				min_provs.stock
			from
				providers_products
				left outer join
				(select
					providers_products.our_prod_id,
					price,
					stock
				from
					providers_products
				where
					providers_products.our_prod_id = $id
					and providers_products.is_min_price = 1
				limit 1) min_provs
				on
				providers_products.our_prod_id = min_provs.our_prod_id
			where
				providers_products.our_prod_id = $id
			limit 1) provs
			on our_products.id = provs.our_prod_id
		where
			our_products.id = $id
		limit 1";
		$res = $db->getRowsAssocArray($get_product_data);
		if(count($res))
		{
			$res = $res[0];
			if(strlen($res['provs']))
			{
				$res['provs'] = explode(',', $res['provs']);
			}
			else
			{
				$res['provs'] = array();
			}
			if(strlen($res['min_provs']))
			{
				$res['min_provs'] = explode(',', $res['min_provs']);
			}
			else
			{
				$res['min_provs'] = array();
			}
		}
		else
			$res = null;
		return $res;
	}
	public static function getProvProds($prov_id, $our_prod_id = 0, $names = null)
	{
		$db = &DB::singleton();
		$get_prov_prods = "select id, prod_name, our_prod_id from providers_products where prov_id=$prov_id";
		if(!$our_prod_id)
		{
			$get_prov_prods .= " and our_prod_id=0";
		}
		if(is_array($names) && count($names))
		{
			$get_prov_prods .= " and prod_name rlike '" . mysql_escape_string(implode('|', $names)) . "'";
		}
		$res = $db->getRowsAssocArray($get_prov_prods);
		return $res;
	}
	public static function getProviderProductsForView($prov_id)
	{
		$db = &DB::singleton();
		$get_prov_prods = "
		select
			providers_products.prod_name,
			our_products.sku,
			providers_products.price,
			providers_products.stock,
			providers_products.last_update 
		from
			providers_products
			left outer join
			our_products
			on providers_products.our_prod_id = our_products.id
		where
			providers_products.prov_id = $prov_id";
		$res = $db->getRowsAssocArray($get_prov_prods);
		return $res;
	}
	public static function getOurProducts()
	{
		$db = &DB::singleton();
		$our_products_query = "
		select
			our_products.id,
			our_products.sku,
			our_products.web_price,
			our_products.web_stock,
			provs.provs,
			provs.min_provs,
			provs.price,
			provs.stock
		from
			our_products
			left outer join 
			(select
				providers_products.our_prod_id,
				group_concat(providers.name order by providers.name asc separator ', ') provs,
				min_provs.provs min_provs,
				min_provs.price,
				min_provs.stock
			from
				providers_products
				left outer join
					(select
						providers_products.our_prod_id,
						group_concat(providers.name order by providers.name asc separator ', ') provs,
						price,
						stock
					from
						providers_products,
						providers
					where
						providers.id = providers_products.prov_id
						and providers_products.our_prod_id > 0
						and providers_products.is_min_price = 1
					group by providers_products.our_prod_id
					) min_provs
				on providers_products.our_prod_id = min_provs.our_prod_id,
				providers
			where
				providers.id = providers_products.prov_id
				and providers_products.our_prod_id > 0
			group by providers_products.our_prod_id) provs
			on our_products.id=provs.our_prod_id
		order by our_products.sku";
		$res = $db->getRowsAssocArray($our_products_query);
		return $res;
	}
	public static function setRelation($our_prod_id, $prov_prod_id)
	{
		$db = &DB::singleton();
		$set_relation_query = "update providers_products set our_prod_id=$our_prod_id where id=$prov_prod_id";
		$res = $db->getAffectedRows($set_relation_query);
		return $res;
	}
	public static function setRelationByName($our_prod_name, $prov_id, $prod_name)
	{
		$db = &DB::singleton();
		$set_relation_query = "
		update
			providers_products
		set 
			providers_products.sku = '" . mysql_escape_string($our_prod_name) . "'
		where
			providers_products.prov_id=$prov_id
			and providers_products.sha_prod_name='" .  hash('sha256', $prod_name) . "'";
		$res = $db->getAffectedRows($set_relation_query);
		return $res;
	}
	public static function importOurProducts($file_path)
	{
		$db = &DB::singleton();
		$tmp_file_name = '';
		if(is_array($file_path))
		{
			if($file_path['type'] == 'text/csv')
			{
				$file_path = $file_path['tmp_name'];
			}
			else if($file_path['type'] == 'application/vnd.ms-excel' )
			{
				if(filesize($file_path['tmp_name']))
				{
					$tmp_file_name = tempnam('/tmp', 'xls2csv_relations_');
					$res = shell_exec("export LANG=en_US.UTF-8 && xls2csv -dUTF-8 -q3 -c, -x " . $file_path['tmp_name'] . ' > ' . $tmp_file_name);
					$file_path = $tmp_file_name;
				}
				else
				{
					throw new Exception("Empty file.");
				}
			}
			else
			{
				throw new Exception("Incorrect file.");
			}
		}
		if(filesize($file_path))
		{
			$fp = fopen($file_path, "r");
			if($fp)
			{
				$db->startTransaction();
				while (($data = fgetcsv($fp, 0, ',', '"')) !== false)
				{
					if(strlen($data[0]))
					{
						self::insertProduct($data[0], true);
					}
				}
				fclose($fp);
				$db->commit();
			}
			else
			{
				throw new Exception("Can't get access to " . $file_path);
			}
		}
		else
		{
			throw new Exception("Empty file.");
		}
		if(strlen($tmp_file_name))
		{
			unlink($tmp_file_name);
		}
		return true;
	}
	public static function importRelations($file_path)
	{
		$tmp_file_name = '';
		if(is_array($file_path))
		{
			if($file_path['type'] == 'text/csv')
			{
				$file_path = $file_path['tmp_name'];
			}
			else if($file_path['type'] == 'application/vnd.ms-excel' )
			{
				if(filesize($file_path['tmp_name']))
				{
					$tmp_file_name = tempnam('/tmp', 'xls2csv_relations_');
					$res = shell_exec("export LANG=en_US.UTF-8 && xls2csv -dUTF-8 -q3 -c, -x " . $file_path['tmp_name'] . ' > ' . $tmp_file_name);
					$file_path = $tmp_file_name;
				}
				else
				{
					throw new Exception("Empty file.");
				}
			}
			else
			{
				throw new Exception("Incorrect file.");
			}
		}
		if(filesize($file_path))
		{
			$fp = fopen($file_path, "r");
			if($fp)
			{
				$provs_ids = array();
				$provs = &Providers::getProvidersIdsByNamesArray();
				$data = fgetcsv($fp, 0, ',', '"');
				for($i = 1; $i < count($data); $i++)
				{
					if(isset($provs[$data[$i]]))
					{
						$provs_ids[$i] = $provs[$data[$i]];
					}
				}
				while (($data = fgetcsv($fp, 0, ',', '"')) !== false)
				{
					for($i = 1; $i < count($data); $i++)
					{
						if(isset($provs_ids[$i]) && strlen($data[$i]))
						{
							self::setRelationByName($data[0], $provs_ids[$i], $data[$i]);
						}
					}
				}
				fclose($fp);
			}
			else
			{
				throw new Exception("Can't get access to " . $file_path);
			}
		}
		else
		{
			throw new Exception("Empty file.");
		}
		if(strlen($tmp_file_name))
		{
			unlink($tmp_file_name);
		}
		return true;
	}
	public static function updateMinPrices()//DONE
	{
		$db = &DB::singleton();
//		$set_0_min_price_for_all = "update providers_products set is_min_price=0";
		$update_min_prices_query = "update providers_products,
				(select
					providers_products.id,
					if(providers_products.price = prices.price and providers_products.stock = stocks.stock, 1, 0) as new_is_min_price
				from
					(select
						sku,
						max(stock) stock
					from
						providers_products,
						providers
					where
						length(sku) > 0
						and providers_products.prov_id = providers.id
						and providers.use_for_our_prices = 1 
					group by sku
					) as stocks,
					(select
						sku,
						min(price) price
					from
						providers_products,
						providers
					where
						length(sku) > 0
						and providers_products.prov_id = providers.id
						and providers.use_for_our_prices = 1
					group by sku
					) as prices,
					providers_products
				where
					prices.sku = stocks.sku
					and providers_products.sku = prices.sku
					and providers_products.is_min_price != if(providers_products.price = prices.price and providers_products.stock = stocks.stock, 1, 0)) as new_is_min_prices
			set
				providers_products.is_min_price = new_is_min_prices.new_is_min_price
			where
				providers_products.id = new_is_min_prices.id";
//		$res = $db->getAffectedRows($set_0_min_price_for_all);
		$res = $db->getAffectedRows($update_min_prices_query);
		return $res;
	}
	public static function updateWebProducts()//DONE
	{
		$db = &DB::singleton();
	/*	$db = &DB::singleton();
		$update_web_prices = "
		update
			our_products,
			providers_products
		set
			our_products.web_price = providers_products.price,
			our_products.web_stock = providers_products.stock
		where
			providers_products.our_prod_id = our_products.id
			and providers_products.is_min_price = 1
			and (providers_products.price != our_products.web_price
				or providers_products.stock != our_products.web_stock)";
		$res = $db->getAffectedRows($update_web_prices);
		return $res;*/
		global $STOCKS;
		$res = 0;
		$need_for_update = self::getWebProductsNeedForUpdate();
		$need_for_update_count = count($need_for_update);
		$proxy = new SoapClient('http://nginx.dev:8080/api/soap/?wsdl');
		$sessionId = $proxy->login('product_updater', 'temp123');
		$attributes = array();
		for($i = 0; $i < $need_for_update_count; $i++)
		{
			$attributes = array();
			$inv_data = array();
			if($need_for_update[$i]['web_stock'] != $need_for_update[$i]['prov_stock'])
			{
				if($need_for_update[$i]['prov_stock'] == $STOCKS['not_in_stock'])
				{
					$inv_data['is_in_stock'] = 0;
					$inv_data["qty"] = 0;
					$attributes['availability_status'] = "";
				}
				else if($need_for_update[$i]['prov_stock'] == $STOCKS['in_stock'])
				{
					if($need_for_update[$i]['web_stock'] == $STOCKS['not_in_stock'])
					{
						$inv_data['is_in_stock'] = 1;
						$inv_data["qty"] = 10;
					}
					$attributes['availability_status'] = "";
				}
				else
				{
					if($need_for_update[$i]['web_stock'] == $STOCKS['not_in_stock'])
					{
						$inv_data['is_in_stock'] = 1;
						$inv_data["qty"] = 10;
					}
					$attributes['availability_status'] = $need_for_update[$i]['prov_stock'];
				}
			}
			if($need_for_update[$i]['qty'] == 0
				&& $need_for_update[$i]['prov_stock'] != $STOCKS['not_in_stock'])
			{
				$inv_data["qty"] = 10;
			}
			else if($need_for_update[$i]['qty'] != 0
				&& $need_for_update[$i]['prov_stock'] == $STOCKS['not_in_stock'])
			{
				$inv_data["qty"] = 0;
			}
			if($need_for_update[$i]['cost'] != $need_for_update[$i]['prov_price'])
			{
				$attributes['cost'] = $need_for_update[$i]['prov_price'];
			}
			$real_price = $need_for_update[$i]['prov_price'] + $need_for_update[$i]['margin'];
			if($real_price != $need_for_update[$i]['price'])
			{
				$attributes['price'] = $real_price;
			}
			try
			{
				if(count($attributes))
				{
					$proxy->call($sessionId, 'product.update', array($need_for_update[$i]['sku'], $attributes, 'default'));
				}
				if(count($inv_data))
				{
					$proxy->call($sessionId, 'product_stock.update', array($need_for_update[$i]['sku'], $inv_data));
				}
				$res++;
			}
			catch(Exception $e)
			{
				print_r($e);
			}
		}
		$res = $db->getAffectedRows('DELETE FROM shop_products_update_temp');
		return $res;
	}
	public static function insertShopProductsNeedForUpdate()
	{
		$db = &DB::singleton();
		$res = $db->getAffectedRows('DELETE FROM shop_products_update_temp');
		$db->selectDb(MYSQL_DB_STORE);
 		$insert_shop_prods = "insert into ".MYSQL_DB.".shop_products_update_temp(sku, cost,price,web_stock,price_markup,prov_price,prov_stock,margin) ".self::getNeedToChangeProductsRequest();
		$res = $db->getAffectedRows($insert_shop_prods);
		$db->selectDb(MYSQL_DB);
		return $res;
	}
	private static function getNeedToChangeProductsRequest() {
		$mage_dir = dirname(__FILE__).'/../../okshop.com.ua/';
		require_once $mage_dir . 'app/Mage.php';
		$storeId = 0;
		$websiteId = Mage::app ()->getStore ( $storeId )->getWebsiteId ();
		$product = Mage::getModel ( 'catalog/product' );
		$specials = $product->setStoreId($storeId)->getResourceCollection();
		$specials->addAttributeToSelect("availability_status", 'left');
		$specials->getSelect()->reset(Zend_Db_Select::COLUMNS);
		$specials->getSelect()->columns('sku');
		$specials->addAttributeToSelect(array("cost", "price"), 'left');
		$specials->getSelect()->joinLeft("eav_attribute_option_value", "_table_availability_status.value=eav_attribute_option_value.option_id and eav_attribute_option_value.store_id=$storeId", "eav_attribute_option_value.value as web_stock");
		$specials->getSelect()->joinLeft( array("price_markups" => new Zend_Db_Expr("(select catalog_category_product.product_id, max(convert(catalog_category_entity_varchar.value, DECIMAL(6,3))) as price_markup from catalog_category_product,catalog_category_entity_varchar where catalog_category_entity_varchar.attribute_id=" . CATEGORY_MURKUP_ATTRIBUTE_ID . " and catalog_category_product.category_id=catalog_category_entity_varchar.entity_id group by catalog_category_product.product_id)")), "e.entity_id=price_markups.product_id", "price_markup");
		$specials->getSelect()->joinLeft("providers_products", "providers_products.sku=e.sku and " . MYSQL_DB . ".providers_products.is_min_price = 1", array("price as prov_price", "stock as prov_stock"), MYSQL_DB);
		$specials->getSelect()->joinLeft("cataloginventory_stock_item as _table_inventory_in_stock", " (_table_inventory_in_stock.product_id=e.entity_id) ", array());
		$specials->getSelect()->columns(array("margin" => new Zend_Db_Expr("if(" . MYSQL_DB . ".providers_products.price*price_markups.price_markup*0.01 is null, 0, " . MYSQL_DB . ".providers_products.price*price_markups.price_markup*0.01)")));
		$specials->addAttributeToSelect(array("availability_status", "cost", "price"), 'left');
		$specials->getSelect()->group('e.entity_id');
		$specials->getSelect()->where(" ( ( " . MYSQL_DB . ".providers_products.price is null
					and `_table_cost`.`value` is not null)
				or ( " . MYSQL_DB . ".providers_products.price is not null
					and `_table_cost`.`value` is null)
				or ( " . MYSQL_DB . ".providers_products.price is not null
					and `_table_cost`.`value` is not null
					and " . MYSQL_DB . ".providers_products.price <> `_table_cost`.`value`)
				or ( " . MYSQL_DB . ".providers_products.`stock` is null
					and eav_attribute_option_value.value is not null)
				or ( " . MYSQL_DB . ".providers_products.`stock` is not null
					and eav_attribute_option_value.value is null)
				or ( " . MYSQL_DB . ".providers_products.`stock` is not null
					and eav_attribute_option_value.value is not null
					and " . MYSQL_DB . ".providers_products.`stock` <> eav_attribute_option_value.value)
				or ( " . MYSQL_DB . ".providers_products.`stock` is not null
					and " . MYSQL_DB . ".providers_products.`stock` = 10
					and `_table_inventory_in_stock`.`is_in_stock` = 1 )
				or ( " . MYSQL_DB . ".providers_products.`stock` is null
					and `_table_inventory_in_stock`.`is_in_stock` = 1 ) ) ");
		return $specials->getSelect();
	}
	public static function getWebProductsNeedForUpdate()//DONE
	{
		$db = &DB::singleton();
		$get_need_for_updated_query = "select * from shop_products_update_temp";
		$need_for_update = $db->getRowsAssocArray($get_need_for_updated_query);
		return $need_for_update;
	}
	public static function deleteTempProd($prov_id)//DONE
	{
		$db = &DB::singleton();
		$res = null;
		$res = $db->getAffectedRows('DELETE FROM providers_products_temp WHERE prov_id=' . $prov_id);
		return $res;
	}
	public static function startInsertTempProd()
	{
		$db = &DB::singleton();
		$db->startTransaction();
	}
	public static function endInsertTempProd()
	{
		$db = &DB::singleton();
		$db->commit();
	}
	public static function insertTempProd($prov_id, $prod_name, $price, $stock)//DONE
	{
		$db = &DB::singleton();
		$res = null;
		$res = $db->getAffectedRows(" INSERT
			providers_products_temp (prov_id, prod_name, sha_prod_name, price,stock)
			values($prov_id, '" . mysql_escape_string($prod_name) . "', '" . hash('sha256', $prod_name) . "', $price, $stock)
		ON DUPLICATE KEY UPDATE price = IF(values(price) < price or values(stock) > stock, values(price), price), stock=IF(values(price) < price or values(stock) > stock, values(stock), stock)");
		return $res;
	}
	public static function getTempProds($prov_id)//DONE
	{
		$db = &DB::singleton();
		$res = array();
		$res = $db->getRowsAssocArray("
		select
			providers_products_temp.prod_name,
			providers_products_temp.price,
			providers_products_temp.stock
		from
			providers_products_temp
		where
			providers_products_temp.prov_id = $prov_id");
		return $res;
	}
	public static function importProducts($provider_id)//DONE
	{
		global $STOCKS;
		$db = &DB::singleton();
		$rows = array();
		$last_update = date("Y-m-d");
		$provider_id = (int)$provider_id;
		$last_update_query = "UPDATE providers SET last_update='$last_update' WHERE id=$provider_id limit 1";
		$insert_update = "insert
				into providers_products(prov_id,prod_name, sha_prod_name,price,stock,last_update)
			select
				providers_products_temp.prov_id,
				providers_products_temp.prod_name,
				providers_products_temp.sha_prod_name,
				providers_products_temp.price,
				providers_products_temp.stock,
				'$last_update'
			from providers_products_temp
			where
				providers_products_temp.prov_id = $provider_id
			ON DUPLICATE KEY UPDATE price=values(price),stock=values(stock),last_update=values(last_update)";
		$db->getAffectedRows($last_update_query);
		$db->getAffectedRows($insert_update);
		return true;
	}
}
?>
