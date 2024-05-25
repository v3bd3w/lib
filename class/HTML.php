<?php // Tool for generate standard markup

class HTML
{//{{{
	static $head =
/////////////////////////////////////////////////////////////////////////////{{{
<<<HEREDOC
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
HEREDOC;
/////////////////////////////////////////////////////////////////////////////}}}
	static $title = "";
	static $styles = [];
	static $style = "";
	static $body = "";
	static $scripts = [];
	static $script = "";
	
	function __destruct()
	{//{{{
		$ob_level = ob_get_level();
		if($ob_level > 0) {
			$ob = ob_get_clean();
			ob_end_clean();
		
			if(!( defined('QUIET') && QUIET === true )) {
				$buffer = &$ob;
				
				$buffer_len = strlen($buffer);
				$default_html_len = strlen(DEFAULT_HTML);
				
				$default_html = '';
				if($buffer_len >= $default_html_len) {
					$default_html = substr($buffer, 0, $default_html_len);
				}
				
				if(strcmp(DEFAULT_HTML, $default_html) === 0) {
					$substr = substr($buffer, $default_html_len);
					$buffer = $substr;
				}
	
				if(!empty($ob)) {
					$ob = strip_tags($ob);
					
					$body = '<dialog name="output"><pre>'. $ob .'</pre></dialog>';
					HTML::$body = $body.HTML::$body;
				
					$script = 
////////////////////////////////////////////////////////////////////////////////
<<<'HEREDOC'
window.addEventListener("load", function(event) {
	let dialog = document.querySelector("dialog[name='output']");
	dialog.showModal();
});

HEREDOC;
////////////////////////////////////////////////////////////////////////////////
					HTML::$script = $script.HTML::$script;
				}
			}
		}
		$html = HTML::generate();
		echo($html);
	}//}}}
	
	static function generate_font_face(string $path_to_font) // string
	{//{{{
		$contents = file_get_contents($path_to_font);
		if(!is_string($contents)) {
			if(defined('DEBUG') && DEBUG) var_dump(['$path_to_font' => $path_to_font]);
			trigger_error("Can't get contents from font file", E_USER_WARNING);
			return(false);
		}
		$base64_font = base64_encode($contents);
		$style = 
////////////////////////////////////////////////////////////////////////////////
<<<HEREDOC
@font-face {
	font-family: 'monospace';
	src: url(data:font/truetype;base64,{$base64_font});
}

HEREDOC;
////////////////////////////////////////////////////////////////////////////////
		return($style);
	}//}}}
	
	static function generate_font_link(string $path_to_favicon) // string
	{//{{{
		$contents = file_get_contents($path_to_favicon);
		if(!is_string($contents)) {
			if(defined('DEBUG') && DEBUG) var_dump(['$path_to_favicon' => $path_to_favicon]);
			trigger_error("Can't get contents from favicon file", E_USER_WARNING);
			return(false);
		}
		$base64_favicon = base64_encode($contents);
		$head = 
////////////////////////////////////////////////////////////////////////////////
<<<HEREDOC
<link rel="icon" href="data:image/x-icon;base64,{$base64_favicon}">

HEREDOC;
////////////////////////////////////////////////////////////////////////////////
		return($head);
	}//}}}
	
	static function generate_stylesheets(array $styles) // string
	{//{{{
		$result = "";
		foreach($styles as $style) {
			if(!is_string($style)) continue;
			$result .= 
////////////////////////////////////////////////////////////////////////////////
<<<HEREDOC
<link rel="stylesheet" href="{$style}" />

HEREDOC;
////////////////////////////////////////////////////////////////////////////////
		}
		return($result);
	}//}}}
	
	static function generate_scripts(array $scripts) // string
	{//{{{
		$result = "";
		foreach($scripts as $script) {
			if(!is_string($script)) continue;
			$result .= 
////////////////////////////////////////////////////////////////////////////////
<<<HEREDOC
<script src="{$script}"></script>

HEREDOC;
////////////////////////////////////////////////////////////////////////////////
		}
		return($result);
	}//}}}

	static function get_url_path() // string
	{//{{{
		if(!@is_string($_SERVER["REQUEST_URI"])) {
			if(defined('DEBUG') && DEBUG) @var_dump(['$_SERVER["REQUEST_URI"]' => $_SERVER["REQUEST_URI"]]);
			trigger_error('Incorrect $_SERVER["REQUEST_URI"]', E_USER_WARNING);
			return(false);
		}
		
		$url_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		if(!is_string($url_path)) {
			if(defined('DEBUG') && DEBUG) var_dump(['$_SERVER["REQUEST_URI"]' => $_SERVER["REQUEST_URI"]]);
			trigger_error('Parse url failed from $_SERVER["REQUEST_URI"]', E_USER_WARNING);
			return(false);
		}
		
		return($url_path);
	}//}}}

	static function generate_csrf_input() // string
	{//{{{
		if(!( defined('CSRF_TOKEN') && is_string('CSRF_TOKEN') )) {
			trigger_error("Incorrect CSRF_TOKEN", E_USER_WARNING);
			return('');
		}
		
		$csrf_token = htmlentities(CSRF_TOKEN);
		$input = 
////////////////////////////////////////////////////////////////////////////////
<<<HEREDOC
<input name="csrf_token" value="{$csrf_token}" type="hidden" />

HEREDOC;
////////////////////////////////////////////////////////////////////////////////
		return($input);
	}//}}}

	static function generate()
	{//{{{
		$head = &self::$head;
		$title = &self::$title;
		$stylesheets = self::generate_stylesheets(self::$styles);
		$style = &self::$style;
		$body = &self::$body;
		$scripts = self::generate_scripts(self::$scripts);
		$script = &self::$script;
		$html = 
<<<HEREDOC
<!DOCTYPE html>
<html>
	<head>
{$head}
		<title>{$title}</title>
{$stylesheets}
		<style>
{$style}
		</style>
{$scripts}
		<script>
{$script}
		</script>
	</head>
	<body>
{$body}
	</body>
</html>
HEREDOC;
		return($html);
	}//}}}
	
}//}}}

