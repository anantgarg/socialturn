	<div class="container">
		<div class="row">
			<div class="col-xs-10">
				<h2>Invite a team member</h2>
				<p>Enter the email address of the team member you would like to invite. All team members will be invited with no privileges.</p>
			</div>
		</div>
	</div>


	<form action="<?php echo BASE_URL;?>team/invited" method="post">
	<div class="container">

		<div class="row">
			
			<div class="col-xs-12">
				<input id="email" name="email" type="text" placeholder="Email" class="form-control">
			</div>

			<div class="col-xs-12 spacer-sm">
				<button name="type" type="submit" value="login" class="btn btn-primary">Invite User</button>
			</div>

			
		</div>
	</div>
	</form>