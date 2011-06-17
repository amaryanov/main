<?php
if(isset($_GET['fname']))
{
	$_GET['fname'] = basename($_GET['fname']);
	@exec('rm ' . dirname(__FILE__) . '/dl/' . $_GET['fname']);
}
?>
