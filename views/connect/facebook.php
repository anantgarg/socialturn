	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h2>Choose a Page</h2>
				<p>Connect any of your Facebook pages to add to SocialTurn.</p>
			</div>
		</div>
		<div class="row">

			<?php if (!empty($pages['data'])):?>
			<?php foreach ($pages['data'] as $page):?>
					
			<div class="col-xs-6 page">
				<div class="row">
					<a href="<?php echo BASE_URL;?>connect/add/facebook/<?php echo base64_encode($page['id']);?>/<?php echo base64_encode($page['name']);?>/<?php echo base64_encode($page['access_token']);?>">
					<div class="col-xs-2 centered">
					<img class="social_image" src="https://graph.facebook.com/<?php echo $page['id'];?>/picture">
					</div>
					<div class="col-xs-10">
						<h5 class="name"><?php echo $page['name'];?></h5>
						<span class="category"><?php echo $page['category'];?></span>
					</div>
					</a>
				</div>
			</div>

			<?php endforeach;?>
			<?php else:?>
			<div class="col-xs-12">
				<p>Sorry, we are unable to find any pages associated with your account.</p>
			</div>
			<?php endif;?>

		</div>
	</div>