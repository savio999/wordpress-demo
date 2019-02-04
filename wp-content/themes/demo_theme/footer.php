	<!-- Footer
    ============================================= -->
	<footer id="footer" class="dark">

		<!-- Copyrights
        ============================================= -->
		<div id="copyrights">

			<div class="container clearfix">

				<div class="col_half"><?php echo get_theme_mod('dt_footer_copyright_text') ?>
					<br>
					<div class="copyright-links">
						<?php
							if (get_theme_mod('dt_footer_tos_page')) {
						?>
						<a href="<?php the_permalink(get_theme_mod('dt_footer_tos_page')) ?>">Terms of Use</a>
					<?php } 
						if (get_theme_mod('dt_footer_privacy_page')) {
					?>
						 / <a href="<?php the_permalink(get_theme_mod('dt_footer_privacy_page')) ?>">Privacy Policy</a>
					<?php 
						}
					?>
					</div>
				</div>

				<div class="col_half col_last tright">
					<div class="fright clearfix">
						<?php if (get_theme_mod('dt_facebook_handle')) {
						?>
									<a href="<?php echo get_theme_mod('dt_facebook_handle') ?>" class="social-icon si-small si-borderless si-facebook">
										<i class="icon-facebook"></i>
										<i class="icon-facebook"></i>
									</a>
							<?php
								}
							if (get_theme_mod('dt_twitter_handle')) {	
						?>

								<a href="<?php echo get_theme_mod('dt_twitter_handle') ?>" class="social-icon si-small si-borderless si-twitter">
									<i class="icon-twitter"></i>
									<i class="icon-twitter"></i>
								</a>
						<?php
						 	}
						 ?>

					</div>

					<div class="clear"></div>

					<?php 
						if (get_theme_mod('dt_email_handle')) {
					?>
						  <i class="icon-envelope2"></i> <?php echo get_theme_mod('dt_email_handle') ?> <span class="middot">&middot;</span>
					<?php
						}
						if (get_theme_mod('dt_phone_handle')) {
					?>
					 <i class="icon-headphones"></i> <?php echo get_theme_mod('dt_phone_handle') ?>
					<?php 
						}
					?>
				</div>

			</div>

		</div><!-- #copyrights end -->

	</footer><!-- #footer end -->

</div><!-- #wrapper end -->

<!-- Go To Top
============================================= -->
<div id="gotoTop" class="icon-angle-up"></div>

<?php wp_footer(); ?>

</body>
</html>