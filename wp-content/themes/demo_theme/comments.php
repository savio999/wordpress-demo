<div id="comments" class="clearfix">
    <!-- Comments List
    ============================================= -->
    <ol class="commentlist clearfix">
	    <?php 
	    	if ($comments) {
	    ?>
	    	<h3 id="comments-title">Comments</h3>

	    <?php
	     	foreach($comments as $comment) {
	   	?>
                <li class="comment even thread-even depth-1">
                    <div class="comment-wrap clearfix">
                        <div class="comment-meta">
                            <div class="comment-author vcard">
								<span class="comment-avatar clearfix"><?php echo get_avatar($comment, 60); ?></span>			
                            </div>
                        </div>
                        <div class="comment-content clearfix">
                            <div class="comment-author"><?php echo comment_author() ?><span><a href="#" title="Permalink to this comment"><?php echo comment_date() ?></a></span></div>
                            	<p><?php echo comment_text()?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </li>

	<?php
	    	}
	    } 
	?>
    </ol><!-- .commentlist end -->
    <?php the_comments_pagination(); ?>
</div>

 <?php
	if (post_password_required()) {
		return;
	}
 ?>
                            <!-- Comment Form
                            ============================================= -->
                            <div id="respond" class="clearfix">
                            <?php 
                            comment_form(
								array(
									'comment_form' => '<div class="col_full">
							                                        <label for="comment" id="comment_box">' . __('Comment', 'demo_theme') .'</label>
							                                        <textarea name="comment" cols="58" rows="7" tabindex="4" class="sm-form-control"></textarea>
							                                    </div>',
							        'fields' => array(
							            'author' => '<div class="col_one_third">
							                                        <label for="author">'. __('Name', 'demo_theme') .'</label>
							                                        <input type="text" name="author" id="author" value="" size="22" tabindex="1" class="sm-form-control" />
							                                    </div>',
							            'email' => '<div class="col_one_third">
							                                        <label for="email">'. __('Email', 'demo_theme') .'</label>
							                                        <input type="text" name="email" id="email" value="" size="22" tabindex="2" class="sm-form-control" />
							                                    </div>',
							           'url'    => '<div class="col_one_third col_last">
							                                        <label for="url">'. __('Website', 'demo_theme') .'</label>
							                                        <input type="text" name="url" id="url" value="" size="22" tabindex="3" class="sm-form-control" />
							                                    </div>'

									),
									'class_submit' => 'button button-3d nomargin',
									'label_submit' => __('Submit Comment', 'demo_theme'),
									'title_reply'  => __('Leave a <span>Comment</span>', 'demo_theme')
							    )
							);

							?>

                            </div><!-- #respond end -->

                        </div><!-- #comments end -->