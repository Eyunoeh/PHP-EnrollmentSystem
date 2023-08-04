<?php
session_start();
include '../../Database/mysql_query.php';
include '../../Database/database_config.php';
if (isset($_SESSION['login_id']) && isset($_GET['stud_id'])){
    $stud_id = $_GET['stud_id'];
    $course_id = get_Student_info($_GET['stud_id'])[5];
    $course_sub_id = array();
    $stud_enrolled_sub_id = array();

    foreach (get_course_subjects_info($course_id) as $sub_info){
        $course_sub_id[] = $sub_info[0];
    }
    $query = "SELECT sub_id FROM tbl_student_subj_enrollment where stud_id = $stud_id";
    $result = mysqli_query($conn, $query);
    while ($row = $result->fetch_assoc()) {
        $stud_enrolled_sub_id[] = $row['sub_id'];
    }

    foreach ($course_sub_id as $courseSub_id) {
        if (!in_array($courseSub_id, $stud_enrolled_sub_id)){
            enroll_course_sub($stud_id,$courseSub_id);
            header("Location: edit_student.php?stud_id=$stud_id");
            exit();
        }
    }

}
else{
    header('Location: dashboard.php');
    exit();
}








