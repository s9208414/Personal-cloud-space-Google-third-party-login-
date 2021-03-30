<?php
	require_once "config.php";

	if (isset($_SESSION['access_token']))
		$gClient->setAccessToken($_SESSION['access_token']);
	else if (isset($_GET['code'])) {
		$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['access_token'] = $token;
	} else {
		header('Location: index.php');
		exit();
	}

	$oAuth = new Google_Service_Oauth2($gClient);
	$userData = $oAuth->userinfo_v2_me->get();
	
	$_SESSION['user'] = $userData['givenName'];
	$_SESSION['account'] = $userData['email'];
    $_SESSION['google_user'] = true;
	/*if (isset($_SESSION['token_remove'])){
		header('Location: main.php');
	}*/
	header('Location: main.php');
	exit();
?>