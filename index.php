<?php
include("lang/".(!empty($config['language']) ? $config['language'] : "English").".php");
if(!file_exists("data/database.xml")){ die(INDEX_LAN01."<br /><a href='https://github.com/septor/cartulary#setting-up-your-databasexml-file'>".INDEX_LAN02."</a>"); }
include("config.php");
include("class.php");

if(!file_exists("data/".$config['cache']) || time() - filemtime("data/".$config['cache']) > 3600)
{
	if(!empty($config['github']))
	{
		generate_github_data();
	}
}

?>
<html>
	<head>
		<title><?php echo $config['sitename']; ?></title>
		<link rel='stylesheet' href='style.css' />
		<link href="http://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css" />
		<style>
			a { color: #<?php echo str_replace("#", "", $config['linkcolor']); ?>; }
		</style>
	</head>
	<body>
		<a name='top'></a>
		<div id='wrap'>

			<div id='bar'>
				<b><?php echo $config['sitename']; ?></b><br />
				<div class='tag'><?php echo $config['sitetag']; ?></div>
				<?php if(!empty($config['paypalme'])) { ?> <div class='donate'><a href='http://paypal.me/<?php echo $config['paypalme']; ?>/'><?php echo INDEX_LAN08; ?></a></div> <?php } ?>
			</div>

			<div id="content">

				<?php

				$xml = simplexml_load_file("data/database.xml");
				$categories = array();
				$colors = array();
				$gi = explode("\n", file_get_contents("data/".$config['cache']));

				foreach($xml->category as $category)
				{
					array_push($categories, $category['name']);
					array_push($colors, $category['color']);

					$color = $category['color'];
					foreach($category->download as $download)
					{
						if(!empty($download['name']) && !empty($download['info']) && !empty($download['github']) && !empty($download['file']))
						{
							foreach($gi as $data)
							{
								if(substr($data, 0, strlen(trim($download['github']))) == trim($download['github']))
								{
									$datas = explode("__", $data);
								}
							}
							$repo_url = "https://github.com/".$config['github']."/".$download['github'];

							echo "
							<div class='download-block'>
								<div class='category' style='background-color: #".$color.";'></div>
								<b>".$download['name']."</b>
								<span class='commitverlog'>
									".(!empty($download['version']) ? "v".$download['version']."<br />" : "")."
									<a href='log.php?repo=".$download['github']."'>".INDEX_LAN03."</a>, <a href='".$repo_url."/issues'>".INDEX_LAN04."</a>
								</span>
								<br />
								<span>".$download['info']."</span>
								<br /><br />
								<span class='button' style='float:left;'><a href='".$repo_url."'>".INDEX_LAN05."</a></span>
								<span class='count'><a href='".$repo_url."/network'>".number_format($datas[1])."</a></span>

								<span class='button' style='float:right;'><a href='".$repo_url."'>".INDEX_LAN06."</a></span>
								<span class='rcount' style='float:right;'><a href='".$repo_url."/stargazers'>".number_format($datas[2])."</a></span>

								<div style='height: 10px;'></div>

								<span class='button' style='float:left;'><a href='get.php?file=".$download['github']."'>".INDEX_LAN07."</a></span>
								<span class='count'>".number_format(get_count($download['github'], "get"))."</span>";

								if(!empty($download['adfly']))
								{
									echo "
									<span class='button' style='float:right;'><a href='http://adf.ly/".$download['adfly']."'>Ad.Fly</a></span>
									<span class='rcount' style='float:right;'>".number_format(get_count($download['github'], "tip"))."</span>";
								}

							echo "
							</div>
							";
						}
					}
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
