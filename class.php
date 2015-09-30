<?php

function generate_github_data()
{
	global $config;
	$obj = json_decode(fetchData("https://api.github.com/users/".$config['github']."/repos"));

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

function fetchData($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
?>
