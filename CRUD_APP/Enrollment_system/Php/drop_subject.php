<?php
session_start();
include '../../Database/database_config.php';
if (isset($_SESSION['login_id']) and isset($_GET['enrolled_sub_id'])){
    $stud_id = $_GET['stud_id'];
    $stud_subj_id = $_GET['enrolled_sub_id'];
    if (mysqli_query($conn,"DELETE FROM tbl_student_subj_enrollment where stud_subj_id = $stud_subj_id")){
        header("Location:edit_student.php?stud_id=$stud_id");
        exit();
    }
}
header('Location: dashboard.php');
exit();


