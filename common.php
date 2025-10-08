<?php

define("ID",0);
define("TITLE",1);
define("CREDITS",2);
define("DOWNLOAD_ICON0",3);
define("DOWNLOAD_ICON0_MIRROR",4);
define("DOWNLOAD_URL",5);
define("DOWNLOAD_URL_MIRROR",6);
define("DOWNLOAD_README",7);
define("DOWNLOAD_README_MIRROR",8);
define("DOWNLOAD_SRC",9);
define("DOWNLOAD_SRC_MIRROR",10);
define("TIME_ADDED",11);
define("CONFIG_TYPE",12);
define("OPTIONS",13);
define("TYPE",14);
define("DEPENDS",15);
define("VISIBLE",16);

define("ORDER_AS_IS",0);
define("ORDER_NEWEST",1);

function update_csv()
{
	$csvTime = filemtime("cbpsdb.csv");
	if(time() > ($csvTime + 600))
	{
		$csvData = file_get_contents("https://raw.githubusercontent.com/git-username/git-repo/master/cbpsdb.csv");
		if(strcmp($http_response_header[0],"HTTP/1.1 200 OK" == 0))
			file_put_contents("cbpsdb.csv",$csvData);
	}
}


function isAvalible(string $url)
{
	$headers = get_headers($url);
	if((strstr($headers[0],"200 OK") !== False) || (strstr($headers[0],"302 Found") !== False))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_list(string $list){
	$listStr = str_replace("||",".-!-PIPE-!-.",$list);
	$listContents = explode("|",$list);
	for($i = 0; $i >= count($listContents); $i++) {
		$listContents[$i] = str_replace(".-!-PIPE-!-.","|",$listContents[$i]);
	}
	return $listContents;
}


function get_readme(array $entry){
	if(isAvalible($entry[DOWNLOAD_README]))
	{
		return $entry[DOWNLOAD_README];
	}
	else
	{
		$arr = get_list($entry[DOWNLOAD_README_MIRROR]);
		foreach($arr as &$itm)
		{
			if(isAvalible($itm) == true)
			{
				return $itm;
			}
		}
	}
}

?>

