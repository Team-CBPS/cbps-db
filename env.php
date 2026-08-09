<?php

function load_env($path = null)
{
	if($path === null)
		$path = __DIR__."/.env";

	if(!file_exists($path))
		return;

	foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line)
	{
		$line = trim($line);
		if($line === "" || $line[0] === "#" || strpos($line, "=") === false)
			continue;

		list($key, $value) = explode("=", $line, 2);
		$key = trim($key);
		$value = trim($value);

		if(strlen($value) >= 2 && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'")))
			$value = substr($value, 1, -1);

		if(getenv($key) === false)
			putenv($key."=".$value);
	}
}

function env($key, $default = null)
{
	$value = getenv($key);
	return $value === false ? $default : $value;
}

load_env();

?>
