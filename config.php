<?php
	session_start();
	require_once "GoogleAPI/vendor/autoload.php";
	$gClient = new Google_Client();
	$gClient->setClientId("676864220694-9na93al4u53pitv5m8gra9cd0usnflec.apps.googleusercontent.com");
	$gClient->setClientSecret("QvRLAjqdNv1y3hFUx2AKhMdX");
	$gClient->setApplicationName("mycattoday.ml");
	$gClient->setRedirectUri("http://mycattoday.ml/g-callback.php");
	$gClient->addScope('email');
	$gClient->addScope('profile');
?>
