<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {

include "db_conn.php";
include 'php/User.php';
$user = getUserById($_SESSION['id'], $conn);


 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php if ($user) { ?>
        <div class="container mt-5">

    <?php 
        include 'navbar.php';
    ?>
    <div class="d-flex justify-content-center align-items-center">
    	<div class="w-100 py-5 text-center">
    		<img src="upload/<?=$user['pp']?>"
    		     class="img-fluid rounded-circle w-50">
            <h3 class="display-4 "><?=$user['fname']?></h3>
            <p><?=$user['email']?></p>
            <p class='text-success'><?=$user['user_type']?></p>
		</div>
    </div>
    <?php }else { 
     header("Location: login.php");
     exit;
    } ?>
</body>
</html>

<?php }else {
	header("Location: login.php");
	exit;
} ?>