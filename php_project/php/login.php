<?php
session_start();

if (
   isset($_POST['email']) &&
   isset($_POST['pass'])
) {

   include "../db_conn.php";

   $email = $_POST['email'];
   $pass = $_POST['pass'];

   $data = "email=" . $email;

   if (empty($email)) {
      $em = "Email is required";
      header("Location: ../login.php?error=$em&$data");
      exit;
   } else if (empty($pass)) {
      $em = "Password is required";
      header("Location: ../login.php?error=$em&$data");
      exit;
   } else {

      $sql = "SELECT * FROM users WHERE email = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$email]);

      if ($stmt->rowCount() == 1) {
         $user = $stmt->fetch();

         $userEmail = $user['email'];
         $password = $user['password'];
         $fname = $user['fname'];
         $id = $user['id'];
         $pp = $user['pp'];
         $userType = $user['user_type'];
         $isActive = $user['isActive'];

         if (!$isActive) {
            $em = "Your accaunt is blocked";
            header("Location: ../login.php?error=$em&$data");
         } else {

         

         if ($userEmail === $email) {
            if (password_verify($pass, $password)) {
               $_SESSION['id'] = $id;
               $_SESSION['fname'] = $fname;
               $_SESSION['pp'] = $pp;

               $_SESSION['userType'] = $userType;

               if ($userType === 'user') {
                  header("Location: ../home.php");
               } else if ($userType === 'admin') {
                  header("Location: ../adminPage.php");
               } else {
                  $em = "Error";
                  header("Location: ../login.php?error=$em&$data");
                  exit;
               }


               exit;
            } else {
               $em = "Incorect password";
               header("Location: ../login.php?error=$em&$data");
               exit;
            }

         } else {
            $em = "Incorect email";
            header("Location: ../login.php?error=$em&$data");
            exit;
         }
      }

      } else {
         $em = "Incorect email or password";
         header("Location: ../login.php?error=$em&$data");
         exit;
      }
   }


} else {
   header("Location: ../login.php?error=error");
   exit;
}