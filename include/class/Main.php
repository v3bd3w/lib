<?php

class Main
{//{{{
	function __construct()
	{//{{{
		$request_method = array_get_string('REQUEST_METHOD', $_SERVER);
		if(!is_string($request_method)) {
			trigger_error("Can't get http request method", E_USER_WARNING);
			return(false);
		}
		
		switch($request_method) {
			case('GET'):
				$return = $this->handle_get_request();
				if($return !== true) {
					trigger_error("Handle get request failed", E_USER_ERROR);
					exit(255);
				}
				
				$HTML = new HTML;
				exit(0);
				
			case('POST'):
				$return = $this->handle_post_request();
				if($return !== true) {
					trigger_error("Handle post request failed", E_USER_ERROR);
					exit(255);
				}
				exit(0);
				
			default:
				if(defined('DEBUG') && DEBUG) var_dump(['$request_method' => $request_method]);
				trigger_error("Unsupported request method", E_USER_ERROR);
				exit(255);
		}
	}//}}}
	
	function handle_get_request()
	{//{{{
		$page = @array_get_string('page', $_GET);
		if(!is_string($page)) {
			$page = '';
		}
		
		$Page = new Page();
		
		switch($page) {
			case(''):
				$return = $Page->index();
				if(!$return) {
					trigger_error("Can't create 'index' page", E_USER_WARNING);
					return(false);
				}
				return(true);
				
			default:
				if(defined('DEBUG') && DEBUG) var_dump(['$page' => $page]);
				trigger_error("Unsupported 'page'", E_USER_WARNING);
				return(false);
		}
		return(false);
	}//}}}
	
	function handle_post_request()
	{//{{{
		$action = array_get_string('action', $_POST);
		if(!is_string($action)) {
			trigger_error("Can't get 'action' from POST request", E_USER_WARNING);
			return(false);
		}
	
		switch($action) {				
			case('phpinfo'):
				ob_end_clean();
				phpinfo();
				return(true);
			default:
				if(defined('DEBUG') && DEBUG) var_dump(['$action' => $action]);
				trigger_error("Unsupported 'action'", E_USER_WARNING);
				return(false);
		}
		return(false);
	}//}}}
	
}//}}}

