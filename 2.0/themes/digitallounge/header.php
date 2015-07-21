<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Digital Lounge
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/calendarfunctions.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/buttontoggle.js"></script>
<link href="<?php bloginfo('template_url'); ?>/css/animate.css" rel="stylesheet" type="text/css">

<link href="<?php echo esc_url( home_url( '/' ) ); ?>/wp-content/plugins/the-events-calendar/src/resources/css/tribe-events-theme.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo esc_url( home_url( '/' ) ); ?>/wp-content/plugins/the-events-calendar/src/resources/css/tribe-events-full.min.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	$(document).ready(function(){
    $("button").click(function(){
        $("primary").animate({left: '250px'});
    });
});
</script>
<script>$( document ).ready(function() {
  
      $( "#insert_events_calendar" ).load( "/annenbergdl.org/events/ #tribe-events-content" );
});
</script>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'digitallounge' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div>
			<a href='javascript:;' onclick="toggleCalendar()">
			<time id="calendaricon" datetime="2014-09-20" class="icon">
			  <em id="dayofweek"></em>
			  <strong id="month"></strong>
			  <span id="daynumber"></span>
			</time>
			</a>
		</div>
		<div id="eventreadout">
			<span class="big">1</span>
			<span class="small"> PM </span>
			<span class="pipe">|</span>
			<span class="eventheadline">Photoshop</span>
			<span>,</span>
			<span class="smalleritalic">How to make a poster...</span>
		</div>
		<div class="site-branding">
			<h1 class="site-title">
			<a class="animated pulse" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</h1> 
			
		</div><!-- .site-branding -->
		<div id="navbuttons">
			<ul>
			<li><a href="#"><span class="chatwithus">chat with us</span></a></li>
			<li><div id="binoculars"><a href='javascript:;' onclick="toggleSearch()"><img src="<?php bloginfo('template_directory'); ?>/img/binoc.svg" width="50" height="50"></a>
			</div></li>
			<li><div class="usclogo">
				<a href="http://www.usc.edu" alt="usclogo" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/img/usclogo.svg" width="50" height="50"></a>
			</div></li>
			</ul>
		</div>
	</header><!-- #masthead -->
	<div id="search-window" class="window animated fadeIn">
		<div class="window-title search-title">Explore
			<a href='javascript:;' onclick="toggleClose()" class="window-close">X</a>
		</div>
		<div class="search-string">Search
		</div>
	</div>
	<div id="calendar-window" class="window window-calendar animated fadeIn">
		<div class="window-title calendar-title">
		<a href='javascript:;' onclick="toggleClose()" class="window-close">X</a>
		<div id="month_event_calendar"></div>
		</div>
		<div id="insert_events_calendar"></div> <!-- This is where the AJAX call will go and grab the calendar from ../events/month -->
	</div>

	<div id="content" class="site-content paper-back">
