<?php 

function getUserById($id, $db){
    $sql = "SELECT * FROM users WHERE id = ?";
	$stmt = $db->prepare($sql);
	$stmt->execute([$id]);
    
    if($stmt->rowCount() == 1){
        $user = $stmt->fetch();
        return $user;
    }else {
        return 0;
    }
}

function getUsers($db){
    $sql = "SELECT * FROM users WHERE user_type = 'user'";
	$stmt = $db->prepare($sql);
	$stmt->execute();

    $users = $stmt->fetchAll();
    return $users;
}

function adminExistCheck($db){
    $sql = "SELECT * FROM users WHERE user_type = 'admin'";
	$stmt = $db->prepare($sql);
	$stmt->execute();

    $count = $stmt->fetchColumn();
    return $count != 0;
}

function emailCheck($email, $db){
    $sql = "SELECT * FROM users WHERE email = ?";
	$stmt = $db->prepare($sql);
	$stmt->execute([$email]);

    $count = $stmt->fetchColumn();
    return $count == 0;
}
 ?>


