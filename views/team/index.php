	<div class="container">
		<div class="row">
			<div class="col-xs-10">
				<h2>Manage your team</h2>
				<p>Assign privileges to team members and manage them or <a href="<?php echo BASE_URL;?>team/invite">invite a new member</a></p>
			</div>
			<div class="col-xs-2 spacer">
				<a href="<?php echo BASE_URL;?>team/invite"><span class="btn btn-border">New Team Member</span></a>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row spacer">

			<?php foreach ($users as $user):?>
					
			<div class="col-xs-6 page">
				<div class="row">
					<div class="col-xs-2 centered">
					<img class="social_image" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($user['email'])));?>?d=mm&s=50">
					</div>
					<div class="col-xs-10">
						<h5 class="name"><?php echo $user['email'];?></h5>
						<span class="category"><?php if ($user['type'] == 1):?>Owner<?php elseif ($user['type'] == 10):?>Administrator<?php else:?>Team Member<?php endif;?><?php if ($user['type'] <> 1):?> | <a href="<?php echo BASE_URL;?>team/manage/<?php echo $user['id'];?>">Manage</a><?php endif;?></span>
					</div>
				</div>
			</div>

			<?php endforeach;?>

		</div>
	</div>