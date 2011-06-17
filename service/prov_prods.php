<!DOCTYPE html
 PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			function loadGrid(){
				resizeGridContainer();
				var columns = [
					{
						title: "Поставщик",
						width: "15%",
						prog_name: "prov_name",
						filter_type: "text"
					},
					{
						title: "Наименование",
						width: "65%",
						prog_name: "prod_name",
						filter_type: "text"
					},
					{
						title: "Цена",
						width: "5%",
						prog_name: "price",
						filter_type: "float"
					},
					{
						title: "Наличие",
						width: "5%",
						prog_name: "stock",
						filter_type: "int"
					},
					{
						title: "Последнее обновление",
						width: "10%",
						prog_name: "last_update",
						filter_type: "text"
					}
				];
				var grid_data = {};
				grid_data["page_size"] = 20;
				grid_data["backend"] = "ajax.php";
				grid_data["backend_args"] = {action: 'get_prov_prods'};
				grid_data["grid_container"] = document.getElementById("grid_container");
				grid_data["columns"] = columns;
				grid_data["sort"] = {"column_name": "prov_name", "order": "asc"};
				var grid = new GridObject(grid_data);
			}
			window.onload = loadGrid;
			function resizeGridContainer()
			{
				document.getElementById("grid_container").style.height = getWindowHeight() - document.getElementById("grid_container").offsetTop + 'px';
			}
			addEvent(window, 'resize', resizeGridContainer);
		</script>
	</head>
	<body scroll="no">
<?php
require_once dirname(__FILE__).'/menu.php';
?>
		<div id="grid_container"> </div>
	</body>
</html>
