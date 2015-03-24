	<form action="<?php echo BASE_URL;?>users/validate" method="post">
	<div class="container spacer centered">
		<div class="row">
			<div><img src="<?php echo BASE_URL;?>assets/img/logo.png" title="SocialTurn"></div>
			<h2>You've been invited</h2>
			<p>Join the team and share updates, together</p>
		</div>
	
		<div class="spacer">
		  <input id="email_dummy" name="email_dummy" type="text" placeholder="Email" class="form-control" value="<?php echo $email;?>" disabled>
		</div>
		<div class="spacer-sm">
			<input id="password" name="password" type="password" placeholder="Password" class="form-control">
		</div>

		<div class="spacer">
			<input id="code" type="hidden" name="code" value="<?php echo $code;?>">
			<input id="email" type="hidden" name="email" value="<?php echo $email;?>">
			<button name="type" type="submit" value="invite" class="btn btn-primary">Sign up</button>
		</div>


	</div>
	</form>