<?php

include("config.php");

$obj = json_decode(file_get_contents("https://api.github.com/users/".$config['github']."/repos"));

foreach($obj as $repo)
{
	echo $repo->name."<br />";
}

/*
$output = "";
foreach($repos as $repo => $id)
{
	$output .= $repo."__".$obj[$id]->forks."__".$obj[$id]->watchers."\n";
}
$fri = fopen("data/".$config['cache'], "w");
fwrite($fri, $output);
fclose($fri);
*/

?>