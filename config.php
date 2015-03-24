<?php

// Your DB details

define('SERVERNAME','localhost');
define('SERVERPORT','3306');
define('DBUSERNAME','username');
define('DBPASSWORD','password');
define('DBNAME','dbname');
define('BASE_URL','http://yoursite.com/'); // With trailing slash

// Facebook App

define('FB_APPID','XXXXXXXXXXXXXXXXX');
define('FB_APPSECRET','XXXXXXXXXXXXXXXXX');

// Twitter App

define('TWITTER_APIKEY','XXXXXXXXXXXXXXXXX');
define('TWITTER_APISECRET','XXXXXXXXXXXXXXXXX');

// Postmarkapp (required to send invitations)

define('POSTMARKAPP_APIKEY','XXXXXXXXXXXXXXXXX');

// Twitter App (for suggestions only)

define('TWITTER_USERNAME','XXXXXXXXXXXXXXXXX');
define('TWITTER_TOKEN','XXXXXXXXXXXXXXXXX');
define('TWITTER_SECRET','XXXXXXXXXXXXXXXXX');

// Create your own Twitter lists and use them here

$suggestionLists = array('design','technology','lifehacks'); 


// Any random value

define('SERVER_SALT','XXXXXXXXXXXXXXXXX');