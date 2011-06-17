<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="scroll_grid.css" type="text/css">
		<script src="json.js"></script>
		<script src="grid-object.js"></script>
		<style>
			body
			{
				overflow: hidden;
			}
		</style>
		<script>
			var grid = null;
			function loadGrid()
			{
				resizeGridContainer();
				var columns = [
					{
						title: "Наименование",
						width: "60%",
						prog_name: "sku",
						filter_type: "text"
					},
					{
						title: "Цена поставщика",
						width: "10%",
						prog_name: "prov_price",
						filter_type: "float"
					},
					{
						title: "Новая цена",
						width: "10%",
						prog_name: "new_price",
						filter_type: "float"
					},
					{
						title: "Маржа",
						width: "10%",
						prog_name: "margin",
						filter_type: "float"
					},
					{
						title: "Наценка",
						width: "10%",
						prog_name: "price_markup",
						filter_type: "float"
					}
				];
				var grid_data = {};
				grid_data["page_size"] = 20;
				grid_data["backend"] = "ajax.php";
				grid_data["backend_args"] = {action: 'get_shop_prods_need_for_update'};
				grid_data["grid_container"] = document.getElementById("grid_container");
				grid_data["columns"] = columns;
				grid_data["sort"] = {"column_name": "sku", "order": "asc"};
				grid = new GridObject(grid_data);
			}
			function reloadData()
			{
				sendPost(
					"ajax.php",
					{action: 'reload_shop_prods_need_for_update'},
					function(xhr){
						if(xhr.readyState == 4 && xhr.status == 200)
						{
							grid.beginLoad();
						}
					});
			}
			function getShopProdsNeedForUpdate()
			{
				sendPost(
					"ajax.php",
					{action: 'get_shop_prods_need_for_update_link'},
					function(xhr){
						if(xhr.readyState == 4 && xhr.status == 200)
						{
							var path = JSON.parse(xhr.responseText);
							if(path.length)
							{
								window.location = path;
							}
							else
							{
								alert('Нет изменений.');
							}
						}
					});
			}
			window.onload = loadGrid;
			function resizeGridContainer()
			{
				document.getElementById("grid_container").style.height = getWindowHeight() - document.getElementById("grid_container").offsetTop + 'px';
			}
			addEvent(window, 'resize', resizeGridContainer);
		</script>
	</head>
	<body scroll="auto">
		<?php
		require_once dirname(__FILE__).'/menu.php';
		?>
		<input type="submit" value="Обновить" onclick="reloadData();" />
		<input type="submit" value="Скачать" onclick="getShopProdsNeedForUpdate();"/>
		<div id="grid_container"> </div>
</body>
</html>
