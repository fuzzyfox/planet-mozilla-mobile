<?php
	//turn off error reporting
	error_reporting(NULL);
	
	//grab the simple pie agreggator
	require_once('simplepie/simplepie.inc');
	
	//create the planet object
	$planet = new SimplePie('http://planet.mozilla.org/rss20.xml', './cache', 1800);
?>
<!doctype html>
	<html>
		<head>
			<meta charset="utf-8">
			<title>Planet Mozilla Mobile</title>
			<link rel="stylesheet" href="assets/style.css" media="all">
			<link href="favicon.png" rel="shortcut icon" type="image/png">
			<meta content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name="viewport">
			<script src="assets/jquery.js" type="text/javascript"></script>
			<script src="assets/jquery.cookie.js" type="text/javascript"></script>
			<script src="assets/jquery.pmom.js" type="text/javascript"></script>
		</head>
		<body>
			<div id="header">
				<h1>Planet Mozilla</h1>
			</div>
			<div id="content">
				<? if((!isset($_GET['country']))): ?><div id="intro">
					<p>Collected here are the most recent blog posts from all over the Mozilla community. The content here is unfiltered and uncensored, and represents the views of individual community members.</p>
					<p class="close">tap to close notice</p>
				</div><? endif ?>
				<div id="content-inner">
					<? if(!isset($_GET['page'])): foreach($planet->get_items() as $country):if(!isset($_GET['country'])): ?>
					<div class="country" rel="<?=$country->get_id()?>">
						<h3>
							<?php
								$title = $country->get_title();
								$title = explode(':', $title, 2);
								echo $title[1];
							?>
						</h3>
						<p>
							<a href="?country=<?=$country->get_id()?>">
								by <?=(is_object($country->get_author()))?$country->get_author()->get_name():$title[0]?> on <?=$country->get_date()?>
							</a>
						</p>
					</div>
					<? elseif($country->get_id() == $_GET['country']):$found=true ?>
					<div class="country-detail">
						<h3>
							<?php
								$title = $country->get_title();
								$title = explode(':', $title, 2);
								echo $title[1];
							?>
						</h3>
						<?=$country->get_content()?>
						<p>
							<a href="?country=<?=$country->get_id()?>">
								by <?=(is_object($country->get_author()))?$country->get_author()->get_name():$title[0]?> on <?=$country->get_date()?>
							</a>
						</p>
					</div>
					<? endif;endforeach ?>
					<? if((!isset($found))&&(isset($_GET['country']))): ?>
					<div id="notice">
						<p class="error">Oops! It looks like we lost your page O.o</p>
						<p class="close">tap to return to cover</p>
					</div>
					<? endif ?><? elseif('list' == $_GET['page']): ?>
					<div id="notice">
						<p class="error">Sorry, but this feature is not quite ready yet. <br><small>We hope to have it working soon.</small></p>
						<p class="close">tap to return to cover</p>
					</div>
					<? elseif('info' == $_GET['page']): ?>
					<h2>Information</h2>
						<h3>Getting Started</h3>
						<p>Planet Mozilla Mobile is here to make reading your favourite Mozilla blogs easier on your handheld device.</p>
						<p><img src="assets/imgs/controlBar.png" alt="control bar">You will have noticed the control bar at the bottom by now. So lets run through what each button does.</p>
						<p><img src="assets/imgs/back.png" alt="back" class="left">This is the back button. You will see this whenever you are not on the cover (homepage). It will take you back one page (unless you have javascript turned off in which case it will take you to the cover).</p>
						<p><img src="assets/imgs/list.png" alt="list" class="left">This is the list button. It allows you to view the list of all those who are syndicated by Planet Mozilla.</p>
						<p><img src="assets/imgs/info.png" alt="info" class="left">This is the info button. It brings you to this page where you can get any help you need on using Planet Mozilla Mobile.</p>
						<p><img src="assets/imgs/reload.png" alt="reload" class="left">This is the reload button. This will reload all the listed articles on the homepage to ensure that you see the most up-to-date items available from Planet Mozilla.</p>
						<p><img src="assets/imgs/link.png" alt="link" class="left">This is the link button. You will only see this when viewing an article. It allows you to go straight to the original source of the content.</p>
					<? endif ?>
				</div>
			</div>
			<div id="controls">
				<? if(!isset($_GET['page'])): ?><? if(isset($_GET['country'])): ?><a href="./" class="back"><img src="assets/imgs/back.png" alt=back"></a><? else: ?>
				<a href="?page=list" class="list"><img src="assets/imgs/list.png" alt="list"></a><? endif ?>
				<a href="?page=info" class="info"><img src="assets/imgs/info.png" alt="info"></a>
				<? if(isset($_GET['country'])): ?><a href="<?=$country->get_link()?>" class="link"><img src="assets/imgs/link.png" alt="link"></a><? else: ?>
				<a href="./" class="reload"><img src="assets/imgs/reload.png" alt="reload"></a><? endif; else: ?>
				<a href="./" class="back"><img src="assets/imgs/back.png" alt="back"></a>
				<? endif ?>
			</div>
			<div id="footer">
				<p>Created By <a href="http://fuzzyfox.mozhunt.com/">FuzzyFox</a> using <a href="http://planet.mozilla.org/">Planet Mozilla</a> as a basis for the design. <br>Content syndicated from <a href="http://planet.mozilla.org/">Planet Mozilla</a></p>
			</div>
		</body>
	</html>
