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
        </div><!-- /inner-wrap -->
    </section><!-- /main -->
</div><!-- /container -->
<style type="text/css">
.footer-menu {
    width: 100%;
    text-align: center;
}
.footer-menu-item {
    padding: 0 10px;
    border-right: 1px solid #aca899;
}
.footer-menu-item.last {
    border-right: 0px;
}
.footer-menu-item-text {
    color: #0782B0;
    font-size: 14px;
}
.footer-copyright {
    color: #bababa;
    font-size: 12px;
    font-family: Verdana,Arial,Helvetica,sans-serif;
    font-weight: bold;
    line-height: 20px;
    width: 100%;
    text-align:center;
}
.footer-disclaimer {
    color: #0782B0;
    font-size: 10px;
    font-family: Verdana,Arial,Helvetica,sans-serif;
    font-weight: bold;
    line-height: 20px;
    width: 100%;
    text-align:center;
    padding-top: 10px;
}

</style>
<footer id="footer" style="background-color:#EFEFEF; width: 100%; padding: 40px 0px;">	
    <div id="container" class="hfeed">
        <div class="footer-info">
            <div class="inner-wrap">
                <div class="footer-menu">
                    <?php if (ICL_LANGUAGE_CODE == 'es') { ?>
                        <a href="http://www.gedboard.com/es/about/" class="footer-menu-item"><span class="footer-menu-item-text">Acerca de Nosotros</span></a>
                        <a href="http://www.gedboard.com/terms-and-conditions/" class="footer-menu-item"><span class="footer-menu-item-text">Legal</span></a>
			<a href="http://www.gedboard.com/privacy-policy/" class="footer-menu-item"><span class="footer-menu-item-text">Privacidad</span></a>
			<a href="http://www.gedboard.com/es/?feed=sitemap" class="footer-menu-item last"><span class="footer-menu-item-text">Mapa del Sitio</span></a>
                    <?php } else { ?>
                        <a href="http://www.gedboard.com/about/" class="footer-menu-item"><span class="footer-menu-item-text">About</span></a>
                        <a href="http://www.gedboard.com/terms-and-conditions/" class="footer-menu-item"><span class="footer-menu-item-text">Terms and Conditions</span></a>
                        <a href="http://www.gedboard.com/privacy-policy/" class="footer-menu-item"><span class="footer-menu-item-text">Privacy Policy</span></a>
                        <a href="http://www.gedboard.com/refund-policy/" class="footer-menu-item"><span class="footer-menu-item-text">Refund Policy</span></a>
                        <a href="http://www.gedboard.com/?feed=sitemap" class="footer-menu-item last"><span class="footer-menu-item-text">Sitemap</span></a>
                    <?php } ?>
                </div>
                <div class="footer-copyright">Open Window Learning, Copyright 2014-2015 OWL School LLC. All Rights Reserved</div>
                <div class="footer-disclaimer">GED® is a registered trademark of the American Council on Education and may not be used without permission. The GED® and GED Testing Service® brands are administered by GED Testing Service LLC under license. This website is not associated with the GED Testing Service®, American Council on Education or Pearson.</div>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>

</body>
</html>