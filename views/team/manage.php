	<div class="container">
		<div class="row">
			<div class="col-xs-10">
				<h2>Manage - <?php echo $user['email'];?></h2>
				<p>You can manage permissions for each user and their roles.</p>
			</div>
		</div>
	</div>


	<form action="<?php echo BASE_URL;?>team/update" method="post">
	<div class="container">

		<div class="row">
			
			<div class="col-xs-12">
			    <h5>User Role</h5>
				<select class="input-group selectpicker" name="role">
					<option value="10" <?php if ($user['type'] == 10) { echo "selected"; }?>>Administrator</option>
					<option value="100" <?php if ($user['type'] == 100) { echo "selected"; }?>>Team Member</option>
				  </select>
			</div>

			<div class="col-xs-6 spacer-sm">
				<h5>User can add updates to which channels?</h5>
				
				<select class="image-picker" multiple="multiple" name="accounts[]">
				<?php foreach ($accounts as $account):?>
				<?php if ($account['type'] == 'facebook'):?>
				<option data-img-src="https://graph.facebook.com/<?php echo $account['data1'];?>/picture" data-img-label="<?php echo $account['name'];?>" data-img-type="<?php echo $account['type'];?>" value="<?php echo $account['id'];?>" <?php if (in_array($account['id'],$present)) { echo "selected"; } ?>><?php echo $account['name'];?> (<?php echo $account['type'];?>)</option>
				<?php endif;?>

				<?php if ($account['type'] == 'twitter'):?>
				<option data-img-src="<?php echo $account['data4'];?>" data-img-label="<?php echo $account['name'];?>" data-img-type="<?php echo $account['type'];?>" value="<?php echo $account['id'];?>" <?php if (in_array($account['id'],$present)) { echo "selected"; } ?>><?php echo $account['name'];?> (<?php echo $account['type'];?>)</option>
				<?php endif;?>

				<?php endforeach;?>
				</select>
			</div>

			<div class="col-xs-12 spacer-sm">
				<input type="hidden" name="id" value="<?php echo $user['id'];?>">
				<button name="type" type="submit" value="update" class="btn btn-primary">Update</button>
			</div>

			<?php if ($_SESSION['user']['loggedin'] != $user['id']):?>
			<div class="col-xs-12 spacer-sm">
				<button name="type" type="submit" value="delete" class="btn btn-danger confirm">Delete User</button>
			</div>
			<?php endif;?>

			
		</div>
	</div>
	</form>