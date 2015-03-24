<?php

function suggestions() {
	global $dbh;
	global $suggestionLists;

	$query = $dbh->prepare("delete from suggestions");
	$query->execute();
	
	
	$twitter = new TwitterOAuth(TWITTER_APIKEY, TWITTER_APISECRET, TWITTER_TOKEN, TWITTER_SECRET);

	foreach ($suggestionLists as $list) {

		$tweets = $twitter->get('lists/statuses',array('slug'=>$list,'owner_screen_name'=>TWITTER_USERNAME,'count'=>'10'));
		
		foreach ($tweets as $tweet) {
			$id = $tweet->id_str;
			$text = $tweet->text;
			$via = $tweet->user->screen_name;
			
			if (!empty($tweet->entities->media[0]->media_url_https)) {
				$media = $tweet->entities->media[0]->media_url_https;
				$text = preg_replace('/(.*)\shttp:\/\/t\.co(.*?)$/', '$1', $text); // Remove last link
			} else {
				$media = '';
			}

			$r = array();
			$r[0] = $tweet->in_reply_to_status_id;
			$r[1] = $tweet->in_reply_to_status_id_str;
			$r[2] = $tweet->in_reply_to_user_id;
			$r[3] = $tweet->in_reply_to_user_id_str;
			$r[4] = $tweet->in_reply_to_screen_name;

			if (empty($r[0]) && empty($r[1]) && empty($r[2]) && empty($r[3]) && empty($r[4])) {
				$query = $dbh->prepare("insert ignore into suggestions (id,text,screen_name,media,list,record_created) values (?,?,?,?,?,?)");
				$query->execute(array($id,$text,$via,$media,$list,time()));
				
			}
		}

	}

	exit;
}

function post() {
	global $dbh;

	$query = $dbh->prepare("select accounts_queue.*, accounts.type, accounts.data1, accounts.data2, accounts.data3 from accounts_queue join accounts on accounts_queue.accountid = accounts.id where scheduled_time <= ? and (sent_time is null or sent_time = '') and accounts.active = 1 limit 10");
	$query->execute(array(time()));
	$posts = $query->fetchAll();

	foreach ($posts as $post) {

		$error = '';

		if ($post['type'] == 'facebook') {

		try {

			$params = array(
			  "access_token" => $post['data2'],
			  "message" => $post['message']
			);

		    $facebook = new Facebook(array('appId'  => FB_APPID,'secret' => FB_APPSECRET));

			if (!empty($post['image'])) {
				$params['url'] = $post['image'];
			    $ret = $facebook->api('/'.$post['data1'].'/photos', 'POST', $params);
			} else {

				unset($matches);

				preg_match_all('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/]+!',$post['message'],$matches);
				
				$link = '';

				if (!empty($matches[0][0])) {
					$link = $matches[0][0];
					$params['link'] = $link;
				}

				$ret = $facebook->api('/'.$post['data1'].'/feed', 'POST', $params);
			}

		} catch(Exception $e) {
		   $error = print_r($e,true);
		}
		
	  }

		if ($post['type'] == 'twitter') {
			if (empty($post['media'])) {
				$twitter = new TwitterOAuth(TWITTER_APIKEY, TWITTER_APISECRET, $post['data2'], $post['data3']);
				$account = $twitter->get('account/verify_credentials');
				$status = $twitter->post('statuses/update', array('status' => $post['message']));
				
				if (!empty($status->errors[0]->message)) {
					$error = $status->errors[0]->message;
				}

			} else {
				$file = $post['media'];

				$params = array(
					'media[]' => file_get_contents($file)
				);

				if (null !==  $post['message']) {
					$params['status'] = $post['message'];
				}

				$tmhOAuth = new \tmhOAuth(array(
					'user_agent' => 'SocialTurn',
					'consumer_key' => TWITTER_APIKEY,
					'consumer_secret' => TWITTER_APISECRET,
					'token' => $post['data2'],
					'secret' => $post['data3'],
				));

				$response = $tmhOAuth->user_request(array(
					'method' => 'POST',
					'url' => $tmhOAuth->url("1.1/statuses/update_with_media"),
					'params' => $params,
					'multipart' => true
				));

				if ($response == 200 && isset($tmhOAuth->response['response'])){
					$json = json_decode($tmhOAuth->response['response']);
					$lastTweetId = $json->id_str;
				}

				if (isset($tmhOAuth->response['response'])) {
					$json = json_decode($tmhOAuth->response['response']);
					if (isset($json->errors)) {
						$error = array();
						foreach ($json->errors as $err) {
							$error[] = '['.$err->code.'] '.$err->message;
						}

						$error = $err->message;
					}
				}

			}
		}

					
		$update = $dbh->prepare("update accounts_queue set sent_time = ?, error = ? where id = ?");
		$update->execute(array(time(),$error,$post['id']));
 

	}
	
	exit;
}