<?php

function fetch_accounts(){
    include 'database_config.php';
    $query_statement = $conn->prepare("SELECT * FROM tbl_accounts");
    $query_statement->execute();

    $result = $query_statement->get_result();
    $maps = array();

    while ($row = $result->fetch_assoc()) {
        $maps[$row['username']] = array(
            'acc_id' => $row['acc_id'],
            'password' => $row['password'],
            'date_created' => $row['date_created']
        );
    }
    $query_statement->close();
    $conn->close();
    return $maps;
}

function fetch_users($login_id){
    include 'database_config.php';
    $query_statement = $conn->prepare("SELECT * FROM tbl_users where acc_id =$login_id");
    $query_statement->execute();
    $result = $query_statement->get_result();
    $user_data = array();

    while ($row = $result->fetch_assoc()) {
        $user_data = array($row['acc_id'], $row['user_id'], $row['first_name'], $row['last_name'],
            $row['contact_num'], $row['email'],$row['birth_date'], $row['profile_image_path']);
    }
    $query_statement->close();
    $conn->close();
    return $user_data;
}

function edit_profile($login_id,$first_name,$last_name,$contact_number,$email,$birth_date,$file_name){
    include "database_config.php";
    $query = "UPDATE tbl_users SET first_name= '$first_name', last_name='$last_name', contact_num='$contact_number',
                     email='$email', birth_date='$birth_date', profile_image_path='$file_name' where acc_id =$login_id";
    $result = mysqli_query($conn,$query);
    $conn->close();
    if ($result){
        return true;
    }else{
        return false;
    }
}

function upload_image($image_path,$profile_image){
    if (isset($profile_image) && $profile_image['error'] === UPLOAD_ERR_OK) {
        $mime = mime_content_type($profile_image['tmp_name']);
        if (strpos($mime, 'image') !== false) {
            $sourcePath = $profile_image['tmp_name'];
            $extension = pathinfo($profile_image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . 'Php' . $extension;
            $targetPath = 'Account_Images/' . $filename;
            if (move_uploaded_file($sourcePath, $targetPath)) {
                return $filename;
            }
        }
    }
    return $image_path;
}

function get_course_subjects_info($course_id){
    include 'database_config.php';
    $query = "SELECT tbl_subjects.sub_id, tbl_subjects.subject_name,tbl_subjects.subject_code,
    tbl_teachers.name
    FROM tbl_subjects
    JOIN tbl_courses on tbl_courses.course_id = tbl_subjects.course_id
    JOIN tbl_teachers on tbl_teachers.prof_id = tbl_subjects.prof_id
    WHERE tbl_courses.course_id = $course_id";
    $result = mysqli_query($conn, $query);
    if ($result){
        while ($row = $result->fetch_assoc()){
            $subject = array($row['sub_id'], $row['subject_name'],$row['subject_code'],$row['name']);
            $subjects[] = $subject;
        }
        return $subjects;
    } else {
        return false;
    }
}
function enroll_course_sub($stud_id, $sub_id){
    include 'database_config.php';
    $query = "INSERT INTO tbl_student_subj_enrollment SET stud_id =$stud_id, sub_id = $sub_id";
    $result = mysqli_query($conn,$query);
    if ($result){
        return true;
    }
    echo "Query execution failed: " . mysqli_error($conn);
    return false;
}

function calculateAge($birthdate) {
    $today = new DateTime();
    $diff = $today->diff(new DateTime($birthdate));
    return $diff->y;
}

function enrolled_list(){
    include 'database_config.php';
    $query_statement = $conn->prepare("SELECT tbl_students.stud_id, tbl_students.first_name, tbl_students.last_name,
    tbl_courses.course, tbl_status.status, tbl_enrollments.enrollment_date,
    tbl_users.first_name AS user_fname, tbl_users.last_name AS user_lname
FROM tbl_students
JOIN tbl_courses ON tbl_courses.course_id = tbl_students.course_id
JOIN tbl_status ON tbl_status.status_id = tbl_students.status_id
JOIN tbl_enrollments ON tbl_enrollments.stud_id = tbl_students.stud_id
JOIN tbl_users ON tbl_users.user_id = tbl_enrollments.user_id
ORDER BY tbl_students.course_id desc ");
    $query_statement->execute();
    $result = $query_statement->get_result();
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='bg-body'><a class='d-flex align-items-center justify-content-center btn btn-info' href='edit_student.php?stud_id=" . $row['stud_id'] . "'>EDIT</td>";
        echo "<td class='bg-body'> <a href='student_profile.php?stud_id=" . $row['stud_id'] . "'>"  . $row['first_name'] . " " . $row['last_name'] . "</td>";
        echo "<td class='bg-body'>" . $row['course'] . "</td>";
        echo "<td class='bg-body'>" . $row['status'] . "</td>";
        echo "<td class='bg-body'>" . $row['user_fname']." ".$row['user_lname']. "</td>";
        echo "<td class='bg-body'><a class='d-flex align-items-center justify-content-center btn btn-danger btn-xs' href='drop_student.php?stud_id=".$row['stud_id']."'>Remove</a>";
        echo "</tr>";
    }
    $conn->close();
}
function search_Student($search){
    include 'database_config.php';

    $search = mysqli_real_escape_string($conn, $search);

    $query_statement = "SELECT tbl_students.stud_id, tbl_students.first_name, tbl_students.last_name,
    tbl_courses.course, tbl_status.status, tbl_enrollments.enrollment_date,
    tbl_users.first_name AS user_fname, tbl_users.last_name AS user_lname
FROM tbl_students
JOIN tbl_courses ON tbl_courses.course_id = tbl_students.course_id
JOIN tbl_status ON tbl_status.status_id = tbl_students.status_id
JOIN tbl_enrollments ON tbl_enrollments.stud_id = tbl_students.stud_id
JOIN tbl_users ON tbl_users.user_id = tbl_enrollments.user_id
WHERE  tbl_students.stud_id = '$search'
    OR tbl_students.first_name LIKE '%$search%'
    OR tbl_students.last_name LIKE '%$search%'
    OR tbl_courses.course LIKE '%$search%'
    OR tbl_status.status = '$search'
    OR tbl_users.first_name LIKE '%$search%'
    OR tbl_users.last_name LIKE '%$search%'
ORDER BY tbl_students.course_id desc
";

    $result = mysqli_query($conn, $query_statement);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='bg-body'><a class='d-flex align-items-center justify-content-center btn btn-info' href='edit_student.php?stud_id=" . $row['stud_id'] . "'>EDIT</td>";
                echo "<td class='bg-body'> <a href='student_profile.php?stud_id=" . $row['stud_id'] . "'>"  . $row['first_name'] . " " . $row['last_name'] . "</td>";
                echo "<td class='bg-body'>" . $row['course'] . "</td>";
                echo "<td class='bg-body'>" . $row['status'] . "</td>";
                echo "<td class='bg-body'>" . $row['user_fname']." ".$row['user_lname']. "</td>";
                echo "<td class='bg-body'><a class='d-flex align-items-center justify-content-center btn btn-danger btn-xs' href='drop_student.php?stud_id=".$row['stud_id']."'>Remove</a>";
                echo "</tr>";
            }
            $conn->close();
        }
    }
}



function get_Student_info($stud_id){
    include 'database_config.php';

    $query = $conn->prepare("SELECT tbl_students.first_name, tbl_students.last_name, tbl_students.contact_number,
     tbl_students.email, tbl_students.date_of_birth, tbl_courses.course_id,tbl_courses.course,tbl_status.status 
     FROM tbl_students
     JOIN tbl_courses on tbl_courses.course_id = tbl_students.course_id
     JOIN tbl_status on tbl_status.status_id = tbl_students.status_id
     where tbl_students.stud_id = $stud_id");
    $query->execute();
    $result = $query->get_result();
    $array_student_data = array();
    while ($row = $result->fetch_assoc()){
        $array_student_data = array(
            $row['first_name'], $row['last_name'], $row['contact_number'],
            $row['email'], $row['date_of_birth'], $row['course_id'],$row['course'], $row['status']
        );
    }
    $conn->close();
    return $array_student_data;
}


function get_enrollments($stud_id){
    include 'database_config.php';
    $query = $conn->prepare("SELECT tbl_users.first_name, tbl_users.last_name, tbl_enrollments.enrollment_date 
    from tbl_enrollments
    JOIN tbl_users on tbl_users.user_id = tbl_enrollments.user_id where tbl_enrollments.stud_id = $stud_id");
    $query->execute();
    $result = $query->get_result();
    $array_enrollment_data = array();
    while ($row = $result->fetch_assoc()){
        $array_enrollment_data = array(
            $row['first_name'], $row['last_name'], $row['enrollment_date']
        );
    }
    return $array_enrollment_data;

}



function stud_enrolled_subj($stud_id){
    include 'database_config.php';
    $query = $conn->prepare("SELECT tbl_subjects.subject_code, tbl_subjects.subject_name,  tbl_teachers.name,tbl_student_subj_enrollment.stud_subj_id
    FROM tbl_students
    JOIN tbl_student_subj_enrollment ON tbl_students.stud_id = tbl_student_subj_enrollment.stud_id
    JOIN tbl_subjects ON tbl_student_subj_enrollment.sub_id = tbl_subjects.sub_id
    JOIN tbl_teachers ON tbl_subjects.prof_id = tbl_teachers.prof_id
    WHERE
    tbl_students.stud_id = ?;");
    $query->bind_param("i", $stud_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<th>".$row['subject_code']."</th>";
        echo "<th>".$row['subject_name']."</th>";
        echo "<th>".$row['name']."</th>";
        echo "<th><a class='text-danger' href='drop_subject.php?enrolled_sub_id=". $row['stud_subj_id']."&stud_id=".$stud_id."'>Drop</a></th>";
        echo "</tr>";
    }
    $conn->close();
}
function get_total_enrolled_sub($stud_id){
    include 'database_config.php';
    $query = "SELECT * FROM tbl_student_subj_enrollment where stud_id = $stud_id";
    return mysqli_num_rows(mysqli_query($conn,$query));
}
function update_stud_info($stud_id, $fname, $lname, $cnum, $b_date, $email, $status, $course){
    include 'database_config.php';
    $query = "UPDATE tbl_students SET course_id=$course, status_id=$status, first_name='$fname', last_name='$lname',contact_number='$cnum',
    email='$email', date_of_birth='$b_date' where stud_id=$stud_id";
    $result = mysqli_query($conn,$query);
    $conn->close();
    if ($result){
        return true;
    }else{
        return false;
    }
}
function drop_all_subject($stud_id) {
    include 'database_config.php';
    $query = "DELETE FROM tbl_student_subj_enrollment WHERE stud_id = $stud_id";
    $result = mysqli_query($conn, $query);
    $conn->close();

    if ($result) {
        return true;
    }
    echo "Query execution failed: " . mysqli_error($conn);
    return false;
}
function deleteEnrollment($stud_id){
    include 'database_config.php';
    $delete_enrollment = "DELETE FROM tbl_enrollments where stud_id = $stud_id";
    $enrollment_res = mysqli_query($conn,$delete_enrollment);
    if ($enrollment_res){
        $conn->close();
        return true;

    }
    $conn->close();
    return false;
}
function deleteStudent($stud_id){
    include 'database_config.php';
    $delete_stud_tble = "DELETE FROM tbl_students where stud_id = $stud_id";
    $stud_tbl_res = mysqli_query($conn, $delete_stud_tble);
    if ($stud_tbl_res) {
        $conn->close();
        return true;
    }
    die("Query Fail");
}
?>
