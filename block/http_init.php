<?php

/// initialization - Initializes the output of 'debug', 'error', 'warning', 'notification' messages.
//{{{

// info
/*{{{
	index.php?debug - Enables the output of debug messages.
	index.php?verbose - Enables the output of notice messages.
	index.php?quiet - Disables the output of error, warning, notice messages.
	
	index.php
	index.php?verbose
	index.php?verbose&debug
	index.php?quiet
}}}*/

if(isset($_GET["debug"])) {
	define('DEBUG', true);
}
if(isset($_GET["verbose"])) {
	define('VERBOSE', true);
}
if(isset($_GET["quiet"])) {
	define('QUIET', true);
}

if(defined('QUIET') && QUIET === true) {
	ini_set('error_reporting', 0);
	ini_set('display_errors', '0');
}
else {
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', '1');
	ini_set('html_errors', '0');
}

$string = 
////////////////////////////////////////////////////////////////////////////////
<<<'HEREDOC'
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	</head>
	<body>
<pre>
HEREDOC;
////////////////////////////////////////////////////////////////////////////////

define('DEFAULT_HTML', $string);
unset($string);

ob_start(function($buffer) {
	$buffer_len = strlen($buffer);
	$default_html_len = strlen(DEFAULT_HTML);
	
	$default_html = '';
	if($buffer_len >= $default_html_len) {
		$default_html = substr($buffer, 0, $default_html_len);
	}
	
	if(strcmp(DEFAULT_HTML, $default_html) === 0) {
		$substr = substr($buffer, $default_html_len);
		$buffer = DEFAULT_HTML.htmlentities($substr);
		return($buffer);
	}
	else {
		$buffer = htmlentities($buffer);
		return($buffer);
	}
});

echo(DEFAULT_HTML);

if(!true) // test
{//{{{
	echo "<script>alert('XA!');</script>\n";
	
	if(defined('DEBUG') && DEBUG) var_dump(['$_GET' => $_GET]);
	if(defined('VERBOSE') && VERBOSE) {
		trigger_error("Test notice", E_USER_NOTICE);
	}
	
	trigger_error("Test warning", E_USER_WARNING);
	trigger_error("Test error", E_USER_ERROR);
}//}}}

//}}}

