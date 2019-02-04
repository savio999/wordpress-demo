<?php $unique_id = esc_attr(uniqid('search-form-')); ?>

<form role="search" method="get" class="search-form" method="<?php esc_attr(home_url('/')) ?>">
	<div class="input-group">
		<input type="search" id="<?= $unique_id ?>" name="s" value="" class="form-control" value="<?php the_search_query() ?>" placeholder="<?php _e('Search', 'demo_theme') ?>"/>
		<span class="input-group-btn">
			<button type="submit" class="btn btn-danger"><i class="icon-search"></i></button>
		</span>
	</div>
</form>