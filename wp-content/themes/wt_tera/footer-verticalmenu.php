<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package  WellThemes
 * @file     footer.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
		</div>
		</div><!-- /inner-wrap -->
	</section><!-- /main -->

	<footer id="footer">
			
		<div class="footer-info">
			<div class="inner-wrap">
				<?php if (wt_get_option( 'wt_footer_text_left' )){ ?> 
					<div class="footer-left">
						<?php if (ICL_LANGUAGE_CODE == 'es') { echo '©2014-2015 Derechos de Autor Owl School LLC'; } else { echo wt_get_option( 'wt_footer_text_left' ); } ?>			
					</div>
				<?php } ?>		
                                <div class="footer-right">
                                    <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                                    <a href="http://www.gedboard.com/es/about/" class="footer-right-item">Acerca de Nosotros</a>
                                    <a href="http://www.gedboard.com/terms-and-conditions/" class="footer-right-item">Legal</a>
				    <a href="http://www.gedboard.com/privacy-policy/" class="footer-right-item">Privacidad</a>
			            <a href="http://www.gedboard.com/es/?feed=sitemap" class="footer-right-item last">Mapa del Sitio</a>
                                    <?php } else { ?>
                                    <a href="http://www.gedboard.com/about/" class="footer-right-item">About</a>
                                    <a href="http://www.gedboard.com/terms-and-conditions/" class="footer-right-item">Terms and Conditions</a>
				    <a href="http://www.gedboard.com/privacy-policy/" class="footer-right-item">Privacy Policy</a>
				    <a href="http://www.gedboard.com/refund-policy/" class="footer-right-item">Refund Policy</a>
			            <a href="http://www.gedboard.com/?feed=sitemap" class="footer-right-item last">Sitemap</a>
                                    <?php } ?>
                                </div>		
			</div>		
		</div>		
	</footer><!-- /footer -->
	
</div><!-- /container -->
<?php wp_footer(); ?>

</body>
</html>