<?php

function set() {
	ignore_user_abort(true);

	if ( function_exists( 'apache_setenv' ) ) {
		apache_setenv( 'no-gzip', 1 );
	}

	ini_set('zlib.output_compression', 0);

	if (ob_get_level() == 0) {
		ob_start();
	}

	header('Content-encoding: none', true);

	if (!empty($_GET['return']) && $_GET['return'] == 'pixel') {
		returnImage();
	} else {
		returnJSON();
	}
}

function track() {
	set();
	global $dbh;
	global $database;


	$ua = $_SERVER['HTTP_USER_AGENT'];

	$parser = new UAParser;
	$result = $parser->parse($ua);

	// Add entry into track
	 
	

	$_REQUEST['track_time'] = time();
	$_REQUEST['ip'] = $_SERVER['REMOTE_ADDR'];

	sql('track');
	

	// Add entry into user

	sql('user');
	
	exit;
}

function sql($table) {
	
	global $dbh;
	global $database;

	$data = array();

	foreach ($database[$table] as $field => $type) {
		if (!empty($_REQUEST[$field])) {
			$data[$field] = $_REQUEST[$field];
		} else {
			if (!empty($type[1])) {
				returnError("$field is required");
				exit;
			}
		}
	}


	$sql = 'insert into '.$table.' (';

	foreach ($data as $name => $value) {
		$sql .= '`'.$name.'`,';
	}

	$sql = substr($sql,0,-1);

	$sql .= ') values (';
	
	foreach ($data as $name => $value) {
		$sql .= ':'.$name.',';
	}

	$sql = substr($sql,0,-1);

	$sql .= ')';

	$sth = $dbh->prepare($sql);
	$sth->execute($data);
	echo $sql;

}

function get() {
	global $path;
	$client = $path[2];
	$site = $path[3];
	$name = $path[4];

	if ($name == 'ref') {
	
		$sth = $dbh->prepare("select ref from track where client = :client and site = :site order by id asc limit 1");
		$sth->execute($data);
		$ref = $statement->fetch(); 

		echo $ref;
		
	}

	exit;
}

function returnImage() {

	header("Content-type: image/gif");
	header("Content-Length: 42");
	header("Cache-Control: private, no-cache, no-cache=Set-Cookie, proxy-revalidate");
	header("Expires: Wed, 11 Jan 2000 12:59:00 GMT");
	header("Last-Modified: Wed, 11 Jan 2006 12:59:00 GMT");
	header("Pragma: no-cache");
		
	echo sprintf(
		'%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%',
		71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59
	);	
	
	
	ob_flush();
	flush();
	ob_end_flush();
}

function returnJSON() {
	$received = true;
//	print(json_encode($received));
//	ob_flush();
//	flush();
//	ob_end_flush();
}

function returnError($error) {
	echo(json_encode(array("error" => $error)));

}