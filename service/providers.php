<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
require_once dirname(__FILE__).'/menu.php';
?>
<table border>
<tr>
	<th>
		Название
	</th>
	<th>
		Дата последнего обновления
	</th>
	<th>
		Удалить
	</th>
	<th>
		Товары
	</th>
	<th>
		Использовать в поиске минимальной цены
	</th>
<tr>
<?php
require_once dirname(__FILE__) . "/lib/Providers.php";
if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'delete':
			Providers::removeProvider((int)$_REQUEST['id']);
			break;
	}
}
$providers = Providers::getProvidersAndCounters();
foreach($providers as $id => $provider)
{
	echo '<tr>
		<td>
			<a href="provider.php?id=' . $provider['id'] . '">' . $provider['name'] . '
		</td>
		<td>
			' . $provider['last_update'] . '
		</td>
		<td>
			<a href="providers.php?action=delete&id=' . $provider['id'] . '" onclick="return confirm(\'Вы уверены?\');" style="color:red">Удалить</a>
		</td>
		<td>
			' . ( (int)$provider['prods_count'] > 0 ? '<a href="prov_prods.php?id=' . $provider['id'] . '">Просмотреть(' . (int)$provider['prods_count'] . ')' : 'Нет товаров') . '</a>
		</td>
		<td>
			' . ($provider['use_for_our_prices'] ? 'Да' : 'Нет') . '
		</td>
	</tr>';
}
?>
</table>
</body>
</html>
