<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
require_once dirname(__FILE__).'/menu.php';
require_once dirname(__FILE__) . "/lib/Product.php";
try
{
	if(isset($_POST['import_relations']) && $_FILES["file_to_import"]["error"] == UPLOAD_ERR_OK)
	{
		Product::importRelations($_FILES["file_to_import"]);
		$min_peices_updated = Product::updateMinPrices();
		echo '<h2>Imported successfully.</h2>';
	}
}
catch(Exception $e)
{
	die($e->getMessage());
}
?>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="file_to_import">
<input type="submit" name="import_relations" value="Импортировать">
</form>
</body>
</html>
