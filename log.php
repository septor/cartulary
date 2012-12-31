<?php
include("config.php");
include("lang/".(!empty($config['language']) ? $config['language'] : "English").".php");

if(!isset($_GET['repo']))
{
	header("location: ./");
}

$repo = $config['github']."/".$_GET['repo'];
$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
?>
<html>
	<head>
		<title><?php echo $config['sitename']; ?></title>
		<link rel='stylesheet' href='style.css' />
		<script src='//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
		<link href="http://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css" />
		<style>
			a { color: #<?php echo str_replace("#", "", $config['linkcolor']); ?>; }
		</style>
	</head>
	<body>
		<div id='wrap'>

			<div id='bar'>
				<b><?php echo $config['sitename']; ?></b><br />
				<div class='tag'><?php echo $config['sitetag']; ?></div>
			</div>

			<div id="content">

				<h2><a href='<?php echo $url; ?>'>&larr;</a> <?php echo LOG_LAN01; ?> <a href='https://github.com/<?php echo $repo; ?>'><?php echo $_GET['repo']; ?></a></h2>
				<?php

				$git = simplexml_load_file("https://github.com/".$repo."/commits/master.atom");
				$arr = array('+ ', '- ', 'm ');


				for($i = 0; $i <= 19; $i++)
				{
					$title = "<a href='".$git->entry[$i]->link['href']."' target='_blank'>".$git->entry[$i]->title."</a>";
					$author = "<a href='".$git->entry[$i]->author->uri."'>".$git->entry[$i]->author->name."</a>";
					$datestamp = date("F jS, Y h:iA", strtotime($git->entry[$i]->updated));
					$files = "";
					$files_affected = 0;

					foreach(explode("\n", strip_tags(trim($git->entry[$i]->content))) as $k => $v)
					{
						if(in_array(substr($v, 0, 2), $arr))
						{
							$files .= "<a style='margin-left: 32px;' href='https://github.com/".$repo."/blob/master/".str_replace($arr, "", $v)."'>".$v."</a><br />\n";
							$files_affected++;
						}
					}

					if($files_affected != 0)
					{
						echo "
						<div class='commit'>
							<b>".$files_affected." ".($files_affected > 1 ? LOG_LAN02 : LOG_LAN03)." ".LOG_LAN04." ".$author."</b>
							<span>".$datestamp."</span>
							<br />
							<div style='margin-left: 10px;'><a style='cursor: hand;' onclick=\"$('.files_".$i."').toggle('fast')\">[+]</a> ".$title."</div>
							<div class='files_".$i." files'>".$files."</div>
						</div>
						";
					}

					unset($title, $author, $datestamp, $files, $files_affected);
				}

				?>

			</div>

			<div id='footer'>
				<div class='categories'>
					<?php

					foreach($categories as $key => $cat)
					{
						echo "<span style='color: #".$colors[$key].";'>".$cat."</span> ";
					}

					?>
				</div>
				<div class='copyright'>
					<?php echo $config['footer']; ?> <a href='#top'>&uarr;</a>
				</div>
			</div>

		</div>
	</body>
</html>