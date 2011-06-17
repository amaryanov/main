<?php
require_once dirname(__FILE__) . "/lib/Providers.php";
?>
<script src="json.js"></script>
<style type="text/css">
/* ================================================================ 
This copyright notice must be untouched at all times.

The original version of this stylesheet and the associated (x)html
is available at http://www.cssplay.co.uk/menus/final_drop.html
Copyright (c) 2005-2008 Stu Nicholls. All rights reserved.
This stylesheet and the associated (x)html may be modified in any 
way to fit your requirements.
=================================================================== */
*
{
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	font-family:helvetica, arial, verdana, sans-serif;
}
.prov_prods
{
	width: 200px;
}
body{
	width: 100%
	font-family:helvetica, arial, verdana, sans-serif;
	font-size: 12px;
}
.menu {margin: 0 auto; width:447px; height:32px; position:relative; z-index:100;border-right:1px solid #000; font-family:helvetica, arial, verdana, sans-serif;}
/* hack to correct IE5.5 faulty box model */
* html .menu {width:448px; w\idth:447px;}
/* remove all the bullets, borders and padding from the default list styling */
.menu ul {padding:0;margin:0;list-style-type:none;}
.menu ul ul {width:149px;}
/* float the list to make it horizontal and a relative positon so that you can control the dropdown menu positon */
.menu li {float:left;width:149px;position:relative;}
/* style the links for the top level */
.menu a, .menu a:visited {display:block;font-size:12px;text-decoration:none; color:black; width:138px; height:30px; border:1px solid #000; border-width:1px 0 1px 1px; background:white; padding-left:10px; line-height:29px; font-weight:normal; text-align: center;}
/* a hack so that IE5.5 faulty box model is corrected */
* html .menu a, * html .menu a:visited {width:149px; w\idth:138px;}

/* style the second level background */
.menu ul ul a.drop, .menu ul ul a.drop:visited {background:white no-repeat 130px center; text-align: left;}
/* style the second level hover */
.menu ul ul a.drop:hover {background:#b7d186 no-repeat 130px center;}
.menu ul ul :hover > a.drop {background:#b7d186 no-repeat 130px center;}
/* style the third level background */
.menu ul ul ul a, .menu ul ul ul a:visited {background:white}
/* style the third level hover */
.menu ul ul ul a:hover {background:#b7d186;}

/* style arrow text */
.menu ul ul a.drop div.arrow, .menu ul ul a.drop:visited div.arrow{position: absolute; top: 3px; right: 3px; vertical-align: bottom; font-family: sans-serif}

/* hide the sub levels and give them a positon absolute so that they take up no room */
.menu ul ul {visibility:hidden;position:absolute;height:0;top:31px;left:0; width:149px;border-top:1px solid #000;}
/* another hack for IE5.5 */
* html .menu ul ul {top:30px;t\op:31px;}

/* position the third level flyout menu */
.menu ul ul ul{left:149px; top:-1px; width:149px;}

/* position the third level flyout menu for a left flyout */
.menu ul ul ul.left {left:-149px;}

/* style the table so that it takes no ppart in the layout - required for IE to work */
.menu table {position:absolute; top:0; left:0; border-collapse:collapse;;}

/* style the second level links */
.menu ul ul a, .menu ul ul a:visited {background:white; color:#000; height:auto; line-height:1em; padding:5px 10px; width:128px;border-width:0 1px 1px 1px; text-align: left;}
/* yet another hack for IE5.5 */
* html .menu ul ul a, * html .menu ul ul a:visited {width:150px;w\idth:128px;}

/* style the top level hover */
.menu a:hover, .menu ul ul a:hover{color:#000; background:#b7d186;}
.menu :hover > a, .menu ul ul :hover > a {color:#000; background:#b7d186;}

/* make the second level visible when hover on first level list OR link */
.menu ul li:hover ul,
.menu ul a:hover ul{visibility:visible; }
/* keep the third level hidden when you hover on first level list OR link */
.menu ul :hover ul ul{visibility:hidden;}
/* make the third level visible when you hover over second level list OR link */
.menu ul :hover ul :hover ul{ visibility:visible;}

</style>
<script>
function updateMinPrices()
{
	sendPost(
		"ajax.php",
		{action: 'update_min_prices'},
		function(xhr){
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				alert(JSON.parse(xhr.responseText));
			}
		});
}
</script>

<div class="menu">

<ul>
<li><a href="/">Домой</a></li>
<li><a href="list_products.php">Продукты<!--[if IE 7]><!--></a><!--<![endif]-->

<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
      <li><a href="web_update.php">Изменения цен</a></li>
      <li><a href="Update://" onclick="updateMinPrices();return false;">Обновить минимальные цены</a></li>
      <li><a href="import_relations.php">Импорт соответствий</a></li>
      <li><a href="convert_for_import.php">Конвертировать для импорта</a></li>
	</ul>

<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>

<li><a href="providers.php">Поставщики<!--[if IE 7]><!--></a><!--<![endif]-->

<!--[if lte IE 6]><table><tr><td><![endif]-->
	<ul>
	<li><a href="convert.php">Импорт прайса</a></li>
	<li><a class="drop" href="providers.php">Поставщики
<?php
$providers = &Providers::getProviders();
if(count($providers))
{
	echo '<div class="arrow">&raquo;</div><!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>';
	foreach($providers as $id => $provider)
	{
		echo '<li><a href="provider.php?id=' . $id . '">' . $provider['name'] . '</a></li>';
	}
	echo '</ul>

<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
}
?>
	</li>
	<li><a href="prov_prods.php">Товары поставщиков</a></li>
      <li><a href="provider.php">Добавить поставщика</a></li>

	</ul>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>
</ul>

</div>
