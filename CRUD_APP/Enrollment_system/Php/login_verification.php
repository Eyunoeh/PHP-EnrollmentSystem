<?php
include '../../Database/database_config.php';
include '../../Database/mysql_query.php';
session_start();


if(isset( $_POST['user_name']) and isset($_POST['password'])){
    $login_user_name = $_POST['user_name'];
    $login_password = $_POST['password'];
    $accounts = fetch_accounts();
    $usernames = array_keys($accounts);

    if (in_array($login_user_name, $usernames)) {
        $account = $accounts[$login_user_name];
        $password = $account['password'];
        if ($login_password == $password) {
            $_SESSION['login_id'] = $account['acc_id'];
            header("Location: dashboard.php");

        }else {
            echo "<script>alert('Incorrect password'); window.location.href = '../Template/login.html';</script>";
        }

    } else {
        echo "<script>alert('Incorrect username'); window.location.href = '../Template/login.html';</script>";

    }
}
else{
    header('Location: ../Template/login.html');
}
exit();
?>






