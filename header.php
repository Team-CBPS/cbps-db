<!DOCTYPE HTML>
<?php
include("common.php");
update_csv();
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="common.js"></script>
		
		<?php
		
		if(isset($_GET["id"]))
		{
			
			$CbpsCsv = explode("\n",file_get_contents("cbpsdb.csv"));	
			foreach($CbpsCsv as &$Entry){
				$csv = explode(",",$Entry);
				if(strcmp($csv[ID],$_GET["id"]) == 0)
				{
					$DEFAULT_ICON0 = $csv[DOWNLOAD_ICON0];
					if(strcmp($csv[DOWNLOAD_ICON0],"None") == 0)
					{
						if($plugins == false)
							$DEFAULT_ICON0 = "/img/app_default.png";
					}
					$GLOBALS['desc'] = htmlspecialchars(file_get_contents(get_readme($csv)),ENT_QUOTES);
					echo('<meta name="title" content="CbpsDB - '.htmlspecialchars($csv[TITLE]).'">');
					echo('<meta name="description" content="'.$GLOBALS['desc'].'">');
					echo('<meta property="og:image" content="'.htmlspecialchars($DEFAULT_ICON0,ENT_QUOTES).'"/>');
					echo('<meta name="twitter:image" content="'.htmlspecialchars($DEFAULT_ICON0,ENT_QUOTES).'"/>');
					echo('<meta property="og:title" content="CbpsDB - '.htmlspecialchars($csv[TITLE]).'">');
					echo('<meta property="og:description" content="'.$GLOBALS['desc'].'">');
					echo('<meta name="keywords" content="Vita,Db,CBPS,CbpsDb,Vitadb,homebrew,henkaku,plugins,VPK,SKPRX,VPK">
					<meta name="robots" content="index, follow">
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					<meta name="language" content="English">
					<meta name="author" content="SilicaAndPina">');
					echo('<title>CbpsDB - '.htmlspecialchars($csv[TITLE]).'</title>');
				}
			}
		}
		else
		{
			echo('<meta name="title" content="CbpsDB - Ultimate source for PS Vita Homebrew apps and Plugins!">
			<meta name="description" content="CbpsDB is the ultimate source for PS Vita Homebrew, Download all the latest PSVita homebrew plugins and apps!">
			<meta name="keywords" content="Vita,Db,CBPS,CbpsDb,Vitadb,homebrew,henkaku,plugins,VPK,SKPRX,VPK">
			<meta name="robots" content="index, follow">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<meta name="language" content="English">
			<meta name="author" content="LiEnby">
			
			<meta property="og:title" content="CbpsDB - Ultimate source for PS Vita Homebrew apps and Plugins!" />
			<meta property="og:description" content="CbpsDB is the ultimate source for PS Vita Homebrew, Download all the latest PSVita homebrew plugins and apps!" />
			<meta property="og:image" content="/img/logo.png"/>
			<title>CbpsDB - Ultimate source for PS Vita Homebrew apps and Plugins!</title>');
		
		}
		
		?>
		

		
		<meta name="viewport" content="width=1000; user-scalable=0;" />
		
	</head>

	<body>
	<div class="header">
			<div class="sitename">
				<a href="/" class="image">
					<img src="img/logo.png" alt="cbpsDB" width="50" height="50">
				</a>
				CbpsDB
			</div>
			
			<div class="sitemap">				
				<div id="apps_page" onclick='open_url("/")' class="sitemap-entry"><a href="/">Apps</a></div>
				<div id="plugins_page" onclick='open_url("/plugins.php")' class="sitemap-entry"><a href="/plugins.php">Plugins</a></div>
				<div id="submit_page" onclick='open_url("/submit.php")' class="sitemap-entry"><a href="/submit.php">Submit</a></div>
			</div>
	</div>


