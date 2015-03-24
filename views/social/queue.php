	<div class="row">
		<div class="col-xs-12">
			<h4>Queue</h4>
			<p>We'll make sure your posts are sent out even when you're asleep! Here is a schedule of all posts that have been queued. All timings are GMT +<?php echo $timezone;?>.</p>
		</div>
	</div>

	<?php foreach ($posts as $post):?>
	<div class="row spacer-sm post">
			<div class="col-xs-10">
				<h5 class="name"><?php if (!empty($post['error'])):?><span class="label label-danger">Failed</span> &nbsp; <?php endif;?><?php echo $post['message'];?></h5>
				<?php if (!empty($post['image'])):?>
				<img class="media" src="<?php echo $post['image'];?>">
				<?php endif;?>
				<?php if (!empty($post['scheduled_time']) && $post['scheduled_time'] <> 1):?><div class="scheduled"><?php echo date("g:ia, F j, Y",$post['scheduled_time']+($timezone*3600));?></div><?php endif;?>
				<div class="category"><br/><a class="confirmLink" href="<?php echo BASE_URL;?>social/queue-now/<?php echo $current;?>/<?php echo $post['id'];?>">Share Now</a> | <a class="confirmLink" href="<?php echo BASE_URL;?>social/queue-remove/<?php echo $current;?>/<?php echo $post['id'];?>">Delete</a> <?php if (!empty($post['error'])):?>| <a class="confirmLink" href="<?php echo BASE_URL;?>social/queue-resend/<?php echo $current;?>/<?php echo $post['id'];?>">Re-queue</a><?php endif;?></div>
			</div>
			<div class="col-xs-2 pull-right">
				<img class="social_image spacer-sm" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($post['email'])));?>?d=mm&s=50" title="<?php echo $post['email'];?>" rel="tooltip">
			</div>
		<div style="clear:both"></div>
	</div>
	<?php endforeach;?>