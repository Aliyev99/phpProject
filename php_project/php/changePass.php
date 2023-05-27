<?php
session_start();

include "../db_conn.php";
include '../php/User.php';
$user = getUserById($_SESSION['id'], $conn);
$password = $user['password'];
if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {



    if (
        isset($_POST['pass']) &&
        isset($_POST['newPass'])
    ) {

        include "../db_conn.php";

        $pass = $_POST['pass'];
        $newPass = $_POST['newPass'];
        $id = $_SESSION['id'];

        if (empty($pass)) {
            $em = "Current password is required";
            header("Location: ../changePass.php?error=$em");
            exit;

        } else if (empty($newPass)) {
            $em = "New password is required";
            header("Location: ../changePass.php?error=$em");
            exit;

        } else if (strlen($newPass) < 8) {
            $em = "Password length must be greather than 7";
            header("Location: ../index.php?error=$em&$data");
            exit;
        } else if (!password_verify($pass, $password)){
            $em = "Wrong current password";
            header("Location: ../changePass.php?error=$em");
            exit;

        } else {
            
           

            $newPass = password_hash($newPass, PASSWORD_DEFAULT);

            $sql = "UPDATE users 
       	        SET password=?
                WHERE id=?";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$newPass, $id]);

            header("Location: ../changePass.php?success=Your password has been updated successfully");
            exit;

        }


    } else {
        header("Location: ../changePass.php?error=error");
        exit;
    }


} else {
    header("Location: login.php");
    exit;
}