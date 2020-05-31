<?php
	sleep(2);
	include('api.php');

	if( empty($_GET['api']) ){
		header('Location: www/index.php');
		die();
	}

	if( !empty($_GET['api']) && in_array($_GET['api'], $GLOBALS['QUICKAPIS']) ){
		$api = $_GET["api"];
		$Apis = new Apis();
		unset($_GET["api"]);

		$Apis->$api( $_GET );
		die( $Apis->formatOutput() );
	}

	if( isset($_GET["api"]) && isset($_GET["api_key"]) ) {
		$api = $_GET["api"];
		$api_key = $_GET["api_key"];
		unset($_GET["api"], $_GET["api_key"]);

		$Apis = new Apis();
		if( !isset($_GET["dev"]) && !$Apis->checkKey($api_key) ) {
			die('{"data": {}, "errors": [{"type": "CommandError", "error": "Invalid api key"}], "error": 1, "time": '.time().'}');
		}

		$Apis->$api( $_GET );
		echo $Apis->formatOutput();
	} else {
		die('{"data": {}, "errors": [{"type": "CommandError", "error": "Variables not set"}], "error": 1, "time": '.time().'}');
	}