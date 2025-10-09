<?php
include("common.php");
include("dbparser.php");

if(isset($_GET["id"])) {
	if(check_entry_exists($_GET["id"]))
	{
		echo("True");
	}
	else
	{
		echo("False");
	}
}
?>