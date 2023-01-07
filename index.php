<?php
ini_set('display_errors', '0');
error_reporting(0);
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("Location: ./dashboard.php");
	exit();
}
// Include config file
require_once "db-con/db.php";
// Include config file
require_once "helpers/infoprovider.php";
// Include config file
require_once "helpers/functions.php";

$phone = '';
$phone_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty(trim($_POST["phone"]))) {
		$phone_err = "Phone number shoud not be blank";
	} else {
		if (!preg_match('/[6789][0-9]{9}/', $_POST["phone"])) {
			$phone_err = "Invalid phone number, please enter a valid Indian phone number without country code.";
		} else {
			$phone = $_POST["phone"];
		}
	}
	if (empty($phone_err)) {
		$rand_id = createRandomPassword();
		$otp = createOTP();
		$c = 0;
		$stmt = $link->prepare("SELECT number 
                       FROM users
                       WHERE number = ? ");
		$stmt->bind_param("s", $phone);
		if ($stmt->execute()) {
			$stmt->bind_result($phone_old);
			while ($stmt->fetch()) {
				$c++;
			}
		}
		if (!$c > 0) {
			$sql = "INSERT INTO users (number, otp, u_id, created, ip) VALUES (?,?,?,?,?)";
			if ($stmt = mysqli_prepare($link, $sql)) {
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssss", $param_phone, $param_otp, $param_randid, $param_date, $param_ip);

				// Set parameters
				$param_otp = $otp;
				$param_phone = $phone;
				$param_randid = $rand_id;
				$param_ip = $ip_address;
				$param_date = $curr_timestamp;

				// Attempt to execute the prepared statement
				if (mysqli_stmt_execute($stmt)) {
					otpdone($phone);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://www.fast2sms.com/dev/bulkV2?authorization=sBuCdzQk5HDoWFYjAMnp1ihx4JlPqUIyevwKb823Tfc06t9OXrabYpqC8ZuJ439KhXvf5WcFN7AtVGdB&route=dlt&sender_id=GEEKST&message=140190&variables_values=User%7C" . $otp . "%7C&flash=0&numbers=" . $phone);
					curl_setopt($ch, CURLOPT_POST, 0);
					$result = curl_exec($ch);
					curl_close($ch);
					// Redirect to login page
					mysqli_stmt_close($stmt);
					header("Location: ./otp.php");
					exit();
				} else {
					mysqli_error($link);
					mysqli_stmt_close($stmt);
				}
				// Close statement
				mysqli_stmt_close($stmt);
			} else {
				mysqli_stmt_close($stmt);
			}
		} else {
			$sql = "UPDATE users SET otp=?, u_id=? WHERE number=?";
			if ($stmt = mysqli_prepare($link, $sql)) {
				mysqli_stmt_bind_param($stmt, "sss", $param_otp, $param_randid, $param_phone);
				$param_otp = $otp;
				$param_phone = $phone;
				$param_randid = $rand_id;
				if (mysqli_stmt_execute($stmt)) {
					otpdone($phone);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://www.fast2sms.com/dev/bulkV2?authorization=sBuCdzQk5HDoWFYjAMnp1ihx4JlPqUIyevwKb823Tfc06t9OXrabYpqC8ZuJ439KhXvf5WcFN7AtVGdB&route=dlt&sender_id=GEEKST&message=140190&variables_values=User%7C" . $otp . "%7C&flash=0&numbers=" . $phone);
					curl_setopt($ch, CURLOPT_POST, 0);
					$result = curl_exec($ch);
					curl_close($ch);
					// Redirect to login page
					mysqli_stmt_close($stmt);
					header("Location: ./otp.php");
					exit();
				} else {
					$err = mysqli_error($link);
					mysqli_stmt_close($stmt);
				}
			} else {
				echo mysqli_error($link);
				exit();
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Blog | Welcome</title>
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
				<a href="./" target="_blank"><img src="assets/img/logo_white.png" class="brand-sb" alt="CoWin2.0 Logo"></a>
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
									<p class="nav-head">Welcome &nbsp;<i class="fas fa-praying-hands"></i></p>
								</div>
							</div>
							<div class="row justify-content-center p-2">
								<div class="row justify-content-center reg-form in-r but">
									<div class="card-body">
										<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post" id="myform">
											<div class="form-group justify-content-center <?php echo (!empty($sb_phone_err)) ? 'has-error' : ''; ?>">
												<label class="w-100" style="text-align: center;">Phone Number <i class="fas fa-phone"></i></label>
												<input type="text" name="phone" class="form-control" pattern="[6789][0-9]{9}" title="Please enter a valid indian number without country code." value="" placeholder="Type your phone number" required>
												<p class="mt-2 w-100 help-block" style="text-align:center;"><?php echo $phone_err; ?></p>
											</div>
											<p class="bottomtxt">An OTP will be sent to your mobile number for verification</p>
											<div class="row justify-content-center form-buttons">
												<input type="submit" class="btn btn-primary submitbutton" value="Get OTP">
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