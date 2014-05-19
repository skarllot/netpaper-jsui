<?php
	require_once("config.inc.php");
	require_once("lib/nusoap/nusoap.php");

	$is_json = False;

	if (isset($_REQUEST['method'])) {
		header('Content-type: application/json');
		extract($_REQUEST);
		$is_json = True;
		if(!isset($method) || empty($method))
			$method = '';
		if(!isset($auth) || empty($auth))
			$auth = '';

		switch ($method) {
			case "createSession":
				createSession();
				break;
			case "destroySession":
				destroySession($auth);
				break;
			case "getDBVersion":
				getDBVersion($auth);
				break;
			case "getLdapConfig":
				getLdapConfig($auth);
				break;
			default:
				echo json_encode(array('error' =>
					array('code' => NULL,
					'description' => 'Invalid parameters supplied',
					'innerError' => NULL)));
				break;
		}
	}

	function createSession() {
		return callSOAP('createSession', array());
	}

	function destroySession($auth) {
		return callSOAP('destroySession', array($auth));
	}

	function getDBVersion($auth) {
		return callSOAP('getDBVersion', array($auth));
	}

	function getLdapConfig($auth) {
		return callSOAP('getLdapConfig', array($auth));
	}

	function callSOAP($name, $params) {
		global $cfg;
		global $is_json;

		$wsdl = $cfg["WS_ADDRESS"];
		$client = new soapclient($wsdl, true);
		$error = $client->getError();
		if ($error) {
			echo json_encode(array('error' => 
				array('code' => NULL,
				'description' => 'Error creating instance of soapclient',
				'innerError' => $error)));
			return;
		}

		$result = $client->call($name, $params);
		if (isset($result->faultcode)) {
			echo json_encode(array('error' =>
				array('code' => NULL,
				'description' => $result->fault,
				'innerError' => NULL)));
			return;
		}

		$error = $client->getError();
		if ($error) {
			echo json_encode(array('error' =>
				array('code' => $result['faultcode'],
				'description' => $result['faultstring'],
				'innerError' => NULL)));
			return;
		}

		if ($is_json)
			echo json_encode(array('result'=>$result));

		return $result;
	}

/*
vim: ts=4 sw=4
*/
?>