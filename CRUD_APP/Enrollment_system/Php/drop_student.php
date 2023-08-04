<?php
session_start();
include '../../Database/mysql_query.php';
include '../../Database/database_config.php';

if (isset($_SESSION['login_id'])) {
    if (isset($_GET['stud_id'])) {
        $stud_id = $_GET['stud_id'];
        if (deleteEnrollment($stud_id)) {
            if(drop_all_subject($stud_id)){
                if (deleteStudent($stud_id)){
                    header("Location: dashboard.php");
                    exit();
                }
            }
        }
    }
}
header("Location: dashboard.php");
exit();

