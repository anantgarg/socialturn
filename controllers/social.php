<?php

function index() {
	header("Location: ".BASE_URL."social/manual");
	exit;
}

function pre() {
	global $template;
	global $dbh;
	global $path;

	$current = 0;
	
	if (!empty($path[2])) {
		$current = intval($path[2]);
	}

	if ($_SESSION['user']['type'] != 1) {
		$query = $dbh->prepare("select accounts.* from accounts join users_accounts on accounts.id = users_accounts.accountid where users_accounts.companyid = ? and users_accounts.userid = ? and accounts.active = 1");
		$query->execute(array($_SESSION['user']['companyid'],$_SESSION['user']['loggedin']));
		$accounts = $query->fetchAll();
	} else {
		$query = $dbh->prepare("select * from accounts where companyid = ? and accounts.active = 1");
		$query->execute(array($_SESSION['user']['companyid']));
		$accounts = $query->fetchAll();
	}

	$currentpermission = 0;

	foreach ($accounts as $no => $account) {
		
		// If no account selected, redirect to first account
		if (empty($current)) {
			header("Location: ".BASE_URL."social/queue/".$account['id']);
			exit;
		}

		if ($account['type'] == 'facebook') {
			$image = 'https://graph.facebook.com/'.$account['data1'].'/picture';
			$accounts[$no]['image'] = $image;
		}

		if ($account['type'] == 'twitter') {
			$image = $account['data4'];
			$accounts[$no]['image'] = $image;
		}

		if ($current == $account['id']) {
			$accounts[$no]['current'] = 1;
			$currentpermission = 1;
		}
	}

	// If no account found, give a friendly error message
	if (empty($current)) {
		if ($_SESSION['user']['type'] == 1) {
			header("Location: ".BASE_URL."connect");
			exit;
		} else {
			header("Location: ".BASE_URL."oops/no-accounts");
			exit;
		}
	}

	// If no permission
	if (empty($currentpermission)) {
		header("Location: ".BASE_URL."oops/permissions");
		exit;
	}

	$template->set('current',$current);
	$template->set('accounts',$accounts);

}

function requeue($accountId) {

	global $dbh;

	$query = $dbh->prepare("select timezone from accounts_schedule where accountid = ? order by record_created desc limit 1");
	$query->execute(array($accountId));
	$timezone = $query->fetch();

	if (!empty($timezone['timezone'])) {
		$timezone = $timezone['timezone'];
	} else {
		$timezone = 0;
	}

	$query = $dbh->prepare("select * from accounts_schedule where accountid = ?");
	$query->execute(array($accountId));
	$schedule = $query->fetchAll();

	if (!empty($schedule)) {

		$days = array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

		$postTiming = array();

		foreach ($schedule as $s) {
			$day = strtotime($days[$s['day']].' this week');
			$day += ($s['time']*36);

			$day -= ($timezone*3600);

			if ($day >= time()) {
				$postTiming[] = $day;
			}
		}

		$postTimingWeek2 = array();

		foreach ($schedule as $s) {
			$day = strtotime($days[$s['day']].' next week');
			$day += ($s['time']*36);

			$day -= ($timezone*3600);

			if ($day >= time()) {
				$postTimingWeek2[] = $day;
			}
		}

		$timing = array();
		$week = 0;

		for ($i = 0;$i<100;$i++) {
			if (!empty($postTiming)) {
				$timing[] = array_shift($postTiming);
			} else {
				$timing[] = current($postTimingWeek2)+$week;
				next($postTimingWeek2);
				if (current($postTimingWeek2) == FALSE) {
					reset($postTimingWeek2);
					$week += 604800;
				}
			}
		}

		$query = $dbh->prepare("select accounts_queue.id from accounts_queue where accounts_queue.accountid = ? and scheduled_time <> 1 and (sent_time is null or sent_time = '') order by id asc limit 100");
		$query->execute(array($accountId));
		$queue = $query->fetchAll();

		if (!empty($queue)) {
		
			$query = "update accounts_queue set scheduled_time = case ";

			foreach ($queue as $post) {
				$query .= "when id = ".$post['id']." then ".array_shift($timing)." ";
				$ids[] = $post['id'];
			}

			$query .= "end where id IN (".implode(",",$ids).")";
			$query = $dbh->prepare($query);
			$query->execute();

		}

	}

}

function queue() {
	global $dbh;
	global $template;
	global $path;

	$accountId = intval($path[2]);

	$query = $dbh->prepare("select timezone from accounts_schedule where companyid = ? and accountid = ? order by record_created desc limit 1");
	$query->execute(array($_SESSION['user']['companyid'],$accountId));
	$timezone = $query->fetch();

	if (!empty($timezone['timezone'])) {
		$timezone = $timezone['timezone'];
	} else {
		$timezone = 0;
	}

	$query = $dbh->prepare("select accounts_queue.*, users.email from accounts_queue join users on accounts_queue.userid = users.id where accounts_queue.companyid = ? and accounts_queue.accountid = ?  and ((scheduled_time <> 1 and (sent_time is null or sent_time = '')) or (error <> '')) order by id asc");
	$query->execute(array($_SESSION['user']['companyid'],$accountId));
	$queue = $query->fetchAll();

	$template->set('timezone',$timezone);
	$template->set('posts',$queue);
}

function queueremove() {
	global $dbh;
	global $template;
	global $path;
	
	$accountId = intval($path[2]);

	if (!empty($path[3])) {
		$postId = intval($path[3]);		
	} else {
		$_SESSION['notification']['type'] = 'error';
		$_SESSION['notification']['message'] = '<b>Oops!</b> Something went wrong.';
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}

	$query = $dbh->prepare("delete from accounts_queue where companyid = ? and accountid = ? and id = ? limit 1");
	$query->execute(array($_SESSION['user']['companyid'],$accountId, $postId));
	requeue($accountId);

	$_SESSION['notification']['type'] = 'success';
	$_SESSION['notification']['message'] = 'We have removed the post from the queue.';
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;


}

function queueresend() {
	global $dbh;
	global $template;
	global $path;
	
	$accountId = intval($path[2]);

	if (!empty($path[3])) {
		$postId = intval($path[3]);		
	} else {
		$_SESSION['notification']['type'] = 'error';
		$_SESSION['notification']['message'] = '<b>Oops!</b> Something went wrong.';
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}

	$query = $dbh->prepare("update accounts_queue set error = '', scheduled_time = 0, sent_time = 0 where companyid = ? and accountid = ? and id = ? limit 1");
	$query->execute(array($_SESSION['user']['companyid'],$accountId, $postId));
	requeue($accountId);

	$_SESSION['notification']['type'] = 'success';
	$_SESSION['notification']['message'] = 'We have rescheduled the post. If you receive the error again, be sure to reconnect your account.';
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;


}


function queuenow() {
	global $dbh;
	global $template;
	global $path;
	
	$accountId = intval($path[2]);

	if (!empty($path[3])) {
		$postId = intval($path[3]);		
	} else {
		$_SESSION['notification']['type'] = 'error';
		$_SESSION['notification']['message'] = '<b>Oops!</b> Something went wrong.';
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}

	$query = $dbh->prepare("update accounts_queue set scheduled_time = 1 where companyid = ? and accountid = ? and id = ? limit 1");
	$query->execute(array($_SESSION['user']['companyid'],$accountId, $postId));
	requeue($accountId);

	$_SESSION['notification']['type'] = 'success';
	$_SESSION['notification']['message'] = 'Your post is on its way!';
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;


}

function manual(){ 

}

function suggestions() {
	global $dbh;
	global $template;
	global $path;
	global $suggestionLists;
	
	$accountId = intval($path[2]);

	$currentSuggestion = 'all';

	if (!empty($path[3]) && in_array($path[3],$suggestionLists)) {
		$currentSuggestion = $path[3];
		$query = $dbh->prepare("select * from suggestions where id not in (select suggestion_id from accounts_queue where accountid = ?) and list = ? order by id desc limit 25");	
		$query->execute(array($accountId,$currentSuggestion));
	} else {
		$query = $dbh->prepare("select * from suggestions where id not in (select suggestion_id from accounts_queue where accountid = ?) order by id desc limit 25");
		$query->execute(array($accountId));
	}

	$suggestions = $query->fetchAll();
	
	$template->set('suggestions',$suggestions);
	$template->set('list',$suggestionLists);
	$template->set('currentSuggestion',$currentSuggestion);

}

function schedule() {
	global $dbh;
	global $template;
	global $path;

	$accountId = intval($path[2]);

	$query = $dbh->prepare("select timezone from accounts_schedule where companyid = ? and accountid = ? order by record_created desc limit 1");
	$query->execute(array($_SESSION['user']['companyid'],$accountId));
	$timezone = $query->fetch();

	if (!empty($timezone['timezone'])) {
		$timezone = $timezone['timezone'];
	} else {
		$timezone = 0;
	}

	$query = $dbh->prepare("select distinct day from accounts_schedule where companyid = ? and accountid = ?");
	$query->execute(array($_SESSION['user']['companyid'],$accountId));
	$d = $query->fetchAll();

	$days = array();

	foreach ($d as $day) {
		$days[$day['day']] = 1;
	}

	$query = $dbh->prepare("select distinct time from accounts_schedule where companyid = ? and accountid = ?");
	$query->execute(array($_SESSION['user']['companyid'],$accountId));
	$t = $query->fetchAll();

 	$times = array();

	foreach ($t as $time) {
		if (strlen($time['time']) == 3) {
			$time['time'] = '0'.$time['time'];
		}

		$times[$time['time']] = 1;
	}


	$template->set('timezone',$timezone);
	$template->set('days',$days);
	$template->set('times',$times);

}

function scheduleupdate() {
	
	global $dbh;
	global $template;
	global $path;

	// $current is already verified in pre()
	$accountId = intval($path[2]);

	$query = $dbh->prepare("delete from accounts_schedule where companyid = ? and accountid = ?");
	$query->execute(array($_SESSION['user']['companyid'],$accountId));

	if (!empty($_POST['days'])) {
		foreach ($_POST['days'] as $day) {
			if (!empty($_POST['time'])) {
				foreach ($_POST['time'] as $time) {

					$query = $dbh->prepare("insert into accounts_schedule (companyid,accountid,userid,timezone,day,time,record_created) values (?,?,?,?,?,?,?)");
					$query->execute(array($_SESSION['user']['companyid'],$accountId,$_SESSION['user']['loggedin'],$_POST['timezone'],$day,$time,time()));

				}
			}
		}
	}

	requeue($accountId);

	$_SESSION['notification']['type'] = 'success';
	$_SESSION['notification']['message'] = '<strong>Yay!</strong> All future posts will be scheduled accordingly.';
	header("Location: ".BASE_URL."social/schedule/".$accountId);
	exit;
	
}

function sent() {

}

function post() {

	global $dbh;
	global $path;
	// $current is already verified in pre()
	$accountId = intval($path[2]);


	if (empty($_POST['message'])) {
		$_SESSION['notification']['type'] = 'error';
		$_SESSION['notification']['message'] = '<b>Oops!</b> You forgot to post an update. Be careful.';
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}

	$image = '';

	if (!empty($_FILES['image']['tmp_name'])) {
	
		if (isset($_FILES['image']['tmp_name'])) {
			$file = $_FILES['image']['tmp_name'];
			$storagePath = dirname(dirname(__FILE__)).'/images';
			$allowedMimes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif', 'image/pjpeg');

			$fileName = upload($file, $storagePath, $allowedMimes);
			if (!$fileName) {
				$_SESSION['notification']['type'] = 'error';
				$_SESSION['notification']['message'] = '<b>Oops!</b> Your image is not valid. Please double check.';
				header("Location: ".$_SERVER['HTTP_REFERER']);
				exit;
			} else {
				$image = BASE_URL.'images/'.$fileName;
			}
		}

	}

	
	if ($_SESSION['user']['type'] != 1) {
		$query = $dbh->prepare("select accounts.* from accounts join users_accounts on accounts.id = users_accounts.accountid where users_accounts.companyid = ? and users_accounts.userid = ?");
		$query->execute(array($_SESSION['user']['companyid'],$_SESSION['user']['loggedin']));
		$access = $query->fetchAll();
	} else {
		$query = $dbh->prepare("select * from accounts where companyid = ?");
		$query->execute(array($_SESSION['user']['companyid']));
		$access = $query->fetchAll();
	}

	foreach ($access as $account) {
		$present[] = $account['id'];
		$accountInfo[$account['id']] = $account;
	}

	if (empty($_POST['accounts'])) {
		$_POST['accounts'] = array($accountId);
	}

	if (!empty($_POST['media'])) {
		$image = $_POST['media'];
	}


	if (!empty($_POST['accounts'])) {
		foreach ($_POST['accounts'] as $account) {
			if (in_array($account,$present)) {

					$_SESSION['notification']['type'] = 'success';

					if ($_POST['when'] == 'now') {
						$_POST['when'] = 1;
						$_SESSION['notification']['message'] = '<b>Yay!</b> Your update will be posted within a few minutes!';
					} else if ($_POST['when'] == 'queue') {
						$_POST['when'] = 0;				
						$_SESSION['notification']['message'] = '<b>Yay!</b> Your update is queued and will be posted as per your schedule.';
					}

					if (!empty($_POST['screen_name'])) {
						if (strlen($_POST['message'].' via @'.$_POST['screen_name']) <= 140) {
							$_POST['message'] = $_POST['message'].' via @'.$_POST['screen_name'];
						}
					}

					$sid = 0;

					if (!empty($_POST['suggestion_id'])) {
						$sid = $_POST['suggestion_id'];
					}

					$query = $dbh->prepare("insert into accounts_queue (companyid,accountid,userid,message,image,record_created,scheduled_time,suggestion_id) values (?,?,?,?,?,?,?,?)");
					$query->execute(array($_SESSION['user']['companyid'],$accountInfo[$account]['id'],$_SESSION['user']['loggedin'],$_POST['message'],$image,time(),$_POST['when'],$sid));
					requeue($accountInfo[$account]['id']);

				}
			}
		}

	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
