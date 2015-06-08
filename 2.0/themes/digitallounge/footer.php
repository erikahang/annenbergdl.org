<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Digital Lounge
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu' ) ); ?>

			<ul>
			<li><a href="#">About Us |</a></li>
			<li><a href="#">Meet The Team |</a></li>
			<li><a href="#">Our Space |</a></li>
			<li><a href="#">ANN301 D, Digital Lounge, Wallis Annenberg Hall, USC|</a></li>
			<li><a href="#">Open Mon - Thur, 9 am - 10PM, Friday 9am - 5pm |</a></li>
			</ul>
		</nav><!-- #site-navigation -->
		<div class="site-info">
			<?php echo get_theme_mod( 'footer_text', '' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
