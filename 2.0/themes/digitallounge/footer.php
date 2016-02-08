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
		<ul class="social-buttons">
			<li><a href="http://www.facebook.com/AnnenbergDL/" target="_blank" alt="facebook"><div class="facebook"></div></a></li>
			<li><a href="http://instagram.com/annenbergdl/" target="_blank" alt="instagram"><div class="instagram"></div></a></li>
			<li><a href="http://twitter.com/annenbergdl/" target="_blank" alt="twitter"><div class="twitter"></div></a></li>
			<li><a href="https://www.youtube.com/channel/UCIU3Dhxp5YH5v5NYoGj65pA" target="_blank" alt="youtube"><div class="youtube"></div></a></li>
			<li><a href="http://pinterest.com/annenbergdl/" target="_blank" alt="pinterest"><div class="pinterest"></div></a></li>
		</ul> 
		<nav id="site-navigation" class="main-navigation" role="navigation">
			
			<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu' ) ); ?>

		</nav><!-- #site-navigation -->
		<div class="site-info">
			<?php echo get_theme_mod( 'footer_text', '' ); ?>
		</div><!-- .site-info -->
		<div id="footerbranding"></div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
