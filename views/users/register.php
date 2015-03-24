	<form action="<?php echo BASE_URL;?>users/validate" method="post">
	<div class="container spacer centered">
		<div class="row">
			<div><img src="<?php echo BASE_URL;?>assets/img/logo.png" title="SocialTurn"></div>
			<h2>SocialTurn</h2>
			<p>The easiest way to publish, for teams</p>
		</div>

		<div class="spacer">
		  <input id="name" name="name" type="text" placeholder="Team Name" class="form-control">
		</div>
		<div class="spacer-sm">
		  <input id="email" name="email" type="text" placeholder="Email" class="form-control">
		</div>
		<div class="spacer-sm">
			<input id="password" name="password" type="password" placeholder="Password" class="form-control">
		</div>

		<div class="spacer">
			<button name="type" type="submit" value="register" class="btn btn-border">Sign up</button>
		</div>
		<div class="spacer-sm">
			<a href="<?php echo BASE_URL;?>users/login">Already have an account?</a>
		</div>


	</div>
	</form>