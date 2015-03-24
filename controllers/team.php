<?php

checkPermission(10); // Adminstrators & Owners

function index() {
	global $dbh;
	global $template;
	
	$query = $dbh->prepare("select * from users where active = 1 and companyid = ?");
	$query->execute(array($_SESSION['user']['companyid']));
	$users = $query->fetchAll();

	$template->set('users',$users);
}

function invite() {
	
}

function invited() {
	global $template;
	$email = $_POST['email'];
	$template->set('email',$email);
}

function manage(){
	global $template;
	global $path;
	global $dbh;

	$userId = intval($path[2]);

	$query = $dbh->prepare("select * from users where id = ? and active = 1 and companyid = ? and type <> 1");
	$query->execute(array($userId,$_SESSION['user']['companyid']));
	$user = $query->fetch();

	if (empty($user['id'])) {
		error404();
	}

	$query = $dbh->prepare("select * from accounts where companyid = ?");
	$query->execute(array($_SESSION['user']['companyid']));
	$accounts = $query->fetchAll();

	$template->set('accounts',$accounts);


	$query = $dbh->prepare("select accounts.* from accounts join users_accounts on accounts.id = users_accounts.accountid where users_accounts.companyid = ? and users_accounts.userid = ?");
	$query->execute(array($_SESSION['user']['companyid'],$userId));
	$access = $query->fetchAll();

	$present = array();

	foreach ($access as $account) {
		$present[] = $account['id'];
	}

	$template->set('present',$present);

	$template->set('user',$user);
}

function update() {
	global $template;
	global $path;
	global $dbh;

	// Are we updating the correct user?

	$userId = $_POST['id'];

	$query = $dbh->prepare("select * from users where id = ? and active = 1 and companyid = ? and type <> 1");
	$query->execute(array($userId,$_SESSION['user']['companyid']));
	$user = $query->fetch();

	if (empty($user['id'])) {
		error404();
	}

	if (!empty($_POST['role']) && $_POST['role'] > 1) {
		$sql = "update users set type = ? where id = ? and companyid = ?";
		$query = $dbh->prepare($sql);
		$query->execute(array($_POST['role'],$_POST['id'],$_SESSION['user']['companyid']));
	}

	$query = $dbh->prepare("delete from users_accounts where companyid = ? and userid = ?");
	$query->execute(array($_SESSION['user']['companyid'],$_POST['id']));

	if (!empty($_POST['accounts'])) {
		foreach ($_POST['accounts'] as $account) {
			$query = $dbh->prepare("insert into users_accounts (companyid,userid,accountid) values (?,?,?)");
			$query->execute(array($_SESSION['user']['companyid'],$_POST['id'],$account));
		}
	}

	if ($_POST['type'] == 'delete') {
		$sql = "update users set active = 0 where id = ? and companyid = ?";
		$query = $dbh->prepare($sql);
		$query->execute(array($_POST['id'],$_SESSION['user']['companyid']));

		$_SESSION['notification']['type'] = 'success';
		$_SESSION['notification']['message'] = $user['email'].' has been successfully removed.';
	} else {
		$_SESSION['notification']['type'] = 'success';
		$_SESSION['notification']['message'] = '<strong>Yay!</strong> Permissions have been successfully modified for '.$user['email'];
	}

	header("Location: ".BASE_URL."team");
	exit;
}