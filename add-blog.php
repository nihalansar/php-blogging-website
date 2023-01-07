<?php
ini_set('display_errors', '0');
error_reporting(0);
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
} else {
	session_unset();
	session_destroy();
	header("Location: ./");
	exit();
}

// Include config file
require_once "db-con/db.php";
// Include config file
require_once "helpers/infoprovider.php";
// Include config file
require_once "helpers/functions.php";

$err = '';
$title = $description = $image = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty(trim($_POST["title"]))) {
		$err = "Title shoud not be blank";
	} else {
		$title = $_POST["title"];
	}

	if (empty(trim($_POST["description"]))) {
		$err = "Description shoud not be blank";
	} else {
		$description = $_POST["description"];
	}

	if (isset($_FILES['image'])) {
		$allowed_ext = array('jpg', 'jpeg', 'png');
		$file_name = $_FILES['image']['name'];
		$file_ext = strtolower(end(explode('.', $file_name)));

		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];

		$type = pathinfo($file_tmp, PATHINFO_EXTENSION);
		$data = file_get_contents($file_tmp);
		$image = 'data:image/' . $type . ';base64,' . base64_encode($data);



		if (in_array($file_ext, $allowed_ext) === false) {
			$err = 'Extension not allowed';
		}

		if ($file_size > 2097152) {
			$err = 'File size must be under 2mb';
		}
	}

	if (empty($err)) {
		$sql = "INSERT INTO blogs (author, title, content, image, created_at) VALUES (?,?,?,?,?)";
		if ($stmt = mysqli_prepare($link, $sql)) {
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sssss", $param_phone, $param_title, $param_description, $param_image, $param_date);

			// Set parameters
			$param_title = $title;
			$param_phone = $_COOKIE['otpdone'];
			$param_description = $description;
			$param_image = $image;
			$param_date = $curr_timestamp;

			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
				// Redirect to login page
				mysqli_stmt_close($stmt);
				echo "<script> alert('Blog Added Successfully ✅!');window.location.href='./dashboard.php';</script>";
			} else {
				$err = mysqli_error($link);
				mysqli_stmt_close($stmt);
			}
		} else {
			mysqli_stmt_close($stmt);
		}
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Blog | Add Blog</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Mohammed_Nihal">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js" integrity="sha512-Eak/29OTpb36LLo2r47IpVzPBLXnAMPAVypbSZiZ4Qkf8p/7S/XRG5xp7OKWPPYfJT6metI+IORkR5G8F900+g==" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" />
	<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/css/new_style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/add_blog.css">
	<link rel='stylesheet' href='https://cdn.rawgit.com/bootstrap-wysiwyg/bootstrap3-wysiwyg/master/src/bootstrap3-wysihtml5.css'>
	<link rel="stylesheet" href="./style.css">
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

	<div class="menu-open hidden">
		<div class="col-12">
			<div class="row justify-content-center">
				<a class="w-100 menu-txt" href="index.php">Home &nbsp;<i class="fas fa-home red"></i></a>
			</div>
			<div class="row justify-content-center">
				<a class="w-100 menu-txt" href="logout.php">Logout &nbsp;<i class="fas fa-sign-out-alt red"></i></a>
			</div>
		</div>
	</div>

	<br>
	<!-- Main -->
	<section class="section">
		<?php if ($err != '') { ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong><?php echo $err; ?> </strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php } ?>
		<div class="col-12 w-100 p-0 vertical-center knock-col">
			<div class="row w-100 justify-content-center" style="margin: auto">
				<div class="knock-box b-style justify-content-center">
					<div class="container">
						<div class="row">

							<div class="col-12 p-4">

								<h1>Add Blog <i class="fas fa-plus"></i></h1>

								<form enctype="multipart/form-data" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post" id="myform">

									<div class="form-group justify-content-center <?php echo (!empty($err)) ? 'has-error' : ''; ?>">
										<div class="form-group has-error">
											<label for="slug">Title <span class="require">*</span></label>
											<input type="text" required minlength="10" class="form-control" name="title" />
										</div>

										<div class="form-group">
											<label for="description">Description <span class="require">*</span></label>
											<textarea rows="5" required minlength="10" class="form-control" name="description"></textarea>
										</div>

										<div class="form-group">
											<label for="title">Image <span class="require">*</span></label><br>
											<input type="file" required name="image" accept="image/png, image/jpeg">
										</div>

										<div class="form-group">
											<button type="submit" class="btn btn-primary">
												Add Blog
											</button>
											<button type="reset" class="btn btn-default">
												Cancel
											</button>
										</div>

								</form>
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
<script src='https://cdn.rawgit.com/bootstrap-wysiwyg/bootstrap3-wysiwyg/master/dist/bootstrap3-wysihtml5.all.min.js'></script>
<script src="assets/js/add_blog.js" type="text/javascript"></script>