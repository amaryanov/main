<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
require_once dirname(__FILE__).'/menu.php';
try
{
require_once dirname(__FILE__) . "/lib/Providers.php";
$providers = &Providers::getProviders();
if(isset($_POST['save']))
{
	$provs_by_names = &Providers::getProvidersIdsByNamesArray();
	if(isset($provs_by_names[$_POST['name']]) && $provs_by_names[$_POST['name']] != $_POST['id'])
	{
		$error_message = 'Поставщик с таким названием уже <a href="provider.php?id=' . $provs_by_names[$_POST['name']] . '">существует</a>';
		$_POST['name'] = $providers[$_POST['id']]['name'];
	}
	if((int)$_POST['id'] > 0)
	{
		Providers::updateProv((int)$_POST['id'], $_POST['name'], $_POST['prog_name'],(int) isset($_POST['use_for_our_prices']));
	}
	else
	{
		$_REQUEST['id'] = Providers::insertProv($_POST['name'], $_POST['prog_name'], (int)isset($_POST['use_for_our_prices']));
	}
}
$providers = &Providers::getProviders();
$provider = array('name' => '', 'prog_name' => '', 'use_for_our_prices' => 0);
echo "<h2>$error_message</h2>";
if(isset($_REQUEST['id']) && isset($providers[$_REQUEST['id']]))
{
	$provider = $providers[$_REQUEST['id']];
	echo '<h2>Редактирование поставщика ' . $provider['name'] . '</h2>';
}
else
{
	echo '<h2>Добавление поставщика</h2>';
}
}
catch(Exception $e)
{
	die($e->getMessage());
}
?>
<form action="" method="post">
<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
<label for="name">Название:</label><input type="text" id="name" name="name" value="<?=$provider['name']?>"/><br/>
<label for_"prog_name">Имя в ропграмме:</label><input type="text" id="prog_name" name="prog_name" value="<?=$provider['prog_name']?>"/><br/>
<label for="use_for_our_prices">Использовать в поиске минимальной цены:</label><input type="checkbox" id="use_for_our_prices" name="use_for_our_prices" <?=($provider['use_for_our_prices'] ? ' checked' : '')?>/><br/>
<input type="submit" name="save" value="Сохранить">
</form>
</body>
</html>
