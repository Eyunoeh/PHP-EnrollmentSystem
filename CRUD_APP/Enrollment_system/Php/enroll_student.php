<?php
include '../../Database/database_config.php';
include '../../Database/mysql_query.php';
session_start();


$stud_course = null;
$status = null;

switch ($_POST['status']){
    case 'Regular':
        $status = 1;
        break;
    case 'Irregular':
        $status = 2;
        break;
}

switch ($_POST['enroll_course']){
    case 'com_sci':
        $stud_course = 1;
        break;
    case 'bsbm':
        $stud_course = 2;
        break;
    case 'psychology':
        $stud_course =  3;
}

if(isset($status) and isset($stud_course)){
    $stud_first_name = $_POST['stud_first_name'];
    $stud_last_name = $_POST['stud_last_name'];
    $stud_contact_number = $_POST['stud_contact_number'];
    $stud_birth_date = $_POST['stud_date_of_birth'];
    $stud_email = $_POST['stud_email'];
    $query = "INSERT INTO tbl_students SET course_id=$stud_course, status_id= $status, first_name='$stud_first_name',
last_name='$stud_last_name', contact_number='$stud_contact_number',email='$stud_email', date_of_birth='$stud_birth_date'";
    $tbl_stud_result = mysqli_query($conn,$query);
    if ($tbl_stud_result){
        $user_id= $_SESSION['login_id'];
        $student_id = mysqli_insert_id($conn);
        foreach (get_course_subjects_info($stud_course) as $subj_info){
            $sub_id = $subj_info[0];
            enroll_course_sub($student_id,$sub_id);
        }
        $tbl_stud_enrollments = "INSERT INTO tbl_enrollments (user_id, stud_id) value ($user_id, $student_id)";
        if($conn->query($tbl_stud_enrollments)){
            echo "<script>alert('Student Enrolled');window.location.href='dashboard.php'</script>";
            $conn ->close();
            exit();
        }
        else{
            die("Error".$conn->connect_error);
        }
    }
}
else{
    header("Location: dashboard.php");
}





