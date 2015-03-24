	<div class="container">
		<div class="row">
			<div class="col-xs-10">
				<h2><?php echo $email;?> has been invited!</h2>
				<p>Below is the unique link that they must use to register or the member won't be added to your account.</p>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="well well-sm"><?php echo BASE_URL;?>users/invite/<?php echo base64_encode($email);?>/<?php echo sha1($_SESSION['user']['companyid'].$email);?></div>
			</div>		
		</div>
	</div>