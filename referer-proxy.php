<?php
include("common.php");
include("dbparser.php");

if(isset($_GET['url']))
{
	$url = $_GET['url'];
	if(substr( $url, 0, 51 ) === "https://rinnegatamante.eu/vitadb/get_hb_url.php?id=")
	{
		$proxylist = explode("\n",file_get_contents("Proxy-Both.lst"));
		$proxy = $proxylist[array_rand($proxylist)];
		
		
		$opts = [
			"http" => [
				"method" => "GET",
				"proxy" => "tcp://".$proxy,
				'follow_location' => 0,
				"request_fulluri" => true,
				"header" => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\r\n".
					"Accept-Encoding: gzip, deflate, br\r\n".
					"Accept-Language: en-GB,en-US;q=0.9,en;q=0.8\r\n".
					"Sec-Fetch-Dest: document\r\n".
					"Sec-Fetch-Mode: navigate\r\n".
					"Sec-Fetch-Site: same-origin\r\n".
					"Sec-Fetch-User: ?1\r\n".
					"Upgrade-Insecure-Requests: 1\r\n".
					"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36\r\n".
					"Referer: https://rinnegatamante.eu/vitadb/\r\n"
			]
		];

		$context = stream_context_create($opts);
		file_get_contents($url, false, $context);
		$filename = "app.vpk";
		$redirect = "";
		
		if(strcmp($http_response_header[0],"HTTP/1.1 200 OK") !== 0 && strcmp($http_response_header[0],"HTTP/1.1 302 Found") !== 0)
		{
			$CbpsCsv = explode("\n",file_get_contents("cbpsdb.csv"));	
			foreach($CbpsCsv as &$Entry){
				$csv = explode(",",$Entry);
				
				if(strcmp($csv[DOWNLOAD_URL],$url) == 0)
				{
					$arr = get_list($csv[DOWNLOAD_URL_MIRROR]);
					foreach($arr as &$itm)
					{
						if(isAvalible($itm) == true)
						{
							echo("<a href=\"".$itm."\">Redirecting.</a>");
							header("Referrer-Policy: no-referrer"); /*Bypass rin firewall*/
							header("Location: ".$itm);
						}
					}
				}
			}
		}

		
		for($i = 1; $i < sizeof($http_response_header); $i++)
		{
			$head = $http_response_header[$i];
			
			if(strcmp(substr(strtolower($head), 0, 9),"location:") == 0)
			{
				$redirect = substr($head, 10);
				$filename = basename($redirect);
			}			
		}
		
		
		$opts = [
			"http" => [
				"method" => "GET",
				"proxy" => "tcp://".$proxy,
				'follow_location' => 0,
				"request_fulluri" => true,
				"header" => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\r\n".
					"Accept-Encoding: gzip, deflate, br\r\n".
					"Accept-Language: en-GB,en-US;q=0.9,en;q=0.8\r\n".
					"Sec-Fetch-Dest: document\r\n".
					"Sec-Fetch-Mode: navigate\r\n".
					"Sec-Fetch-Site: same-origin\r\n".
					"Sec-Fetch-User: ?1\r\n".
					"Upgrade-Insecure-Requests: 1\r\n".
					"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36\r\n".
					"Referer: https://vitadb.rinnegatamante.it/\r\n"
			]
		];
		
		$context = stream_context_create($opts);
		$file = file_get_contents($redirect, false, $context);
		
		for($i = 1; $i < sizeof($http_response_header); $i++)
		{
			$head = $http_response_header[$i];
			
			if(strcmp(substr(strtolower($head), 0, 15),"content-length:") == 0)
			{
				header($head);		
			}
		}
		
		header('Content-Type: application/octet-stream');
		header('Cache-Control must-revalidate');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('X-Rinnegatamante: You Mad Bro?');
		
		file_put_contents("php://output",$file);
	}

}
?>