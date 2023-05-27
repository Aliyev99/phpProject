<?php
 @include 'php/User.php';
 @include 'db_conn.php';

if (isset($_GET['remove'])){
    $userId = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['remove']);

    $sql = "DELETE FROM users WHERE Id=?";
       	$stmt = $conn->prepare($sql);
       	$stmt->execute([$userId]);
}


if (isset($_GET['disable'])){
    $userId = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['disable']);

    $sql = "UPDATE users
        SET isActive = false
        WHERE Id=?";
       	$stmt = $conn->prepare($sql);
       	$stmt->execute([$userId]);
}

if (isset($_GET['enable'])){
    $userId = preg_replace('/[^a-zA-Z0-9-]/', '', (int)$_GET['enable']);

    $sql = "UPDATE users
        SET isActive = true
        WHERE Id=?";
       	$stmt = $conn->prepare($sql);
       	$stmt->execute([$userId]);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

    <div class="container mt-5">

    <?php 
        include 'navbar.php';
    ?>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Full Name</th>
                <th class="text-center">Email address</th>
                <th class="text-center">Status</th>
                <th width='25%' class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
           

            $allUser = getUsers($conn);

            if ($allUser) {
                $i = 0;
                foreach ($allUser as $user) {
                    $i++;

                    ?>
                    <tr class="text-center">
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td>
                            <?php echo $user['fname']; ?>
                        </td>
                        <td>
                            <?php echo $user['email']; ?> <br>
                        <td>
                            <?php echo $user['user_type']; ?> <br>
                    

                        <td>
                        <?php if ($user['isActive'] == '0') { ?>
                                <a onclick="return confirm('Are you sure To enable ?')" class="btn btn-secondary btn-sm "
                                    href="?enable=<?php echo $user['id']; ?>">Enable</a>

                            <?php } elseif ($user['isActive'] == '1') { ?>
                                <a onclick="return confirm('Are you sure To disable ?')" class="btn btn-warning btn-sm "
                                    href="?disable=<?php echo $user['id']; ?>">Disable</a>
                            <?php } ?>

                            <a class="btn btn-info btn-sm " href="editUserPage.php?id=<?php echo $user['id'];?>">Edit</a>


                            <a onclick="return confirm('Are you sure To Delete ?')" class="btn btn-danger btn-sm "
                                href="?remove=<?php echo $user['id']; ?>">Remove</a>
                        </td>
                            

                            
                    </tr>




                <?php }
            } else { ?>
                <tr class="text-center">
                    <td>No user availabe now !</td>
                </tr>
            <?php } ?>


        </tbody>

    </table>
    </div>

</body>

</html>