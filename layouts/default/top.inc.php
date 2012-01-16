<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Think of Something Nice</title>
		<?php echo $Page->get_css(); ?>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Annie+Use+Your+Telescope|Forum|Cabin+Sketch&v2" type="text/css" />		
		<?php echo $Page->get_js(); ?>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
		<?php if ($Session->get('human') == false) { ?>
			<script type="text/javascript">
				var RecaptchaOptions = {
					theme : 'white'
				};
			</script>
		<?php } ?>
		<?php if (!isset($SelectedPost) || !is_object($SelectedPost)) { ?>
			<meta property="og:title" content="Think of Something Nice "/>
			<meta property="og:site_name" content="Think of Something Nice"/>
			<meta property="og:url" content="http://<?php echo $Conf->http_host; ?>"/>
		<?php }
		else { ?>
			<meta property="og:title" content="Think of Something Nice - '<?php echo Util::excerpt_char(Util::html($SelectedPost->somethingnice()), 20); ?>'"/>
			<meta property="og:site_name" content="Think of Something Nice - '<?php echo Util::excerpt_char(Util::html($SelectedPost->somethingnice()), 20); ?>'"/>
			<meta property="og:url" content="http://<?php echo $Conf->http_host; ?>/thing/<?php echo $SelectedPost->id(); ?>"/>
		<?php } ?>
		<meta property="og:type" content="website"/>
		<?php if (!isset($Upload) || !is_object($Upload)) { ?>
			<meta property="og:image" content="http://<?php echo $Conf->http_host; ?>/assets/images/fb.png"/>
		<?php }
		else { ?>
			<meta property="og:image" content="http://<?php echo $Conf->http_host; ?>/assets/uploads/<?php echo $Upload->name(); ?>"/>
		<?php } ?>
		<meta property="fb:admins" content="100001431671704,223305159"/>
		<meta name="description" content="Think of something nice and get something nice in return. The sharing, caring website." />
		<meta name="keywords" content="Think of Something Nice, Think, Something, Nice, altruism, community, sharing, nice things, nice pictures, nice content" />
	</head>

	<body>		
		<div id="fb-root"></div>
		<div id="container" class="<?php echo isset($SelectedPost) ? 'long' : ''; ?>">
			<?php if ($Posts->has_posted($Session) || (isset($CurrentUser) && $CurrentUser != false)) { ?>
				<ul id="toolbar">
					<?php if ($Auth->check_logged_in()) { ?>
						<li><a href="<?php echo $Page->page; ?>?logout=1" title="Logout">Logout</a></li>
					<?php } ?>
					<?php if (!isset($SelectedPost) || !$Posts->is_last($SelectedPost)) { ?>
						<li><a href="/thing/<?php echo $Posts->get_last_id(); ?>" title="Latest">Latest &raquo;</a></li>
						<?php if (isset($SelectedPost)) { ?>
							<li><a href="/thing/<?php echo $Posts->get_next_id($SelectedPost); ?>" title="Next">Next &gt;</li>
						<?php } ?>						
					<?php } ?>
					<li><a href="/thing/random" title="Random">Random</a></li>
					<?php if (!isset($SelectedPost) || !$Posts->is_first($SelectedPost)) { ?>						
						<?php if (isset($SelectedPost)) { ?>					
							<li><a href="/thing/<?php echo $Posts->get_prev_id($SelectedPost); ?>" title="Previous">&lt; Previous</li>
						<?php } ?>
						<li><a href="/thing/<?php echo $Posts->get_first_id(); ?>" title="Earliest">&laquo; Earliest</a></li>
					<?php } ?>
				</ul>
			<?php }
			else { ?>
				<?php /*<em id="toolbar">You must submit something nice to get something nice in return!</em>*/ ?>
			<?php } ?>
			<div id="header">
				<a href="/" title="Think of Something Nice"><img src="/assets/images/logo.png" alt="Think of Something Nice" title="Think of Something Nice" id="logo" /></a>			
			</div>
			<hr/>
			<?php if ($Alert->check_for_alerts()) { ?>
				<div id="alerts">
					<?php echo $Alert->output_alerts(); ?>
				</div>
			<?php } ?>
			<div id="content">
