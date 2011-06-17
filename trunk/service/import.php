<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php	if($_POST['preview'])
		{
?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="scroll_grid.css" type="text/css">
		<script src="json.js"></script>
		<script src="grid-object.js"></script>
		<style>
			body
			{
				overflow: hidden;
			}
			.scroll_table .stock
			{
				text-align: right;
			}
			.scroll_table .price
			{
				text-align: right;
			}
		</style>
		<script>
			function loadGrid(){
				resizeGridContainer();
				var columns = [
					{
						title: "Наименование",
						width: "70%",
						prog_name: "prod_name",
						filter_type: "text"
					},
					{
						title: "Цена",
						width: "15%",
						prog_name: "price",
						filter_type: "float"
					},
					{
						title: "Наличие",
						width: "15%",
						prog_name: "stock",
						filter_type: "int"
					}
				];
				var grid_data = {};
				grid_data["page_size"] = 20;
				grid_data["backend"] = "ajax.php";
				grid_data["backend_args"] = {
					action: 'get_temp_prov_prods',
					"filter_compare[prov_id]": 'equal',
					"filter_values[prov_id]": '<?=$_POST['prov_id']?>'};
				grid_data["grid_container"] = document.getElementById("grid_container");
				grid_data["columns"] = columns;
				grid_data["sort"] = {"column_name": "prod_name", "order": "asc"};
				var grid = new GridObject(grid_data);
			}
			window.onload = loadGrid;
			function resizeGridContainer()
			{
				document.getElementById("grid_container").style.height = getWindowHeight() - document.getElementById("grid_container").offsetTop + 'px';
			}
			addEvent(window, 'resize', resizeGridContainer);
		</script>
<?php
	}
?>
</head>
<body scroll="auto">
<?php
require_once dirname(__FILE__).'/menu.php';
require_once dirname(__FILE__) . "/lib/Converter.php";
require_once dirname(__FILE__) . "/lib/Providers.php";
require_once dirname(__FILE__) . "/lib/Product.php";
$providers = Providers::getProviders();
try
{
	if($_POST['convert_pricelist'] && $_POST['prov_id'] > 0 && isset($providers[$_POST['prov_id']]) && strlen($providers[$_POST['prov_id']]['prog_name']) && $_FILES["file_to_convert"]["error"] == UPLOAD_ERR_OK)
	{
		$res = Converter::convert($_POST['prov_id'], $_FILES["file_to_convert"]);
		if($res)
		{
			if($_POST['preview'])
			{
				echo '<h2>Обновление цен от "' . $providers[$_POST['prov_id']]['name'] . '"</h2><form action ="" method="post"><input type="hidden" name="prov_id" value="' . $_POST['prov_id'] . '"><input type="checkbox" name="import_correct_pricelist" id="import_correct_pricelist"><label for="import_correct_pricelist"><b>Подтверждаю правильность</b></label><input type="submit" value="Заимпортировать"></form><table border><tbody>';
				//$temp_prods = Product::getTempProds($_POST['prov_id']);
				echo '<div id="grid_container"></div>';
				/*echo '<table border><tbody><tr><td>Наименование</td><td>Цена</td><td>Склад</td></tr>';
				$temp_prods_count = count($temp_prods);
				for($i = 0; $i < $temp_prods_count; $i++)
				{
					echo '<tr><td>' . $temp_prods[$i]['prod_name'] . '</td><td>' . $temp_prods[$i]['price'] . '</td><td>' . $temp_prods[$i]['stock'] . '</td></tr>';
				}
				echo '</tbody></table>';*/
			}
			else if(Product::importProducts($_POST['prov_id']))
			{
				$min_peices_updated = Product::updateMinPrices();
				echo '<h2>Imported successfully.</h2>';
			}
		}
	}
	else if(isset($_POST['import_correct_pricelist']) && $_POST['prov_id'] > 0 && isset($providers[$_POST['prov_id']]) && strlen($providers[$_POST['prov_id']]['prog_name']) && Product::importProducts($_POST['prov_id']))
	{
		echo '<h2>Обновление цен от "' . $providers[$_POST['prov_id']]['name'] . '"</h2><br><h2>Imported successfully.</h2>';
		$min_peices_updated = Product::updateMinPrices();
	}
}
catch(Exception $e)
{
	die($e->getMessage());
}
?>
</body>
</html>
