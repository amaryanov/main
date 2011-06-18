<?php
/**
* Code for converting files to the correct Magento format.
*
* PHP version 5
*
* @author     Anton Maryanov <amaryanov@gmail.com>
* @copyright  Anton Maryanov
* @license    GNU GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
* @version    SVN: $Id$
*/

require_once dirname(__FILE__) . '/Product.php';
require_once dirname(__FILE__) . '/Providers.php';
require_once dirname(__FILE__) . '/DB.php';

/**
* Class contains functionality for converting pricelists and products data list
* to the correct Magento format.
*/

class Converter
{

	public static function convert($provider_id, $file_path)
	{
		global $STOCKS, $implemented_convertors;
		$providers = Providers::getProviders();
		if(!in_array($providers[$provider_id]['prog_name'], $implemented_convertors ))
		{
			throw new Exception('Convertion does not implemented for this provider.');
		}
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
					$tmp_file_name = tempnam('/tmp', 'xls2csv_pricelist_');
					$additional_conf = '';
					switch($providers[$provider_id]['prog_name'])
					{
						case 'SouthPalmira':
						case 'medterm':
							$additional_conf = ' -scp1251 ';
							break;
					}
					$res = shell_exec('export LANG=en_US.UTF-8 && xls2csv -dUTF-8 -q3 -c, -x '
						. $additional_conf . $file_path['tmp_name'] . ' > ' . $tmp_file_name);
					$file_path = $tmp_file_name;
				}
				else
				{
					throw new Exception('Empty file.');
				}
			}
			else
			{
				throw new Exception('Incorrect file.');
			}
		}
		if(filesize($file_path))
		{
			$fp = fopen($file_path, 'r');
			if($fp)
			{
				$row = 0;
				$new_fields = array('prov_sku' => '', 'price' => '', 'stock' => '');
				Product::deleteTempProd($provider_id);
				Product::startInsertTempProd();
				while (($data = fgetcsv($fp, 0, ',', '"')) !== false)
				{
					$row++;
					$new_fields['prov_sku'] = '';
					$new_fields['price'] = '0';
					$new_fields['stock'] = $STOCKS['not in stock'];
					switch($providers[$provider_id]['prog_name'])
					{
						case 'preximD': //Прексим Д
							if(!strlen($data[1]))
							{
								continue 2;
							}
							$new_fields['prov_sku'] = $data[0];
							$new_fields['price'] = (string)$data[1];
							$new_fields['stock'] = $STOCKS['in_stock'];
							break;
						case 'SouthPalmira':
							if($row < 3 || !strlen($data[2]))
							{
								continue 2;
							}
							$new_fields['prov_sku'] = $data[2];
							$new_fields['price'] = (string)$data[3];
							$new_fields['stock'] = $STOCKS['in_stock'];
							break;
						case 'NTCom':
							if($row < 9 || !strlen($data[2]))
							{
								continue 2;
							}
							$new_fields['prov_sku'] = $data[2];
							switch(trim($data[8]))
							{
								case '+':
								case '+/-':
									$new_fields['price'] = (string)$data[4];
									$new_fields['stock'] = $STOCKS['in_stock'];
									break;
								case '-':
									$new_fields['stock'] = $STOCKS['not_in_stock'];
									break;
								case 'в пути':
									$new_fields['stock'] = $STOCKS['wait'];
									break;
								case 'под заказ':
									$new_fields['stock'] = $STOCKS['order'];
									break;
							}
							break;
						case 'medterm':
							if($row < 9 || !strlen($data[1]) || !strlen($data[0]))
							{
								continue 2;
							}
							$new_fields['prov_sku'] = $data[1];
							$new_fields['stock'] = $STOCKS['adjust'];
							break;
						case 'vodovorot':
							if($row < 7 || !strlen($data[1]) || !strlen($data[2]))
							{
								continue 2;
							}
							$new_fields['prov_sku'] = $data[1];
							$new_fields['stock'] = $STOCKS['adjust'];
							break;
						case 'foxtrot':
							if(!strlen($data[0]) || !strlen($data[1]))
							{
								continue 2;
							}
							$new_fields['prov_sku'] = $data[0];
							$new_fields['price'] = (string)$data[1];
							$new_fields['stock'] = $STOCKS['in_stock'];
							break;
					}
					try
					{
						Product::insertTempProd($provider_id, $new_fields['prov_sku'],
							$new_fields['price'], $new_fields['stock']);
					}
					catch(Exception $e)
					{
						echo 'There was an error: ' . $e->getMessage();
					}
				}
				Product::endInsertTempProd();
				fclose($fp);
			}
			else
			{
				throw new Exception('Cant get access to ' . $file_path);
			}
		}
		else
		{
			throw new Exception('Empty file.');
		}
		if(strlen($tmp_file_name))
		{
			unlink($tmp_file_name);
		}
		return true;
	}
	public static function convertForImport(
		$attribute_set_id,
		$file_path,
		$rows_per_file = 50,
		$category_ids = '',
		$add_update_products = 'update',
		$out_of_stock = false,
		$add_images = false,
		$exclude_sku_list = null,
		$import_language = 'EN',
		$attribytes_by_groups = true,
		$name_manuf_fix = array())
	{
		global $STOCKS;
		$db = &DB::singleton();
		$tmp_file_name = '';
		if(is_array($file_path))
		{
			if($file_path['type'] == 'text/csv')
			{
				$file_path = $file_path['tmp_name'];
			}
			else if($file_path['type'] == 'application/vnd.ms-excel'
				|| $file_path['type'] == 'application/msexcel')
			{
				if(filesize($file_path['tmp_name']))
				{
					$tmp_file_name = tempnam('/tmp', 'xls2csv_prodict_import_');
					$additional_conf = '';
					$res = shell_exec('export LANG=en_US.UTF-8 && xls2csv -dUTF-8 -q3 -c, -x '
						. $additional_conf . $file_path['tmp_name'] . ' > ' . $tmp_file_name);
					$file_path = $tmp_file_name;
				}
				else
				{
					throw new Exception('Empty file.');
				}
			}
			else
			{
				throw new Exception('Incorrect file.');
			}
		}
		if(filesize($file_path))
		{
			$fp = fopen($file_path, 'r');
			if($fp)
			{
				$exclude_skus = array();
				if(!is_null($exclude_sku_list))
				{
					$exclude_skus = file($exclude_sku_list['tmp_name'],
						FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT);
				}
				$attribute_sets = Product::getAttributeSets();
				$dir_name = PROJECT_HOME . '/dl/' . self::trans(mb_strtolower($attribute_sets[$attribute_set_id]))
					. '_' . date('d.m.y-H.i.s');
				if(@mkdir($dir_name))
				{
					$column_names = array();
					$column_names_from_file = fgetcsv($fp, 0, ',', '"');
					$column_names_from_file_count = count($column_names_from_file);
					$cur_column_name = '';
					$not_found_columns = array();
					require_once MAGE_DIR . '/app/Mage.php';
					$storeId = 0;
					$websiteId = Mage::app ()->getStore ( $storeId )->getWebsiteId ();
					$attrs = Mage::getResourceModel('catalog/product_attribute_collection')
						->setAttributeSetFilter($attribute_set_id)
						->addVisibleFilter()
						->checkConfigurableProducts();
					$attrs->getSelect()->joinLeft('eav_attribute_group',
						'entity_attribute.attribute_group_id = eav_attribute_group.attribute_group_id',
						'eav_attribute_group.attribute_group_name');
					$attrs = $attrs->load();
					$attrs_group = array();
					$attributes = array();
					$attributes_types = array();
					$select_values = array();
					foreach($attrs as $attr)
					{
						if(!isset($attrs_group[$attr->getAttributeGroupName()]))
						{
							$attrs_group[$attr->getAttributeGroupName()] = array();
						}
						$code = $attr->getAttributeCode();
						$label = $attr->getFrontendLabel();
						$attrs_group[$attr->getAttributeGroupName()][$label] = $code;
						$attributes[$label] = $code;
						$attr_type = $attr->getFrontendInput();
						$attributes_types[$code] = $attr_type;
						if($attr_type == 'select' || $attr_type == 'multiselect')
						{
							$select_values[$code] = array();
							$optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
								->setAttributeFilter($attr->getAttributeId())
								//	->setStoreFilter(0, false)
								->setPositionOrder('desc', true)
								->load();
							foreach($optionCollection as $option)
							{
								$select_values[$code][] = $option->getValue();
							}
						}
					}
					unset($attrs);
					if($attribytes_by_groups)
					{
						$cur_group = '';
						for($i = 0; $i < $column_names_from_file_count; $i++)
						{
							if(substr($column_names_from_file[$i], 0, 2) == '**'
								&& substr($column_names_from_file[$i], 0, -2))
							{
								$cur_group = substr($column_names_from_file[$i], 2,
									strlen($column_names_from_file[$i]) - 4);
								continue;
							}
							if($cur_group)
							{
								$cur_column_name = '';
								if(isset($attrs_group[$cur_group])
									&& isset($attrs_group[$cur_group][$column_names_from_file[$i]]))
								{
									$column_names[$attrs_group[$cur_group][$column_names_from_file[$i]]] = $i;
								}
								else if(isset($attrs_group[$cur_group])
									&& in_array($column_names_from_file[$i], $attrs_group[$cur_group]))
								{
									$column_names[$column_names_from_file[$i]] = $i;
								}
								else
								{
									$not_found_columns[] = $column_names_from_file[$i];
								}
							}
						}
					}
					else
					{
						for($i = 0; $i < $column_names_from_file_count; $i++)
						{
							$cur_column_name = '';
							$cur_column_name = $attributes[$column_names_from_file[$i]];
							if(strlen($cur_column_name))
							{
								$column_names[$cur_column_name] = $i;
							}
							else if(in_array($column_names_from_file[$i], $attributes))
							{
								$column_names[$column_names_from_file[$i]] = $i;
							}
							else
							{
								$not_found_columns[] = $column_names_from_file[$i];
							}
						}
					}
					if(count($not_found_columns))
					{
						echo '<h2>Notfound attributes:</h2><p>' . implode('<br>', $not_found_columns) . '</p>';
					}
					if(!isset($column_names['sku']) && isset($column_names['name']))
					{
						$column_names['sku'] = $column_names['name'];
						if($add_update_products == 'update')
						{
							unset($column_names['name']);
						}
					}
					if(!isset($column_names['sku']))
					{
						rmdir($dir_name);
						throw new Exception('SKU is important.');
					}
					if(count($name_manuf_fix) && !isset($column_names['manufacturer']))
					{
						rmdir($dir_name);
						throw new Exception('Manufacturer is important.');
					}
					if(count($column_names))
					{
						$additional_columns = array();
						$dyn_additional_columns = array();
						if($category_ids != 'dummy')
						{
							$additional_columns['category_ids'] = $category_ids;
						}
						$low_stock_date = gmdate('Y-m-d H:i:s');
						if($out_of_stock)
						{
							$additional_columns['qty'] = '0';
							$additional_columns['low_stock_date'] = $low_stock_date;
							$additional_columns['is_in_stock'] = '0';
						}
						else if(isset($column_names['availability_status']))
						{
							$dyn_additional_columns[] = 'qty';
							$dyn_additional_columns[] = 'low_stock_date';
							$dyn_additional_columns[] = 'is_in_stock';
						}
						if($add_images)
						{
							$dyn_additional_columns[] = 'image';
							$dyn_additional_columns[] = 'small_image';
							$dyn_additional_columns[] = 'thumbnail';
						}
						if($add_update_products == 'add')
						{
							$additional_columns['store'] = 'admin';
							$additional_columns['websites'] = 'base';
							$additional_columns['visibility'] = $GLOBALS['_'][$import_language]['conv4imp']['Каталог, поиск'];
							$additional_columns['tax_class_id'] = $GLOBALS['_'][$import_language]['conv4imp']['Нет'];
							$additional_columns['status'] = $GLOBALS['_'][$import_language]['conv4imp']['Включено'];
							if(!isset($column_names['weight']))
							{
								$additional_columns['weight'] = '1';
							}
							$additional_columns['type'] = 'simple';
							if(!isset($column_names['price']))
							{
								$additional_columns['price'] = '0';
							}
							$additional_columns['attribute_set'] = $attribute_sets[$attribute_set_id];
							$dyn_additional_columns[] = 'url_key';
							$dyn_additional_columns[] = 'meta_keyword';
						}
						$short_desc_id = -1;
						if(isset($column_names['description']) && !isset($column_names['short_description']))
						{
							$desc_id = array_search('description', array_keys($column_names));
							$dyn_additional_columns[] = 'short_description';
						}
						$all_column_names = array_merge(array_keys($column_names),
							array_keys($additional_columns), $dyn_additional_columns);
						$file_to_write = fopen($dir_name . '/' . basename($dir_name) . '_1.csv', 'w');
						fputcsv($file_to_write, $all_column_names, ',', '"');
						$row = 0;
						$cur_row = array();
						$sku_id = array_search('sku', array_keys($column_names));
						$name_prefx_id = array_search('name_prefix', array_keys($column_names));
						$availability_status_id = array_search('availability_status', array_keys($column_names));
						$name_id = array_search('name', array_keys($column_names));
						$add_update_products_error_skus = array();
						while (($data = fgetcsv($fp, 0, ',', '"')) !== false)
						{
							$cur_row = array();
							foreach($column_names as $column_name => $id)
							{
								if(($attributes_types[$column_name] == 'select'
									|| $attributes_types[$column_name] == 'multiselect')
									&& strlen($data[$id]))
								{
									$found = false;
									for($i = 0; $i < count($select_values[$column_name]); $i++)
									{
										if(mb_strtolower($select_values[$column_name][$i]) == mb_strtolower($data[$id]))
										{
											$found = true;
											$cur_row[] = $select_values[$column_name][$i];
											break;
										}
									}
									if(!$found)
									{
										printf(INCORRECT_VALUE_FOR_SELECT,
											$data[$id], $column_name, $row + 1);
										$cur_row[] = '';
									}
								}
								else
								{
									$cur_row[] = $data[$id];
								}
								switch($column_name)
								{
									case 'description':
										if(!strlen($cur_row[count($cur_row) - 1]))
										{
											$cur_row[count($cur_row) - 1] = NO_DESCRIPTION;
										}
										else
										{
											$cur_row[count($cur_row) - 1] = strip_tags($cur_row[count($cur_row) - 1]);
										}
										break;
								}
							}
							if(count($name_manuf_fix))
							{
								if($add_update_products == 'add')
								{
									if(isset($name_manuf_fix['name']) && $name_id !== false)
									{
										$cur_row[$name_id] = strtoupper($data[$column_names['manufacturer']])
											. ' ' . $cur_row[$name_id];
									}
									if(isset($name_manuf_fix['sku']))
									{
										$cur_row[$sku_id] = strtoupper($data[$column_names['manufacturer']])
											. ' ' . $cur_row[$sku_id];
									}
								}
								else
								{
									if(isset($name_manuf_fix['name']))
									{
										$cur_row[$name_id] = $cur_row[$name_id] . ' ' . $data[$column_names['manufacturer']];
									}
								}
							}
							if(!strlen($cur_row[$sku_id])
								|| in_array($cur_row[$sku_id], $exclude_skus))
							{
								continue;
							}
							$is_product_exist = Product::isProductExistInShop($cur_row[$sku_id]);
							if(($is_product_exist && $add_update_products == 'add')
									|| (!$is_product_exist && $add_update_products == 'update'))
							{
								$add_update_products_error_skus[] = $cur_row[$sku_id];
								continue;
							}
							foreach($additional_columns as $col_val)
							{
								$cur_row[] = $col_val;
							}
							for($i = 0; $i < count($dyn_additional_columns); $i++)
							{
								switch($dyn_additional_columns[$i])
								{
									case 'url_key':
										$cur_row[] = self::trans(mb_strtolower($cur_row[$sku_id]), '-');
										break;
									case 'meta_keyword':
										$cur_row[] = ($name_prefx_id !== false ? $cur_row[$name_prefx_id] : $attribute_sets[$attribute_set_id])
											. ' ' . $cur_row[$sku_id] . META_KEYWORDS;
										break;
									case 'image':
									case 'small_image':
									case 'thumbnail':
										$cur_row[] = '/' . self::trans(mb_strtolower($cur_row[$sku_id]), '-') . '.jpg';
										break;
									case 'short_description':
										$cur_row[] = mb_substr($cur_row[$desc_id], 0, 100);
										break;
									case 'qty':
										if($cur_row[$availability_status_id] == $STOCKS['in_stock'])
										{
											$cur_row[] = DEFAULT_QTY;
										}
										else
										{
											$cur_row[] = '0';
										}
										break;
									case 'low_stock_date':
										if($cur_row[$availability_status_id] == $STOCKS['in_stock'])
										{
											$cur_row[] = '';
										}
										else
										{
											$cur_row[] = $low_stock_date;
										}
										break;
									case 'is_in_stock':
										if($cur_row[$availability_status_id] == $STOCKS['in_stock'])
										{
											$cur_row[] = '1';
										}
										else
										{
											$cur_row[] = '0';
										}
										break;
								}
							}
							fputcsv($file_to_write, $cur_row, ',', '"');
							$row++;
							if($row%$rows_per_file == 0)
							{
								fclose($file_to_write);
								$file_to_write = fopen($dir_name . '/'  . basename($dir_name)
									. '_' . ($row/$rows_per_file + 1) . '.csv', 'w');
								fputcsv($file_to_write, $all_column_names, ',', '"');
							}
						}
						if(count($add_update_products_error_skus))
						{
							if($add_update_products == 'update')
							{
								echo NO_GOODS_MESSAGE;
							}
							else if($add_update_products == 'add')
							{
								echo GOODS_ARE_IN_DB;
							}
							echo implode('<br/>', $add_update_products_error_skus);
						}
						fclose($file_to_write);
						self::removeTempFiles();
						exec('cd ' . dirname($dir_name) . " && zip -r $dir_name.zip "
							. basename($dir_name) . " && rm -rf $dir_name");
						$archive_name = basename($dir_name) . '.zip';
					}
				}
				else
				{
					throw new Exception('Cant create temporary dir.');
				}
				fclose($fp);
			}
			else
			{
				throw new Exception('Cant get access to ' . $file_path);
			}
		}
		else
		{
			throw new Exception('Empty file.');
		}
		if(strlen($tmp_file_name))
		{
			unlink($tmp_file_name);
		}
		return $archive_name;
	}
	private static function removeTempFiles()
	{
		exec('find ' . PROJECT_HOME . '/dl/ -type f -iname \'*.zip\' -cmin +30 -exec rm -f {} \;');
	}
	public static function getShopProdsNeedForUpdateLink()
	{
		$res = '';
		$db = &DB::singleton();
		$products = $db->getNumArray('select sku, margin, ceil(prov_price+margin), '
			. 'prov_price, price_markup, prov_stock from shop_products_update_temp');
		$products_count = count($products);
		if($products_count)
		{
			$file_name = PROJECT_HOME . '/dl/shop-prods-need-for-update_' . date('d.m.y-H.i.s') . '.csv';
			$file_to_write = fopen($file_name, 'w');
			$columns = array('Model', 'Маржа', 'Price', 'Price S.', 'Наценка', 'Avalibility');
			fputcsv($file_to_write, $columns, ',', '"');
			for($i = 0; $i < $products_count; $i++)
			{
				fputcsv($file_to_write, $products[$i], ',', '"');
			}
			fclose($file_to_write);
			self::removeTempFiles();
			$zip_name = basename(substr($file_name, 0, strlen($file_name) - 4) . '.zip');
			exec('cd ' . PROJECT_HOME . '/dl/ && zip -r ' . $zip_name . ' '
				. basename($file_name) . ' && rm -f ' . $file_name);
			$res =  '/dl/' . $zip_name;
		}
		return $res;
	}
	public static function trans($str, $space_char = '_')
	{
		global $trans_chars;
		$res = '';
		$str_len = mb_strlen($str);
		for($i = 0; $i < $str_len; $i++)
		{
			$res .= $trans_chars['ru'][mb_substr($str, $i, 1)];
		}
		return $res;
	}
}

