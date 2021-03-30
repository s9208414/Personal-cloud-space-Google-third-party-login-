<?php
	session_start();

	if (!isset($_SESSION['access_token'])&&!isset($_SESSION['account'])) {
		header('Location: index.php');
		exit();
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>mycattoday</title>
</head>
<body>
	<form method="post" enctype="multipart/form-data">
		選擇上傳項目:
		<input type="file" name="file"/>
		<input type="hidden" name="pass" value="<?php ?>">
		</br>
		<input type="submit" name="submit"/>	
		<input type="button" onclick="window.location = 'logout.php';" value="登出" class="btn btn-danger">
	</form>
		<!-- 以下是處理檔案上傳的部分-->
		<?php
			//$conn = new PDO("mysql:host=sql201.epizy.com;dbname=epiz_26902167_mycat","epiz_26902167","qb6BHxJKSB29j6c");
			$conn = new PDO("mysql:host=localhost;dbname=test","root","");
			date_default_timezone_set("Asia/Taipei");
			$Row = "temp";
			$Path = "";
			/*if(!isset($_SESSION['decide'])){
				$_SESSION['decide']=0;
			}*/
			//$post = $_POST['submit'];
			if(isset($_POST['submit'])){
				session_start();
				//echo $_SESSION['user'];
				$stmt = $conn->prepare("SELECT * FROM myblob");
				$stmt->execute();
				//echo $_FILES['file']['name'];
				while($row = $stmt->fetch()){
					//echo $row;
					//echo $_SESSION['user'];
					if($row['account']==$_SESSION['account']){
						if($row['name']==$_FILES['file']['name']){
							global $Row;
							$Row = $row['name'];
							break;
						}
						else{
							global $Row;
							$Row = "temp";
							//給自己:不能使用break 因為有可能撈出來的下幾筆資料會有檔名和上傳檔案的檔名相同的情況
						}
					}
				}
				//echo $Row."\n";
				//echo $_FILES['file']['name'];
				if($Row!=$_FILES['file']['name']){
					$name = $_FILES['file']['name'];
					$type = $_FILES['file']['type'];
					if($_FILES['file']['error']>0){
						echo'無法上傳檔案至雲端';
						echo$_FILES['file']['error'];
					}else{
						copy($_FILES['file']['tmp_name'],$_SESSION['account']."/".$_FILES['file']['name']);
						$Path = $_SESSION['account']."/".$_FILES['file']['name'];
						$path = $Path;
						//echo $data;
						$time = date("Y/m/d h:i:sa");
						//echo $time;
						$stmt = $conn->prepare("INSERT INTO myblob VALUES('',?,?,?,?,?,?)");
						$stmt->bindParam(1,$name);
						$stmt->bindParam(2,$type);
						$stmt->bindParam(3,$path);
						$stmt->bindParam(4,$time);
						$stmt->bindParam(5,$_SESSION['user']);
						$stmt->bindParam(6,$_SESSION['account']);
						$stmt->execute();
						echo'上傳檔案成功';					
					}
					
					//$_SESSION['decide']+=1;
					
					
				}else{
					$name = $_FILES['file']['name'];
					$type = $_FILES['file']['type'];
					if($_FILES['file']['error']>0){
						echo'無法更新檔案至雲端';
						echo$_FILES['file']['error'];
					}else{
						copy($_FILES['file']['tmp_name'],$_SESSION['account']."/".$_FILES['file']['name']);
						$Path = $_SESSION['account']."/".$_FILES['file']['name'];
						$path = $Path;
						//echo $data;
						$time = date("Y/m/d h:i:sa");
						//echo $time;
						$stmt = $conn->prepare("UPDATE myblob SET update_time=? WHERE account=? AND name=?");
						$stmt->bindParam(1, $time);
						$stmt->bindParam(2, $_SESSION['account']);
						$stmt->bindParam(3, $_FILES['file']['name']);
						$stmt->execute();
						echo'更新檔案成功';			
					}
				}
			}
			
		?>	
		<!--下面程式用來顯示從資料庫提取的檔案-->
		<ol>
		<?php
				
			//session_start();
				
			if(isset($_SESSION['account'])){
				//pre_stmt = "SELECT * FROM myblob WHERE user='".$_SESSION['user']."'";
				$stmt = $conn->prepare("SELECT * FROM myblob");
				$stmt->execute();
				echo '<b>您的愛貓今天的情況</b>';
				/*$testRow = $stmt->fetch();
				if($testRow!=null){
					echo '<b>您的愛貓今天的情況</b>';
					echo '<br>';
				}else{
					//測試階段暫時不會顯示下面這一段話
					echo '<b>您尚未使用攝像頭，以至於無法提供您愛貓的情況</b>';
				}*/
				while($row = $stmt->fetch()){
				//echo $row['mime'];
				//echo "<li>上傳日期:".$row['update_time']."</br><embed src='data:".$row['mime'].";base64,".base64_encode($row['data'])."' width='200' /></li>";
				//echo $_SESSION['decide'];
				//echo $_POST['decide'];
						
					if($row['account']==$_SESSION['account']){
						echo"<li>上傳日期:".$row['update_time']."<br/><embed src='./".$row['path']."' width='200'/></li>";
					}
				}
					
			}
		?>
		</ol>
		<!--下面程式碼處理google帳戶登入-->
		<?php //echo $_SESSION['user'] 
			if(isset($_SESSION['google_user'])){
				$Row_register = "temp";
				$usr_cloud_path = "";
				$conn = new PDO("mysql:host=sql201.epizy.com;dbname=epiz_26902167_mycat","epiz_26902167","qb6BHxJKSB29j6c");
				//$conn = new PDO("mysql:host=localhost;dbname=test","root","");
				$stmt = $conn->prepare('SELECT * FROM myuser');
				$stmt->execute();
				while($row = $stmt->fetch()){
					if($row['account']==$_SESSION['account']){
						global $Row_register;
						$Row_register = $row['account'];
						break;
					}
				}
				if($Row_register!=$_SESSION['account']){
					$user = $_SESSION['user'];
					$account = $_SESSION['account'];
					$psw = '';
					$usr_cloud_path = './'.$account;
					$stmt = $conn->prepare("INSERT INTO myuser VALUES('',?,?,?,?)");
					$stmt->bindParam(3,$usr_cloud_path);
					$stmt->bindParam(1,$user);
					$stmt->bindParam(2,$psw);
					$stmt->bindParam(4,$account);
					$stmt->execute();
					//echo '<p>註冊成功</p>';
					if(!is_dir($usr_cloud_path)){
						mkdir($usr_cloud_path,0777,"true");
					}
				}
			}
			
		?>

</body>
</html>