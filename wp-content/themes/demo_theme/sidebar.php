				<!-- Sidebar
                ============================================= -->
				<div class="sidebar nobottommargin col_last clearfix">
					<div class="sidebar-widgets-wrap">
						<?php 
							if (is_active_sidebar("demo_siebar")) {
								dynamic_sidebar("demo_siebar");
							}
						?>
					</div>

				</div><!-- .sidebar end -->