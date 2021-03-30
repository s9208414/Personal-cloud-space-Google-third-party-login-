<!--下面的程式碼用來建立google登入連結-->
<?php
    require_once "config.php";

	if (isset($_SESSION['access_token'])) {
		header('Location: main.php');
		exit();
	}

	$loginURL = $gClient->createAuthUrl();
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>mycattoday</title>
</head>
<body>
	<h1>專門為喵奴設計的網站</h1>
    <form method="post">
        <label for='user'>帳號:</label>
		<input type='text' name='user' id='user' required /><br/>
		<label for='psw'>密碼:</label></div>
		<input type='password' name='psw' id='psw' required /><br/>
		<input type='submit' name='login' value='登入'/>
		<input type='submit' name='register' value='註冊'/>
		<input type="button" onclick="window.location = '<?php echo $loginURL ?>';" value="使用Google登入" >
    </form>
	<!--下面的程式碼是處理註冊成功與否的部分-->
   <?php
		$Row_register = "temp";
		$usr_cloud_path = "";
		//$conn = new PDO("mysql:host=sql201.epizy.com;dbname=epiz_26902167_mycat","epiz_26902167","qb6BHxJKSB29j6c");
		$conn = new PDO("mysql:host=localhost;dbname=test","root","");
		//$conn->set_charset("UTF-8");
					
		if(isset($_POST['register'])){
			$stmt = $conn->prepare('SELECT * FROM myuser');
			$stmt->execute();
			while($row = $stmt->fetch()){
				if($row['account']==$_POST['user']){
					global $Row_register;
					$Row_register = $row['account'];
					break;
				}
			}

			if($Row_register!=$_POST['user']){
				$user = $_POST['user'];
				$psw = md5($_POST['psw']);
				$usr_cloud_path = './'.$user;
				$stmt = $conn->prepare("INSERT INTO myuser VALUES('',?,?,?,?)");
				$stmt->bindParam(3,$usr_cloud_path);
				$stmt->bindParam(1,$user);
				$stmt->bindParam(2,$psw);
				$stmt->bindParam(4,$user);
				$stmt->execute();
				echo '<p>註冊成功</p>';
				if(!is_dir($usr_cloud_path)){
					mkdir($usr_cloud_path,0777,"true");
				}
			}else{
				echo'<p>此帳號已被註冊了喔</p>';
			}
		}
		
		
		?>
	<!--下面的程式碼是處理登入成功與否的部分-->
	<?php
        $check = 0;
		if(isset($_POST['login'])){
			session_start();
			//$conn = new PDO("mysql:host=sql201.epizy.com;dbname=epiz_26902167_mycat","epiz_26902167","qb6BHxJKSB29j6c");
			$conn = new PDO("mysql:host=localhost;dbname=test","root","");
			$stmt = $conn->prepare('SELECT * FROM myuser');
			$stmt->execute();
			while($row = $stmt->fetch()){
				if(isset($_SESSION['account'])){
					echo '您早已登入';
                    header('Location:main.php');
					break;
				}
				if($row['account'] == $_POST['user']){
					if($row['psw'] == md5($_POST['psw'])){
						echo'登入成功';
						$_SESSION['user'] = $row['user'];
						$_SESSION['psw'] = $row['psw'];
						$_SESSION['account'] = $row['account'];
                        $check = 0;
						header('Location:main.php');
					}
				}
                if(($row['account'] != $_POST['user'])||($row['psw'] != $_POST['psw'])){
                    $check = 1;
                }
                
			}
            if($check>0){
                echo'登入失敗，帳號或密碼有誤';
            }
		}
	?>
</body>
</html>