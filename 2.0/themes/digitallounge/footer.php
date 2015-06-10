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
			<ul>
			<li class="facebook"><a href="#" alt="facebook"></a></li>
			<li class="instagram"><a href="#" alt="instagram"></a></li>
			<li class="twitter"><a href="#" alt="twitter"></a></li>
			<li class="youtube"><a href="#" alt="youtube"></a></li>
			</ul> 
			<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu' ) ); ?>

		</nav><!-- #site-navigation -->
		<div class="site-info">
			<?php echo get_theme_mod( 'footer_text', '' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
