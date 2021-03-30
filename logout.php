<?php
	require_once "config.php";
	unset($_SESSION['access_token']);
	unset($_SESSION['token_remove']);
	unset($_SESSION['user']);
	unset($_SESSION['psw']);
	unset($_SESSION['account']);
    unset($_SESSION['google_user']);
	$gClient->revokeToken();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
	//header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	//header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost/google_login/index.php');
	//header('https://www.google.com/accounts/Logout');
	//header('Location:http://localhost/google_login/index.php');
	//echo'<a href="https://www.google.com/accounts/Logout" target="myiframe">logout</a>';
	//echo'<iframe name="myiframe" style="display:none" onload="redirect()"></iframe>';
	//header('Location:登入 - Google 帳戶.html');
	header("Location: index.php");
	exit();
?>

