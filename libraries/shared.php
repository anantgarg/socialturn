<?php

function generateLink($controller,$action) {
	return basePath().'/'.$controller.'/'.$action;
}

function isLoggedIn() {
	if (!empty($_SESSION['user']['loggedin']) && !empty($_SESSION['user']['email']) && !empty($_SESSION['user']['companyid']) && !empty($_SESSION['user']['type'])) {
		return 1;
	}

	return 0;
}

function authenticate($force = 0) {
	global $template;
	global $controller;
	global $action;

	$loggedin = isLoggedIn();

	if ($loggedin == 0 && !($controller == 'users' && ($action == 'login' || $action == 'validate' || $action == 'invite' || $action == 'register')) && !($controller == 'cron')) {
		header("Location: ".BASE_URL."users/login");
		exit;
	}
	
}

function error404() {
	header("Location: ".BASE_URL."oops/not-found");
	exit;
}

function checkPermission($permission) {
	if ($_SESSION['user']['type'] > $permission) {
		header("Location: ".BASE_URL."oops/permissions");
		exit;
	}
}

function hasPermission($permission) {
	if ($_SESSION['user']['type'] > $permission) {
		return false;
	}

	return true;
}


function getLink() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function sanitize($input,$type = "old") {
	
	switch ($type) {
	case "int": 
		$input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
	break;

	case "string": 
		$input = filter_var($input, FILTER_SANITIZE_STRING);
	break;

	case "url": 
		$input = filter_var($input, FILTER_SANITIZE_URL);
	break;

	case "email":
		$input = strtolower(filter_var($input, FILTER_SANITIZE_EMAIL));
	break;

	case "comment":
		$input = htmlentities($input, ENT_QUOTES);
	break;

	case "old":
		echo "Old version of sanitize called";
		exit();
	break;

	}

	return $input;
}


function escape($input) {
	$input = mysql_real_escape_string($input);
	return $input;
}

function createSlug($input) {
	$input = filter_var($input, FILTER_SANITIZE_STRING);
	$input = trim($input);
	$input = preg_replace("/ /","-",$input);
	$input = preg_replace("/[^+A-Za-z0-9\.\-]/", "", $input); 
	$input = preg_replace("/--/","-",$input);
	return strtolower($input);
}

function fetchURL($url) {
  $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect
        CURLOPT_TIMEOUT        => 10,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    return $content;
}

function db() {
	global $dbh;
	try {
	  $dbh = new PDO("mysql:host=".SERVERNAME.";dbname=".DBNAME, DBUSERNAME, DBPASSWORD);
	  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(PDOException $e) {
		echo "We are experiencing very heavy load at the moment. Please try again in 10 minutes.";
		file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
		exit;
	}
}

function datify($date) {
	return date('g:iA M dS', strtotime($date));
}

function datifyunix($date) {
	return date('g:iA M dS', $date);
}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

function hashPassword($password) {
	$password = hash('sha256', $password);
	$salt = hash('sha256', SERVER_SALT);
	$hash = $password.$salt;
	$hash = hash('sha512', $hash );

	return $password;
}


function highlight($c,$q){ 
$q=explode(' ',str_replace(array('','\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','#','-','_'),'',$q));
for($i=0;$i<sizeOf($q);$i++) 
	$c=preg_replace("/($q[$i])(?![^<]*>)/i","<span class=\"highlight\">\${1}</span>",$c);
return $c;}


 function excerpt($text, $phrase, $radius = 100, $ending = "...") { 
       $phraseLen = strlen($phrase); 
       if ($radius < $phraseLen) { 
             $radius = $phraseLen; 
         } 

		 $phrases = explode (' ',$phrase);
		 
		 foreach ($phrases as $phrase) {
			 $pos = strpos(strtolower($text), strtolower($phrase)); 
			 if ($pos > -1) break;
		 }
  
         $startPos = 0; 
         if ($pos > $radius) { 
             $startPos = $pos - $radius; 
         } 
  
         $textLen = strlen($text); 
  
         $endPos = $pos + $phraseLen + $radius; 
         if ($endPos >= $textLen) { 
             $endPos = $textLen; 
         } 
  
         $excerpt = substr($text, $startPos, $endPos - $startPos); 
         if ($startPos != 0) { 
             $excerpt = substr_replace($excerpt, $ending, 0, $phraseLen); 
         } 
  
         if ($endPos != $textLen) { 
             $excerpt = substr_replace($excerpt, $ending, -$phraseLen); 
         } 
  
         return $excerpt; 
   } 

function truncate ($text, $length = 200, $ending = "...") {
	if (strlen($text) <= $length) { 
		return $text; 
	} else { 
		$truncate = substr($text, 0, $length - strlen($ending)).$ending; 
		return $truncate;
	} 
}

function sendemail($fromname,$fromaddress,$toemail,$subject,$body,$tag = null) {

	Mail_Postmark::compose()->from($fromaddress,$fromname)
      ->to($toemail)
      ->subject($subject)
      ->messagePlain($body)
	    ->tag($tag)
      ->send();

}

function getExtensionToMimeTypeMapping() {
    return array(
        'ai'=>'application/postscript',
        'aif'=>'audio/x-aiff',
        'aifc'=>'audio/x-aiff',
        'aiff'=>'audio/x-aiff',
        'anx'=>'application/annodex',
        'asc'=>'text/plain',
        'au'=>'audio/basic',
        'avi'=>'video/x-msvideo',
        'axa'=>'audio/annodex',
        'axv'=>'video/annodex',
        'bcpio'=>'application/x-bcpio',
        'bin'=>'application/octet-stream',
        'bmp'=>'image/bmp',
        'c'=>'text/plain',
        'cc'=>'text/plain',
        'ccad'=>'application/clariscad',
        'cdf'=>'application/x-netcdf',
        'class'=>'application/octet-stream',
        'cpio'=>'application/x-cpio',
        'cpt'=>'application/mac-compactpro',
        'csh'=>'application/x-csh',
        'css'=>'text/css',
        'csv'=>'text/csv',
        'dcr'=>'application/x-director',
        'dir'=>'application/x-director',
        'dms'=>'application/octet-stream',
        'doc'=>'application/msword',
        'drw'=>'application/drafting',
        'dvi'=>'application/x-dvi',
        'dwg'=>'application/acad',
        'dxf'=>'application/dxf',
        'dxr'=>'application/x-director',
        'eps'=>'application/postscript',
        'etx'=>'text/x-setext',
        'exe'=>'application/octet-stream',
        'ez'=>'application/andrew-inset',
        'f'=>'text/plain',
        'f90'=>'text/plain',
        'flac'=>'audio/flac',
        'fli'=>'video/x-fli',
        'flv'=>'video/x-flv',
        'gif'=>'image/gif',
        'gtar'=>'application/x-gtar',
        'gz'=>'application/x-gzip',
        'h'=>'text/plain',
        'hdf'=>'application/x-hdf',
        'hh'=>'text/plain',
        'hqx'=>'application/mac-binhex40',
        'htm'=>'text/html',
        'html'=>'text/html',
        'ice'=>'x-conference/x-cooltalk',
        'ief'=>'image/ief',
        'iges'=>'model/iges',
        'igs'=>'model/iges',
        'ips'=>'application/x-ipscript',
        'ipx'=>'application/x-ipix',
        'jpg'=>'image/jpeg',
        'jpeg'=>'image/jpeg',
        'jpg'=>'image/jpeg',
        'js'=>'application/x-javascript',
        'kar'=>'audio/midi',
        'latex'=>'application/x-latex',
        'lha'=>'application/octet-stream',
        'lsp'=>'application/x-lisp',
        'lzh'=>'application/octet-stream',
        'm'=>'text/plain',
        'man'=>'application/x-troff-man',
        'me'=>'application/x-troff-me',
        'mesh'=>'model/mesh',
        'mid'=>'audio/midi',
        'midi'=>'audio/midi',
        'mif'=>'application/vnd.mif',
        'mime'=>'www/mime',
        'mov'=>'video/quicktime',
        'movie'=>'video/x-sgi-movie',
        'mp2'=>'audio/mpeg',
        'mp3'=>'audio/mpeg',
        'mpe'=>'video/mpeg',
        'mpeg'=>'video/mpeg',
        'mpg'=>'video/mpeg',
        'mpga'=>'audio/mpeg',
        'ms'=>'application/x-troff-ms',
        'msh'=>'model/mesh',
        'nc'=>'application/x-netcdf',
        'oga'=>'audio/ogg',
        'ogg'=>'audio/ogg',
        'ogv'=>'video/ogg',
        'ogx'=>'application/ogg',
        'oda'=>'application/oda',
        'pbm'=>'image/x-portable-bitmap',
        'pdb'=>'chemical/x-pdb',
        'pdf'=>'application/pdf',
        'pgm'=>'image/x-portable-graymap',
        'pgn'=>'application/x-chess-pgn',
        'png'=>'image/png',
        'pnm'=>'image/x-portable-anymap',
        'pot'=>'application/mspowerpoint',
        'ppm'=>'image/x-portable-pixmap',
        'pps'=>'application/mspowerpoint',
        'ppt'=>'application/mspowerpoint',
        'ppz'=>'application/mspowerpoint',
        'pre'=>'application/x-freelance',
        'prt'=>'application/pro_eng',
        'ps'=>'application/postscript',
        'qt'=>'video/quicktime',
        'ra'=>'audio/x-realaudio',
        'ram'=>'audio/x-pn-realaudio',
        'ras'=>'image/cmu-raster',
        'rgb'=>'image/x-rgb',
        'rm'=>'audio/x-pn-realaudio',
        'roff'=>'application/x-troff',
        'rpm'=>'audio/x-pn-realaudio-plugin',
        'rtf'=>'text/rtf',
        'rtx'=>'text/richtext',
        'scm'=>'application/x-lotusscreencam',
        'set'=>'application/set',
        'sgm'=>'text/sgml',
        'sgml'=>'text/sgml',
        'sh'=>'application/x-sh',
        'shar'=>'application/x-shar',
        'silo'=>'model/mesh',
        'sit'=>'application/x-stuffit',
        'skd'=>'application/x-koan',
        'skm'=>'application/x-koan',
        'skp'=>'application/x-koan',
        'skt'=>'application/x-koan',
        'smi'=>'application/smil',
        'smil'=>'application/smil',
        'snd'=>'audio/basic',
        'sol'=>'application/solids',
        'spl'=>'application/x-futuresplash',
        'spx'=>'audio/ogg',
        'src'=>'application/x-wais-source',
        'step'=>'application/STEP',
        'stl'=>'application/SLA',
        'stp'=>'application/STEP',
        'sv4cpio'=>'application/x-sv4cpio',
        'sv4crc'=>'application/x-sv4crc',
        'swf'=>'application/x-shockwave-flash',
        't'=>'application/x-troff',
        'tar'=>'application/x-tar',
        'tcl'=>'application/x-tcl',
        'tex'=>'application/x-tex',
        'texi'=>'application/x-texinfo',
        'texinfo'=>'application/x-texinfo',
        'tif'=>'image/tiff',
        'tiff'=>'image/tiff',
        'tr'=>'application/x-troff',
        'tsi'=>'audio/TSP-audio',
        'tsp'=>'application/dsptype',
        'tsv'=>'text/tab-separated-values',
        'txt'=>'text/plain',
        'unv'=>'application/i-deas',
        'ustar'=>'application/x-ustar',
        'vcd'=>'application/x-cdlink',
        'vda'=>'application/vda',
        'viv'=>'video/vnd.vivo',
        'vivo'=>'video/vnd.vivo',
        'vrml'=>'model/vrml',
        'wav'=>'audio/x-wav',
        'wrl'=>'model/vrml',
        'xbm'=>'image/x-xbitmap',
        'xlc'=>'application/vnd.ms-excel',
        'xll'=>'application/vnd.ms-excel',
        'xlm'=>'application/vnd.ms-excel',
        'xls'=>'application/vnd.ms-excel',
        'xlw'=>'application/vnd.ms-excel',
        'xml'=>'application/xml',
        'xpm'=>'image/x-xpixmap',
        'xspf'=>'application/xspf+xml',
        'xwd'=>'image/x-xwindowdump',
        'xyz'=>'chemical/x-pdb',
        'zip'=>'application/zip',
    );
}

function getMimeType($filePath) {

    if (!is_file($filePath)) {
        return false;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filePath);
    finfo_close($finfo);

    return $mime;
}

function upload($filePath, $destinationDir = 'images', array $allowedMimes = array()) {

    if (!is_file($filePath) || !is_dir($destinationDir)) {
        return false;
    }

    if (!($mime = getMimeType($filePath))) {
        return false;
    }

    if (!in_array($mime, $allowedMimes)) {
        return false;
    }

    $ext = null;
    $extMapping = getExtensionToMimeTypeMapping();
    foreach ($extMapping as $extension => $mimeType) {
        if ($mimeType == $mime) {
            $ext = $extension;
            break;
        }
    }

    if (empty($ext)) {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    }

    if (empty($ext)) {
        return false;
    }

    $fileName = md5(uniqid(rand(0, time()), true)) . '.' . $ext;
    $newFilePath = $destinationDir.'/'.$fileName;

    if(!rename($filePath, $newFilePath)) {
        return false;
    }

    return $fileName;
}

function ordinalize($num)
{
    if ( ! is_numeric($num)) return $num;

    if ($num % 100 >= 11 and $num % 100 <= 13)
    {
        return $num."th";
    }
    elseif ( $num % 10 == 1 )
    {
        return $num."st";
    }
    elseif ( $num % 10 == 2 )
    {
        return $num."nd";
    }
    elseif ( $num % 10 == 3 )
    {
        return $num."rd";
    }
    else
    {
        return $num."th";
    }
}

db();
authenticate();