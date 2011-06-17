<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
require_once dirname(__FILE__).'/menu.php';
require_once dirname(__FILE__) . "/lib/Product.php";
require_once dirname(__FILE__) . "/lib/Converter.php";
try
{
	if(isset($_POST['convert_the_the_file']) && ((int)$_POST['attribute_set_id']) > 0 && $_FILES["file_to_convert"]["error"] == UPLOAD_ERR_OK)
	{
		$archive_name = Converter::convertForImport(
			(int)$_POST['attribute_set_id'],
			$_FILES["file_to_convert"],
			(int)$_POST['rows_per_file'],
			implode(",", $_POST['category_ids']),
			$_POST['add_update_products'],
			isset($_POST['out_of_stock']),
			isset($_POST['add_images']),
			strlen($_FILES["exclude_sku_list"]['tmp_name']) ? $_FILES["exclude_sku_list"] : null,
			$_POST['import_language'],
			isset($_POST['attribytes_by_groups']),
			(array)$_POST['manuf_fix']
			);
		echo '<h2><a href="/dl/'.$archive_name.'">Готово.</a></h2>';
	}
}
catch(Exception $e)
{
	die($e->getMessage());
}
$attribute_sets = Product::getAttributeSets();
$categories = Product::getCategories();
?>
<form action="" method="post" enctype="multipart/form-data">
<table border="0" style="margin: 0 auto;">
	<tbody>
		<tr>
			<td>
				<label for="attribute_set_id">Тип:&nbsp;</label>
			</td>
			<td>
				<select name="attribute_set_id" id="attribute_set_id">
				<option selected="selected" value="dummy"></option>
				<?php
				foreach($attribute_sets as $attribute_set_id => $attribute_set_name)
				{
					echo '<option value="' . $attribute_set_id . '">' . $attribute_set_name . '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="category_ids">Категории:&nbsp;</label>
			</td>
			<td>
				<select name="category_ids[]" id="category_ids" multiple="multiple">
				<option selected="selected" value="dummy"></option>
				<?php
				foreach($categories as $category_id => $category_name)
				{
					echo '<option value="' . $category_id . '">' . $category_name . '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="rows_per_file">Записей в 1 файле:&nbsp;</label>
			</td>
			<td>
				<input type="text" name="rows_per_file" value="50" size="3" id="rows_per_file">
			</td>
		</tr>
		<tr>
			<td>
				<label for="add_products">Добавление:&nbsp;</label>
			</td>
			<td>
				<input type="radio" name="add_update_products" id="add_products" value="add">
			</td>
		</tr>
		<tr>
			<td>
				<label for="update_products">Обновление:&nbsp;</label>
			</td>
			<td>
				<input type="radio" name="add_update_products" id="update_products" value="update" checked="checked">
			</td>
		</tr>
		<tr>
			<td>
				<label for="add_images">Картинки:&nbsp;</label>
			</td>
			<td>
				<input type="checkbox" name="add_images" id="add_images">
			</td>
		</tr>
		<tr>
			<td>
				<label for="out_of_stock">Нет в наличии:&nbsp;</label>
			</td>
			<td>
				<input type="checkbox" name="out_of_stock" id="out_of_stock">
			</td>
		</tr>
		<tr>
			<td>
				<label for="attribytes_by_groups">Аттрибуты по группам:&nbsp;</label>
			</td>
			<td>
				<input type="checkbox" name="attribytes_by_groups" id="attribytes_by_groups">
			</td>
		</tr>
		<tr>
			<td>
				<label for="manuf_fix[name]">Наименование fix:&nbsp;</label>
			</td>
			<td>
				<input type="checkbox" name="manuf_fix[name]" id="manuf_fix[name]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="manuf_fix[sku]">SKU fix:&nbsp;</label>
			</td>
			<td>
				<input type="checkbox" name="manuf_fix[sku]" id="manuf_fix[sku]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="import_language">Язык импорта:&nbsp;</label>
			</td>
			<td>
				<select name="import_language" id="import_language">
				<?php
				$langs = array_keys($_);
				for($i = 0; $i < count($langs); $i++)
				{
					echo '<option value="' . $langs[$i] . '"' . ($i == 0 ? ' selected="selected"' : '') . '>' . $langs[$i] . '</option>';
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="file_to_convert">Файл:&nbsp;</label>
			</td>
			<td>
				<input type="file" name="file_to_convert" id="file_to_convert">
			</td>
		</tr>
		<tr>
			<td>
				<label for="exclude_sku_list">Список SKU<br/>для исключения:&nbsp;</label>
			</td>
			<td>
				<input type="file" name="exclude_sku_list" id="exclude_sku_list">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" name="convert_the_the_file" value="Конвертировать">
			</td>
		</tr>
	</tbody>
</table>
</form>
</body>
</html>
