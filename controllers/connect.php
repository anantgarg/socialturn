<?php

checkPermission(1); // Owners Only

function index() {
	global $dbh;
	global $template;

	$query = $dbh->prepare("select * from accounts where companyid = ? and active = 1");
	$query->execute(array($_SESSION['user']['companyid']));
	$accounts = $query->fetchAll();

	foreach ($accounts as $no => $account) {
		
		if ($account['type'] == 'facebook') {
			$image = 'https://graph.facebook.com/'.$account['data1'].'/picture';
			$accounts[$no]['image'] = $image;
		}

		if ($account['type'] == 'twitter') {
			$image = $account['data4'];
			$accounts[$no]['image'] = $image;
		}

	}	

	$template->set('accounts',$accounts);
}

function facebook() {

	global $template;
	
	$facebook = new Facebook(array('appId'  => FB_APPID,'secret' => FB_APPSECRET));	
	$user = $facebook->getUser();

	if ($user) {
	  try {

		$pages = $facebook->api('/me/accounts');
		$template->set('pages',$pages);

		$permissions = $facebook->api("/me/permissions");
	
		$publish_actions = 0;
		$manage_pages = 0;

		foreach ($permissions['data'] as $permission) {
			if ($permission['permission'] == 'publish_actions') {
				$publish_actions = 1;
			}
			if ($permission['permission'] == 'manage_pages') {
				$manage_pages = 1;
			}
		}

		if ($publish_actions == 0 || $manage_pages == 0) {
			throw new Exception("Oops");
		}


	  } catch (Exception $e) {

		$url = $facebook->getLoginUrl(array("scope" => "publish_actions,manage_pages"));
		header("Location: ".$url);
		exit;
	  }

	} else {
		$url = $facebook->getLoginUrl(array("scope" => "publish_actions,manage_pages"));
		header("Location: ".$url);
		exit;
	}
	  
}

function twitter() {
	global $template;
	
	$twitter = new TwitterOAuth(TWITTER_APIKEY, TWITTER_APISECRET);
	$request_token = $twitter->getRequestToken(BASE_URL."connect/twitter-callback");
	
	$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	 
	switch ($twitter->http_code) {
	  case 200:
		/* Build authorize URL and redirect user to Twitter. */
		$url = $twitter->getAuthorizeURL($token);
		header('Location: ' . $url); 
		exit;
	  default:
		$_SESSION['notification']['type'] = 'error';
		$_SESSION['notification']['message'] = 'Unable to connect to Twitter. Please try again later.';
		header("Location: ".BASE_URL."connect");
		exit;
	}

}


function twittercallback() {
	
	$twitter = new TwitterOAuth(TWITTER_APIKEY, TWITTER_APISECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	$token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);

	$account = $twitter->get('account/verify_credentials');

	header('Location: '.BASE_URL.'connect/add/twitter/'.base64_encode($token['user_id']).'/'.base64_encode($token['screen_name']).'/'.base64_encode($token['oauth_token']).'/'.base64_encode($token['oauth_token_secret']).'/'.base64_encode($account->profile_image_url_https)); 
	exit;


}

function add() {
	global $dbh;
	global $path;

	$type = $path[2];

	if ($type == 'facebook') {

		$query = $dbh->prepare("select * from accounts where companyid = ? and type = ? and data1 = ?");
		$query->execute(array($_SESSION['user']['companyid'],'facebook',base64_decode($path[3])));
		$existing = $query->fetch();

		if ($existing['id'] > 0) {
			$sql = "update accounts set active = ?, name = ?, data2 = ? where companyid = ? and type = ? and data1 = ?";
			$query = $dbh->prepare($sql);
			$query->execute(array(1,base64_decode($path[4]),base64_decode($path[5]),$_SESSION['user']['companyid'],'facebook',base64_decode($path[3])));
		} else {
			$sql = "insert into accounts (companyid,type,name,data1,data2,active) VALUES (?,?,?,?,?,?)";
			$query = $dbh->prepare($sql);
			$query->execute(array($_SESSION['user']['companyid'],'facebook',base64_decode($path[4]),base64_decode($path[3]),base64_decode($path[5]),1));
		}
	}

	if ($type == 'twitter') {

		$query = $dbh->prepare("select * from accounts where companyid = ? and type = ? and data1 = ?");
		$query->execute(array($_SESSION['user']['companyid'],'twitter',base64_decode($path[3])));
		$existing = $query->fetch();

		if ($existing['id'] > 0) {
			$sql = "update accounts set active = ?, name = ?, data2 = ?, data3 = ?, data4 = ? where companyid = ? and type = ? and data1 = ?";
			$query = $dbh->prepare($sql);
			$query->execute(array(1,base64_decode($path[4]),base64_decode($path[5]),base64_decode($path[6]),base64_decode($path[7]),$_SESSION['user']['companyid'],'twitter',base64_decode($path[3])));
		} else {

			$sql = "insert into accounts (companyid,type,name,data1,data2,data3,data4,active) VALUES (?,?,?,?,?,?,?,?)";
			$query = $dbh->prepare($sql);
			$query->execute(array($_SESSION['user']['companyid'],'twitter',base64_decode($path[4]),base64_decode($path[3]),base64_decode($path[5]),base64_decode($path[6]),base64_decode($path[7]),1));

		}

	}

	$_SESSION['notification']['type'] = 'success';
	$_SESSION['notification']['message'] = 'Account has been successfully added.';

	header("Location: ".BASE_URL."connect");
	exit;

}

function remove() {
	global $dbh;
	global $path;

	$accountId = intval($path[2]);

	$query = $dbh->prepare("update accounts set active = ? where companyid = ? and id = ?");
	$query->execute(array(0,$_SESSION['user']['companyid'],$accountId));

	$_SESSION['notification']['type'] = 'success';
	$_SESSION['notification']['message'] = 'Account has been successfully removed.';

	header("Location: ".BASE_URL."connect");
	exit;
}