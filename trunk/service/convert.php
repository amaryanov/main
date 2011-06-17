<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
require_once dirname(__FILE__).'/menu.php';
require_once dirname(__FILE__) . "/lib/Providers.php";
$providers = &Providers::getProviders();
?>
<form action="import.php" method="post" enctype="multipart/form-data">
<input type="checkbox" name="preview" id="preview" onclick="this.form.setAttribute('target', (this.checked ? '_blank' : ''));"><label for="preview">Предпросмотр</label>
<select name="prov_id">
<option value='dummy' selected></option>
<?php
foreach($providers as $id => $value)
{
	if(in_array($value['prog_name'], $implemented_convertors))
	{
		echo '<option value="' . $id . '">' . $value['name'] . '</option>';
	}
}
?>
</select>
	<input type="file" name="file_to_convert">
	<input type="submit" name="convert_pricelist">
</form>
</body>
</html>
