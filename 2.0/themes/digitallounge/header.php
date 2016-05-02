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
<meta name="viewport" content="width=device-width">
<link rel="profile" href="http://gmpg.org/xfn/11">
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/calendarfunctions.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/buttontoggle.js"></script>
<link href="<?php bloginfo('template_url'); ?>/css/animate.css" rel="stylesheet" type="text/css">

<script>$( document ).ready(function() {
  	$( "#insert_events_calendar" ).load( "<?php echo site_url(); ?>/events/month #tribe-events-content");
});
</script>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="<?php bloginfo('template_url'); ?>/img/favicon/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'digitallounge' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div>
			<a id="calendarhref" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">
			<time id="calendaricon" datetime="2014-09-20" class="icon">
			  <em id="dayofweek"></em>
			  <strong id="month"></strong>
			  <span id="daynumber"></span>
			</time>
			</a>
		</div>
		<div id="eventreadout" class="animated fadeInLeft">
		<?php echo do_shortcode('[tribe_events_list limit="1"]'); ?>
		<!-- <span class="big">1</span>
			<span class="small"> PM </span>
			<span class="pipe">|</span>
			<span class="eventheadline">Photoshop</span>
			<span>,</span>
			<span class="smalleritalic">How to make a poster...</span> -->
		</div>
		<div class="site-branding">
			<h1 class="site-title">
			<a class="animated pulse" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</h1> 
			
		</div><!-- .site-branding -->
		<div id="navbuttons">
			<ul>
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
		<div class="search-string">
			<?php get_search_form(); ?>
		</div>
	</div>
	<div id="calendar-window" class="window window-calendar animated fadeIn">
			<div class="window-title calendar-title">
				<a href='javascript:;' onclick="toggleClose()" class="window-close">X</a>
			<a href="<?php echo site_url(); ?>/events/"><div id="month_event_calendar"></div></a>
			</div>
		<div id="insert_events_calendar"></div> <!-- This is where the AJAX call will go and grab the calendar from ../events/month -->
	</div>

	<div id="content" class="site-content paper-back">
		<section class="home-search-filter-bar paper-front">
			<?php get_search_form(); ?>
			<ul class="filter-bar-menu">
				<li class="tools"><button type="button">Tools</button>
					<ul>
					<?php $tools = get_terms( array(
					    'taxonomy' => 'tool',
					    'hide_empty' => true,
					) ); 
					foreach ( $tools as $tool ) {
						echo '<li><a href="' . get_term_link( $tool->term_id, 'tool' ) . '" class="tool-icon-link"><img src="' . get_term_meta( $tool->term_id, 'tool_icon', true ) . '" class="tool-icon"/></a></li>';
					}
					?></ul>
					<!--<ul><?php wp_list_categories( array( 'taxonomy' => 'tool', 'title_li' => '', 'show_count' => 0, 'orderby' => 'count', 'order' => 'desc' ) ); ?></ul>-->
				</li>
				<li class="tutorial_tags"><button type="button">Collections</button>
					<ul><?php wp_list_categories( array( 'taxonomy' => 'tutorial_tag', 'title_li' => '', 'show_count' => 0, 'orderby' => 'name', 'order' => 'desc' ) ); ?></ul>
				</li>
				<li class="difficulties"><button type="button">Skill Level</button>
					<ul><?php wp_list_categories( array( 'taxonomy' => 'difficulty', 'title_li' => '', 'show_count' => 0, 'orderby' => 'count', 'order' => 'desc' ) ); ?></ul>
				</li>
			</ul>
		</section>
