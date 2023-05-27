<?php
session_start();

if (isset($_GET['id'])) {



    if (
        isset($_POST['fname']) &&
        isset($_POST['email'])
    ) {

        include "../db_conn.php";
        include "User.php";

        $fname = $_POST['fname'];
        $email = $_POST['email'];
        $old_pp = $_POST['old_pp'];
        $newPass = $_POST['pass'];

        $id = $_GET['id'];

        $user = getUserById($id, $conn);

        if (empty($fname)) {
            $em = "Full name is required";
            header("Location: ../editUserPage.php?id=$id&error=$em");
            exit;
        } else if (empty($email)) {
            $em = "Email is required";
            header("Location: ../editUserPage.php?error=$em");
            exit;
        } else if (strlen($newPass) < 8 && strlen($newPass) > 0) {
            $em = "Password length must be greather than 7";
            header("Location: ../index.php?error=$em&$data");
            exit;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $em = "Email is not valid";
            header("Location: ../editUserPage.php?error=$em");
        } else if (!emailCheck($email, $conn) && $email != $user['email']){
            $em = "Email used";
            header("Location: ../editUserPage.php?error=$em");
            exit;
        } else {

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
                        // Delete old profile pic
                        $old_pp_des = "../upload/$old_pp";
                        if (unlink($old_pp_des)) {
                            // just deleted
                            move_uploaded_file($tmp_name, $img_upload_path);
                        } else {
                            // error or already deleted
                            move_uploaded_file($tmp_name, $img_upload_path);
                        }


                        // update the Database
                        if (strlen($newPass) > 0) {
                            $newPass = password_hash($newPass, PASSWORD_DEFAULT);
                            
                            $sql = "UPDATE users 
                       SET fname=?, email=?, pp=?, password=?
                       WHERE id=?";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$fname, $email, $new_img_name, $newPass, $id]);

                            header("Location: ../editUserPage.php?id=$id&success=User account has been updated successfully");
                            exit;
                        }
                        

                        $sql = "UPDATE users 
                       SET fname=?, email=?, pp=?
                       WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$fname, $email, $new_img_name, $id]);
                        
                        // $_SESSION['fname'] = $fname;

                        header("Location: ../editUserPage.php?id=$id&success=User account has been updated successfully");
                        exit;

                    } else {
                        $em = "You can't upload files of this type";
                        header("Location: ../editUserPage.php?id=$id&error=$em&$data");
                        exit;
                    }
                } else {
                    $em = "unknown error occurred!";
                    header("Location: ../editUserPage.php?id=$id&error=$em&$data");
                    exit;
                }


            } else {

                if (strlen($newPass) > 0) {
                    
                    $newPass = password_hash($newPass, PASSWORD_DEFAULT);

                    $sql = "UPDATE users 
               SET fname=?, email=?, password=?
               WHERE id=?";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$fname, $email, $newPass, $id]);

                    header("Location: ../editUserPage.php?id=$id&success=User account has been updated successfully");
                    exit;
                }
                
                $sql = "UPDATE users 
       	        SET fname=?, email=?
                WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$fname, $email, $id]);

                header("Location: ../editUserPage.php?id=$id&success=User account has been updated successfully");
                exit;
            }
        }


    } else {
        header("Location: ../editUserPage.php?error=error");
        exit;
    }


} else {
    header("Location: login.php");
    exit;
}