<?php
session_start();
include('../dB/config.php');

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT `userId`, `firstName`, `lastName`, `email`, `password`, `phoneNumber`, `gender`, `birthday`, `verification`, `role` FROM `users` WHERE email = '$email' AND password = '$password' LIMIT 1";
    $query_run = mysqli_query($conn, $query);

    if ($query_run){
        if(mysqli_num_rows($query_run) > 0){
            $data = mysqli_fetch_assoc($query_run);

            $userId = $data['userId'];
            $fullName = $data['firstName'] . ' ' . $data['lastName'];
            $emailAddress = $data['email'];
            $userRole = $data['role'];
            
            $_SESSION['auth'] = true;
            $_SESSION['role'] = $userRole;
            $_SESSION['authUser'] = [
                'userId' => $userId,
                'fullName' => $fullName,
                'emailAddress' => $emailAddress,
            ];

            if($userRole == 'admin'){
                header('Location: ../view/admin/index.php');
            }elseif($userRole == 'user'){
                header('Location: ../view/user/index.php');
            }else{
                header('Location: ../login.php');
            }
            exit();
        }else{
            $_SESSION['message'] = 'Invalid Credentials';
            $_SESSION['code'] = 'error';
            header('Location: ../login.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'There is something wrong';
        $_SESSION['code'] = 'error';
        header('Location: ../login.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Please login';
    $_SESSION['code'] = 'error';
    header('Location: ../login.php');
    exit();
}
?>