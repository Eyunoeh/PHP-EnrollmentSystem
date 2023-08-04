<?php
include '../../Database/mysql_query.php';
include '../../Database/database_config.php';
if( isset($_POST['create_account'])){
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $confirm_pass = $_POST['confirm_pass'];
    $new_username = $_POST['username'];
    $temp_pass = $_POST['temp_pass'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $birth_date = $_POST['date_of_birth'];


    foreach (array_keys(fetch_accounts()) as $username) {
        if ($new_username == $username) {
            echo "<script>alert('Username already exists'); window.location.href ='../Template/Registration.html'</script>";
            exit();
        }
    }

    if (strlen($confirm_pass) < 5) {
        echo "<script>alert('Password too short!'); window.location.href ='../Template/Registration.html'</script>";
        exit();
    } elseif ($temp_pass != $confirm_pass) {
        echo "<script>alert('Passwords do not match!'); window.location.href ='../Template/Registration.html'</script>";
        exit();
    }

    $insert2Acc_Statement = "INSERT INTO tbl_accounts (username, password) VALUES ('$new_username', '$confirm_pass')";
    if ($conn->query($insert2Acc_Statement)) {
        if (in_array($new_username, array_keys(fetch_accounts()))) {
            $account = fetch_accounts()[$new_username];
            $account_id = $account['acc_id'];
            $insert2Acc_info_Statement = "INSERT INTO tbl_users (acc_id, first_name, last_name, contact_num, email, birth_date) VALUES ('$account_id', '$firstname', '$lastname', '$contact_number', '$email', '$birth_date')";

            if ($conn->query($insert2Acc_info_Statement)) {
                $conn->close();
                header('Location: ../Template/login.html');
                exit();
            } else {
                echo "Error: " . $insert2Acc_info_Statement . "<br>" . $conn->error;
            }
        }
    }
}
else
    header('Location: ../Template/Registration.html');
exit();
?>





