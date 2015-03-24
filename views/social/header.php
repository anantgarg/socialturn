<div class="container">
	<div class="row">
		<div class="col-sm-3">
			<div class="list-group">
			  	<?php foreach ($accounts as $account):?>

				<a class="list-group-item <?php if (!empty($account['current'])):?>active<?php endif;?>" href="<?php echo BASE_URL;?>social/queue/<?php echo $account['id'];?>"><img src="<?php echo $account['image'];?>" class="nav-image"><i class="<?php echo $account['type'];?> fa fa-<?php echo $account['type'];?>-square"></i>&nbsp; <?php echo $account['name'];?></a>

				<?php endforeach;?>
			</div>

	 

		</div>
		<div class="col-sm-9">
				<div class="row">
					<div class="col-xs-12">
						<ul class="nav nav-tabs" role="tablist">
						  <li <?php if ($action == 'queue'):?>class="active"<?php endif;?>><a href="<?php echo BASE_URL;?>social/queue/<?php echo $current;?>">Queue</a></li>
						  <li <?php if ($action == 'manual'):?>class="active"<?php endif;?>><a href="<?php echo BASE_URL;?>social/manual/<?php echo $current;?>">Share</a></li>
					  <li <?php if ($action == 'suggestions'):?>class="active"<?php endif;?>><a href="<?php echo BASE_URL;?>social/suggestions/<?php echo $current;?>">Suggestions</a></li>
						  <li <?php if ($action == 'schedule'):?>class="active"<?php endif;?>><a href="<?php echo BASE_URL;?>social/schedule/<?php echo $current;?>">Schedule</a></li>
<!--					  <li <?php if ($action == 'sent'):?>class="active"<?php endif;?>><a href="<?php echo BASE_URL;?>social/sent/<?php echo $current;?>">Sent</a></li> -->
						</ul>
					
					</div>
				</div>
