<?php

require_once dirname(__FILE__) . "/DB.php";

class Providers
{
	private static $providers = null;
	private static $providersIdsByNames = null;
	public static function &getProviders()
	{
		if(self::$providers == null)
		{
			$db = &DB::singleton();
			$providers = array();
			try
			{
				$providers = $db->getRowsAssocArray("select * from providers");
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
			for($i = 0; $i < count($providers); $i++)
			{
				self::$providers[$providers[$i]['id']] = $providers[$i];
				unset(self::$providers[$providers[$i]['id']]['id']);
			}
		}
		return self::$providers;
	}
	public static function getProvidersAndCounters()
	{
		$db = &DB::singleton();
		$res = array();
		$get_provs = "
		select
			providers.*,
			provs_prods.prods_count
		from
			providers
			left outer join
				(select
					prov_id,
					count(*) prods_count
				from
					providers_products
				group by prov_id) provs_prods
			on provs_prods.prov_id = providers.id
			order by name";
		$res = $db->getRowsAssocArray($get_provs);
		return $res;
	}
	public static function isProvExists($name)
	{
		$db = &DB::singleton();
		$get_prov = "select id from providers where name='" . mysql_escape_string($name) . "' limit 1";
		$res = $db->getValue($get_prov);
		return $res;
	}
	public static function insertProv($name, $prog_name, $use_for_prices)
	{
		$db = &DB::singleton();
		$insert_prov = "insert providers set name='" . mysql_escape_string($name) . "', prog_name='" . mysql_escape_string($prog_name) . "', use_for_our_prices=$use_for_prices";
		$res = $db->getAffectedRows($insert_prov);
		if($res)
		{
			$res = 	$db->getLastId();
		}
		self::$providers = null;
		return $res;
	}
	public static function updateProv($id, $name, $prog_name, $use_for_prices)
	{
		$db = &DB::singleton();
		$update_prov = "update providers set name='" . mysql_escape_string($name) . "', prog_name='" . mysql_escape_string($prog_name) . "', use_for_our_prices=$use_for_prices where id=$id limit 1";
		$res = $db->getAffectedRows($update_prov);
		self::$providers = null;
		return $res;
	}
	public static function removeProvider($id)
	{
		$db = &DB::singleton();
		$res = 0;
		$delete_products = "delete from providers_products where providers_products.prov_id=$id";
		$delete_provider = "delete providers from providers where providers.id=$id";
		$res = $db->getAffectedRows($delete_products);
		$res = $db->getAffectedRows($delete_provider);
		self::$providers = null;
		return $res;
	}
	public static function &getProvidersIdsByNamesArray()
	{
		if(self::$providersIdsByNames == null)
		{
			$provs = &self::getProviders();
			foreach( $provs as $key => $value)
			{
				self::$providersIdsByNames[$value['name']] = $key;
			}
		}
		return self::$providersIdsByNames;
	}
}
?>
