<?php

function login() {
	global $template;

	$template->set('noextra','1');
	$template->set('heading','login');
	$template->set('title','Login');
}

function validate() {
	global $dbh;

	$type = $_POST['type'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$code = '';
	
	if (!empty($_POST['code'])) {
		$code = $_POST['code'];
	}

	if (empty($_POST['email']) || empty($_POST['password'])) {
			$_SESSION['notification']['type'] = 'error';
			$_SESSION['notification']['message'] = '<strong>Oops!</strong> Looks like you missed some details.';
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
		}

	if ($type == 'login') {

		$query = $dbh->prepare("select * from users where email = ? and password = ? and active = 1");
		$query->execute(array($email,hashPassword($password)));
		$account = $query->fetch();

		if (!empty($account['id'])) {
			$_SESSION['user']['loggedin'] = $account['id'];
			$_SESSION['user']['email'] = $account['email'];
			$_SESSION['user']['companyid'] = $account['companyid'];
			$_SESSION['user']['type'] = $account['type'];

			header("Location: ".BASE_URL);
			exit;
		} else {
			$_SESSION['notification']['type'] = 'error';
			$_SESSION['notification']['message'] = '<strong>Oops!</strong> Looks like your login information is incorrect.';
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
		}

		
	} else if ($type == 'register') {

		if (!empty($_POST['name'])) {
			$name = $_POST['name'];
		} else {
			$_SESSION['notification']['type'] = 'error';
			$_SESSION['notification']['message'] = 'Looks like you forgot to enter your team\'s name.';
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
		}

		$query = $dbh->prepare("select * from users where email = ?");
		$query->execute(array($email));
		$account = $query->fetch();
		
		if (!empty($account['id'])) {
			$_SESSION['notification']['type'] = 'error';
			$_SESSION['notification']['message'] = 'Looks like you already have an account. Please use our forgot password facility.';
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit;
		}

		$sql = "INSERT INTO companies (name,plan,active) VALUES (?,?,?)";
		$query = $dbh->prepare($sql);
		$query->execute(array($name,1,1));
		$companyId = $dbh->lastInsertId();

		$sql = "INSERT INTO users (companyid,email,password,active,type) VALUES (?,?,?,?,?)";
		$query = $dbh->prepare($sql);
		$query->execute(array($companyId,$email,hashPassword($password),1,1));

		$_SESSION['user']['loggedin'] = $dbh->lastInsertId();
		$_SESSION['user']['email'] = $email;
		$_SESSION['user']['companyid'] = $companyId;
		$_SESSION['user']['type'] = 1;


		header("Location: ".BASE_URL);
		exit;

	} else if ($type == 'invite') {

		$query = $dbh->prepare("select * from users where email = ?");
		$query->execute(array($email));
		$account = $query->fetch();
		
		if (!empty($account['id'])) {
			$_SESSION['notification']['type'] = 'error';
			$_SESSION['notification']['message'] = 'Looks like you already have an account. We currently support only 1 team per email, sorry!';
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}

		$query = $dbh->prepare("select * from companies where sha1(concat(id,?)) = ?");
		$query->execute(array($email,$code));
		$company = $query->fetch();
		
		if (empty($company['id'])) {
			$_SESSION['notification']['type'] = 'error';
			$_SESSION['notification']['message'] = 'Looks like something has gone wrong. Please ask your team member to invite you again.';
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}

		$companyId = $company['id'];

		$sql = "INSERT INTO users (companyid,email,password,active,type) VALUES (?,?,?,?,?)";
		$query = $dbh->prepare($sql);
		$query->execute(array($companyId,$email,hashPassword($password),1,100));

		$_SESSION['user']['loggedin'] = $dbh->lastInsertId();
		$_SESSION['user']['email'] = $email;
		$_SESSION['user']['companyid'] = $companyId;
		$_SESSION['user']['type'] = 100;


		header("Location: ".BASE_URL);
		exit;

	}
	
}

function register() {
	global $template;

	$template->set('noextra','1');

}

function logout() {
	session_destroy();
	header("Location: ".BASE_URL);
	exit;
}

function invite() {
	global $template;
	global $path;

	$email = base64_decode($path[2]);
	$code = $path[3];

	if (empty($code) || empty($email)) {
		error404();
	}

	$template->set('code',$code);
	$template->set('email',$email);
	$template->set('noextra','1');
	$template->set('title',"You've been invited!");
}

function inform() {
	global $template;
	global $path;

	// EMAIL

	$template->set('noextra','1');

}