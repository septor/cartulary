<?php
include("config.php");
include("class.php");

if(isset($_GET['file']))
{
	$xml = simplexml_load_file("data/database.xml");

	foreach($xml->category as $category)
	{
		foreach($category->download as $download)
		{
			if($download['github'] == $_GET['file'])
			{
				$file = $config['file_path'].$download['file'];
				if(file_exists($file))
				{
				    header('Content-Type: application/octet-stream');
				    header('Content-Disposition: attachment; filename='.basename($file));
				    readfile($file);
				    increase_count(trim($download['github']), "get");
				}
			}
		}
	}
}
else
{
	header("location: ./");
}

?>