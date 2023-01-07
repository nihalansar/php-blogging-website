<?php
ini_set('display_errors', '0');
error_reporting(0);
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("Location: ./dashboard.php");
	exit();
}
if(!isset($_COOKIE['otpdone']) || $_COOKIE['otpdone'] == false){
	header("Location: ./");
	exit();
}else{
	$phone = $_COOKIE['otpdone'];
 	if (!preg_match('/[6789][0-9]{9}/', $phone)) {
      header("Location: ./");
	    exit();
   }
   $phone_4 = substr($phone, -4);
}

// Include config file
require_once "db-con/db.php";
// Include config file
require_once "helpers/infoprovider.php";
// Include config file
require_once "helpers/functions.php";


// $c = 0;
// $stmt = $link->prepare("SELECT otp
//                        FROM users
//                        WHERE number = ? ");
// $stmt->bind_param("s", $phone);
// if($stmt->execute()){
// 	$stmt->bind_result($otp_old);
// 	while($stmt->fetch()){
// 		$c++;
// 	}
// }
// if(!$c>0){
// 	sleep(3);
// 	header("Location: .//");
// 	exit();
// }

// $otp = '';
// $otp_err = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
if(!isset($_COOKIE['otpdone']) || $_COOKIE['otpdone'] == false){
	header("Location: ./");
	exit();
}

if(empty(trim($_POST["otp"]))){
    $otp_err = "OTP shoud not be blank";
   }
   else{
   	if (!preg_match('/[0-9]{6}/', $_POST["otp"])) {
        $otp_err = "Invalid otp.";
     }
     else{
     	$otp = $_POST["otp"];
     }
   }


if(empty($otp_err)){
	$b = 0;
	$stmt = $link->prepare("SELECT u_id
	                       FROM users
	                       WHERE number = ? AND otp = ?");
	$stmt->bind_param("ss", $phone, $otp);
	if($stmt->execute()){
		$stmt->bind_result($u_id);
		while($stmt->fetch()){
		$b++;
	}
	}
	if($b>0){
		$sql = "UPDATE users SET otp='' WHERE u_id=?";
		if($stmt = mysqli_prepare($link, $sql)){
		    mysqli_stmt_bind_param($stmt, "s", $param_uid);
		    $param_uid = $u_id;
		    mysqli_stmt_execute($stmt);
		}
		session_start();                  
    // Store data in session variables
    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $u_id;
		header("Location: ./dashboard.php");
		exit();
	}else{
		$otp_err = 'Invalid OTP';
	}

}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog | OTP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Mohammed_Nihal">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js" integrity="sha512-Eak/29OTpb36LLo2r47IpVzPBLXnAMPAVypbSZiZ4Qkf8p/7S/XRG5xp7OKWPPYfJT6metI+IORkR5G8F900+g==" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" />
	<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/icon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/icon/favicon-16x16.png">
	<link rel="manifest" href="assets/icon/site.webmanifest">
	<link rel="shortcut icon" href="assets/icon/favicon.ico">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-config" content="assets/icon/browserconfig.xml">
	<meta name="theme-color" content="#191970">
	<!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="./">
    <meta property="og:title" content="Blog">
    <meta property="og:description" content="All in one blog website.">
    <meta property="og:image" content="./assets/img/fav.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="./">
    <meta property="twitter:title" content="Blog">
    <meta property="twitter:description" content="All in one blog website.">
    <meta property="twitter:image" content="./assets/img/fav.png">
</head>
<body>
	<!-- Loader -->
    <div class="loader">
      <img src="assets/img/load.gif" style="width: 200px; height: 200px;" alt="Loading...">
    </div>

     <!-- navigation bar -->
	 <nav class="navbar mt-3 d-flex">
	  <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a href="./"><img src="assets/img/logo_white.png" class="brand-sb" alt="CoWin2.0 Logo"></a>
        </li>
      </ul>
	  <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="#"><i class="fas fa-bars menubar"></i></a>
        </li>
      </ul>
	 </nav>
     
     <br>
	 <!-- Main -->
	 <section class="section">
	 	<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>OTP : </strong> Generated.
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>
	 	<div class="col-12 w-100 p-0 vertical-center knock-col">
	 		<div class="row w-100 justify-content-center" style="margin: auto">
	 			<div class="knock-box b-style">
		 				<div class="row justify-content-center" style="margin: auto">
		 					<div class="col-md-7 align-self-center">
		 						<div class="row justify-content-center mt-4">
		 						<img src="assets/img/logo.png" class="responsive-img wow animate__animated animate__heartBeat">
		 					    </div>
		 					    <div class="row justify-content-center">
		 			    </div>
		 					</div>
		 					<div class="col-md-6 but-down back-blu card-pad-round mt-4 mb-4">
		 						    <div class="row justify-content-center p-2">
		 			    				<div class="row justify-content-center in-r but">
		 			    					<p class="nav-head">Verification &nbsp;<i class="fab fa-searchengin"></i></p>
		 			    				</div>
		 			    			</div>
		 			    			<div class="row justify-content-center p-2">
		 			    			<div class="row justify-content-center reg-form in-r but">
		 			    			<div class="card-body">
		 			    				<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post" id="myform">
		 			    					<div class="form-group justify-content-center <?php echo (!empty($sb_phone_err)) ? 'has-error' : ''; ?>">
				                      <label class="w-100" style="text-align: center;">OTP Verification <i class="far fa-comment-dots"></i></label>
				                      <input type="text" name="otp" class="form-control" pattern="[0-9]{6}" title="Please enter a valid otp." value="" placeholder="Enter OTP" required>
				                      <p class="mt-2 w-100 help-block" style="text-align:center;"><?php echo $otp_err; ?></p>
				                </div>
				                <p class="bottomtxt">An OTP has been sent to<br>XXX XXX <?php echo $phone_4; ?></p>
				                <div class="form-group row justify-content-center">
				                	<a href="" class="bottomtxt blu">Resend OTP</a>
				                </div>
				                <div class="row justify-content-center form-buttons">
				                	<input type="submit" class="btn btn-primary submitbutton" value="Proceed">
				                </div>
		 			    				</form>
		 			    			</div>
		 			    		  </div>
		 			    		  </div>
		 					</div>
		 			    </div>
		 			</div>
	 		</div>
		</div>
	 </section>
	 <!-- FOOTER -->
      <footer id="footer-id">
        <p class="footer-para">
				Made with <i class="fas fa-heart blu"></i> by &nbsp;<a class="tag" href="#">Group 2</a>
        </p>
      </footer>
      <div id="loader"></div>
</body>
</html>
<script src="assets/js/app.js" type="text/javascript"></script>