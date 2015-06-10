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
		<ul>
			<li><a href="http://www.facebook.com/AnnenbergDL" target="_blank" alt="facebook"><div class="facebook"></div></a></li>
			<li><a href="http://instagram.com/annenbergdl/" target="_blank" alt="instagram"><div class="instagram"></div></a></li>
			<li><a href="http://twitter.com/annenbergdl" target="_blank" alt="twitter"><div class="twitter"></div></a></li>
			<li><a href="#" alt="youtube" target="_blank"><div class="youtube"></div></a></li>
		</ul> 
		<nav id="site-navigation" class="main-navigation" role="navigation">
			
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
