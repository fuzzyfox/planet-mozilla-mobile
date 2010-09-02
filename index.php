<?php
	
	//deal with error reporting
	error_reporting(NULL);
	
	//get the simplepie library
	require_once('simplepie/simplepie.inc');
	
	//create the new object for the feed
	$planet = new SimplePie('http://planet.mozilla.org/rss20.xml', './cache', 1800);
	
	/* handle and check that urls are safe */
	
		/** @param string $countryCode the article link to create unique id for
		 * @return string an 8 char hash segment for use as a unique id for each article
		 */
		function countryCode($countryCode)
		{
			//take url for article, hash it, return first 8 chars of it (this should be enough to avoid collisions)
			return substr(sha1($countryCode), 0, 8);
		}
		
		$countryCode = (preg_match("/[0-9a-zA-Z]+/", $_GET['countryCode']))?$_GET['countryCode']:null;
		
?>
<!doctype>
	<html>
		<head>
			<!-- meta content -->
			<meta charset="utf-8">
			<title>Planet Mozilla Mobile</title>
			
			<!-- styles -->
			<link rel="stylesheet" href="assets/style.css" type="text/css" media="all">
			<link rel="shortcut icon" href="favicon.png" type="image/png">
			
			<!-- scripts -->
			<script type="text/javascript" src="assets/jquery.js"></script>
			<script type="text/javascript" src="assets/planet-mozilla-mobile.js"></script>
			
			<!-- mobile device detection/settings -->
			<meta content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name="viewport">
		</head>
		<body>
			<div id="header">
				<h1>Planet Mozilla</h1>
			</div>
			<div id="content">
				
				<!-- introduction block -->
				<div id="disclaimer">
					<p>
						Collected here are the most recent blog posts from all
						over the Mozilla community. The content here is unfiltered
						and uncensored, and represents the views of individual
						community members.
					</p>
					<p class="close">
						tap to close notice
					</p>
				</div>
				
				<?php
					foreach($planet->get_items() as $country):
						if($countryCode == null):
				?>
				<!-- homepage content -->
					<div class="country" rel="<?=countryCode($country->get_link())?>">
						<h3>
							<?php
								$countryName = $country->get_title();
								$countryName = explode(':', $countryName, 2);
								echo $countryName[1];
							?>
						</h3>
						<p>
							<a href="?countryCode=<?=countryCode($country->get_link())?>">
								by <?=(is_object($country->get_author()))?$country->get_author()->get_name():$countryName[0]?> on <?=$country->get_date()?>
							</a>
						</p>
					</div>
				<?php
						elseif($countryCode == countryCode($country->get_link())):
							$countryExists = true;
				?>
				<!-- country content -->
				<div id="country">
					<h3>
						<?php
							$countryName = $country->get_title();
							$countryName = explode(':', $countryName, 2);
							echo $countryName[1];
						?>
					</h3>
					<?=$country->get_content()?>
					<p>
						<a href="<?=$country->get_link()?>">
							by <?=(is_object($country->get_author()))?$country->get_author()->get_name():$countryName[0]?> on <?=$country->get_date()?>
						</a>
					</p>
				</div>
				<?php
							break;
						endif;
					endforeach;
					if($countryCode == 'info'):
				?>
				<h2>Information</h2>
					<h3>Getting Started</h3>
					<p>Planet Mozilla Mobile is here to make reading your favourite Mozilla blogs easier on your handheld device.</p>
					<p><img src="assets/imgs/controlBar.png" alt="control bar">You will have noticed the control bar at the bottom by now. So lets run through what each button does.</p>
					<p><img src="assets/imgs/back.png" alt="back" class="left">This is the back button. You will see this whenever you are not on the cover (homepage). It will take you back one page (unless you have javascript turned off in which case it will take you to the cover).</p>
					<p><img src="assets/imgs/list.png" alt="list" class="left">This is the list button. It allows you to view the list of all those who are syndicated by Planet Mozilla.</p>
					<p><img src="assets/imgs/info.png" alt="info" class="left">This is the info button. It brings you to this page where you can get any help you need on using Planet Mozilla Mobile.</p>
					<p><img src="assets/imgs/reload.png" alt="reload" class="left">This is the reload button. This will reload all the listed articles on the homepage to ensure that you see the most up-to-date items available from Planet Mozilla.</p>
					<p><img src="assets/imgs/link.png" alt="link" class="left">This is the link button. You will only see this when viewing an article. It allows you to go straight to the original source of the content.</p>
				<? elseif($countryCode == 'list'): ?>
				<div class="notice">
					<p class="error">Sorry, but this feature is not quite ready yet. <br><small>We hope to have it working soon.</small></p>
					<p class="close"><a href="./">tap to return to cover</a></p>
				</div>
				<? elseif((!isset($countryExists))&&($countryCode != null)): ?>
				<!-- invalid country code -->
				<div class="notice">
					<p class="error">Oops! It looks like we lost your page O.o</p>
					<p class="close"><a href="./">tap to return to cover</a></p>
				</div>
				<? endif; ?>
				
			</div>
			
			<div id="controls">
				<? if(isset($countryExists)): ?>
				<a href="./" class="back"><img src="assets/imgs/back.png" alt="back"></a>
				<a href="?countryCode=info" class="info"><img src="assets/imgs/info.png" alt="info"></a>
				<a href="<?=$country->get_link()?>" class="link"><img src="assets/imgs/link.png" alt="link"></a>
				<? elseif(preg_match("/(list|info)/i", $countryCode)): ?>
				<a href="./" class="back"><img src="assets/imgs/back.png" alt="back"></a>
				<? else: ?>
				<a href="?countryCode=list" class="list"><img src="assets/imgs/list.png" alt="list"></a>
				<a href="?countryCode=info" class="info"><img src="assets/imgs/info.png" alt="info"></a>
				<a href="./" class="reload"><img src="assets/imgs/reload.png" alt="reload"></a>
				<? endif ?>
			</div>
			<div id="footer">
				<p>Created By <a href="http://fuzzyfox.mozhunt.com/">FuzzyFox</a> using <a href="http://planet.mozilla.org/">Planet Mozilla</a> as a basis for the design. <br>Content syndicated from <a href="http://planet.mozilla.org/">Planet Mozilla</a></p>
			</div>
		</body>
	</html>