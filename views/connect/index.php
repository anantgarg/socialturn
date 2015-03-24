	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h4>Connect a Social Network</h4>
				<p>Share to many different places with SocialTurn and we make sure your posts look great everywhere. Click the 'Connect' buttons below to begin connecting your account to SocialPost:</p>
			</div>
		</div>

		<div class="row spacer">
			<div class="col-xs-2">
				<div class="row centered">
					<a href="<?php echo BASE_URL;?>connect/twitter">
					<div>
						<span class="fa fa-twitter fa-5x twitter"></i>
					</div>
					<div>
						<p class="twitter">twitter</p>
					</div>
					<div>
						<span class="btn btn-default btn-info">Connect</span>
					</div>
				

					</a>
				</div>
			</div>

			<div class="col-xs-2">
				<div class="row centered">
					<a href="<?php echo BASE_URL;?>connect/facebook">
					<div>
						<span class="fa fa-facebook fa-5x facebook"></i>
					</div>
					<div>
						<p class="facebook">facebook</p>
					</div>
					<div>
						<span class="btn btn-default btn-info">Connect</span>
					</div>


					</a>
				</div>
			</div>

			<div class="col-xs-2" style="opacity:.3" rel="tooltip"  title="Not yet available">
				<div class="row centered">
				<!--	<a href="<?php echo BASE_URL;?>connect/google"> -->
					<div>
						<span class="fa fa-google-plus fa-5x google-plus"></i>
					</div>
					<div>
						<p class="google-plus">google</p>
					</div>
					<div>
						<span class="btn btn-default btn-info">Connect</span>
					</div>

					<!-- </a> -->
				</div>
			</div>

		</div>

	<?php if (!empty($accounts)):?>

		<div class="row spacer">
			<div class="col-xs-12">
				<h4>Connected Networks</h4>
				<p>Re-connect or delete your existing networks</p>
			</div>
		</div>

		<div class="row spacer">
			<?php foreach ($accounts as $account):?>
					
			<div class="col-xs-6 page">
				<div class="row">
					<div class="col-xs-2 centered">
					<img class="social_image" src="<?php echo $account['image'];?>">
					</div>
					<div class="col-xs-10">
						<h5 class="name"><?php echo $account['name'];?> (<?php echo ucwords($account['type']);?>)</h5>
						<span class="category"><a class="confirmLink" href="<?php echo BASE_URL;?>connect/<?php echo $account['type'];?>">Reconnect</a> | <a class="confirmLink" href="<?php echo BASE_URL;?>connect/remove/<?php echo $account['id'];?>">Delete</a></span>
					</div>
				</div>
			</div>

			<?php endforeach;?>
		</div>
	<?php endif;?>

	</div>