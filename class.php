<?php

function generate_github_data()
{
	global $config;
	$obj = json_decode(file_get_contents("https://api.github.com/users/".$config['github']."/repos"));

	$output = "";
	foreach($obj as $repo)
	{
		$output .= $repo->name."__".$repo->forks."__".$repo->watchers."\n";
	}
	$fri = fopen("data/".$config['cache'], "w");
	fwrite($fri, $output);
	fclose($fri);
}

function increase_count($repo, $type)
{
	if($type == "get")
	{
		$file = "data/tally/get/".$repo.".txt";
		if(file_exists($file))
		{
			$getCount = file_get_contents($file);
			$getCount++;
			$igc = fopen($file, "w");
			fwrite($igc, $getCount);
			fclose($igc);
		}
		else
		{
			$igc = fopen($file, "w");
			fwrite($igc, "1");
			fclose($igc);
			chmod($file, 0777);
		}
	}
	else if($type == "tip")
	{
		$file = "data/tally/tip/".$repo.".txt";
		if(file_exists($file))
		{
			$getCount = file_get_contents($file);
			$getCount++;
			$itc = fopen($file, "w");
			fwrite($itc, $getCount);
			fclose($itc);
		}
		else
		{
			$itc = fopen($file, "w");
			fwrite($itc, "1");
			fclose($itc);
			chmod($file, 0777);
		}
	}
}

function get_count($repo, $type)
{
	$file = "data/tally/".$type."/".$repo.".txt";
	return (file_exists($file) ? file_get_contents($file) : "0");
}
?>