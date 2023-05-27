<?php

if (
  isset($_POST['fname']) &&
  isset($_POST['email']) &&
  isset($_POST['pass'])
) {

  include "../db_conn.php";
  include 'User.php';



  $fname = $_POST['fname'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $rptPass = $_POST['rpt_pass'];
  // $uType = $_POST['uType'];

  $data = "fname=" . $fname . "&email=" . $email;




  if (empty($fname)) {
    $em = "Full name is required";
    header("Location: ../index.php?error=$em&$data");
    exit;
  } else if (empty($email)) {
    $em = "Email is required";
    header("Location: ../index.php?error=$em&$data");
    exit;
  } else if (empty($pass)) {
    $em = "Password is required";
    header("Location: ../index.php?error=$em&$data");
    exit;
  } else if ($pass !== $rptPass) {
    $em = "Password is not match";
    header("Location: ../index.php?error=$em&$data");
    exit;
  } else if (strlen($pass) < 8) {
    $em = "Password length must be greather than 7";
    header("Location: ../index.php?error=$em&$data");
    exit;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $em = "Email is not valid";
    header("Location: ../index.php?error=$em&$data");
  } else if (!emailCheck($email, $conn)) {
    $em = "Email used";
    header("Location: ../index.php?error=$em&$data");
    exit;
  } else {
    // hashing the password
    $pass = password_hash($pass, PASSWORD_DEFAULT);

    if (isset($_FILES['pp']['name']) and !empty($_FILES['pp']['name'])) {


      $img_name = $_FILES['pp']['name'];
      $tmp_name = $_FILES['pp']['tmp_name'];
      $error = $_FILES['pp']['error'];

      if ($error === 0) {
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_to_lc = strtolower($img_ex);

        $allowed_exs = array('jpg', 'jpeg', 'png');
        if (in_array($img_ex_to_lc, $allowed_exs)) {
          $new_img_name = uniqid($email, true) . '.' . $img_ex_to_lc;
          $img_upload_path = '../upload/' . $new_img_name;
          move_uploaded_file($tmp_name, $img_upload_path);

          // Insert into Database
          $uType = 'admin';
          if (adminExistCheck($conn)) {
            $uType = 'user';
          }
          $sql = "INSERT INTO users(fname, email, password, user_type, pp) 
                 VALUES(?,?,?,?,?)";
          $stmt = $conn->prepare($sql);
          $stmt->execute([$fname, $email, $pass, $uType, $new_img_name]);

          header("Location: ../index.php?success=Your account has been created successfully");
          exit;
        } else {
          $em = "You can't upload files of this type";
          header("Location: ../index.php?error=$em&$data");
          exit;
        }
      } else {
        $em = "unknown error occurred!";
        header("Location: ../index.php?error=$em&$data");
        exit;
      }


    } else {
      $uType = 'admin';
      if (adminExistCheck($conn)) {
        $uType = 'user';
      }
      $sql = "INSERT INTO users(fname, email, password, user_type) 
       	        VALUES(?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$fname, $email, $pass, $uType]);

      header("Location: ../index.php?success=Your account has been created successfully");
      exit;
    }
  }


} else {
  header("Location: ../index.php?error=error");
  exit;
}


?>