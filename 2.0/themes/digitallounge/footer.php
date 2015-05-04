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
		</nav><!-- #site-navigation -->
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'digitallounge' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'digitallounge' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'digitallounge' ), 'Digital Lounge', '<a href="http://annenbergdl.org/" rel="designer">The Creative Media Team</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
