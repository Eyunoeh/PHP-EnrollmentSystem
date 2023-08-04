<?php
session_start();
include '../../Database/mysql_query.php';
include '../../Database/database_config.php';
if(isset($_POST['save'])){
    $stud_id = $_GET['stud_id'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $cnum = $_POST['contact_number'];
    $b_date = $_POST['date_of_birth'];
    $email = $_POST['email'];
    $status = null;
    $stud_course = null;
    switch ($_POST['course_choice']){
        case 'com_sci':
            $stud_course = 1;
            break;
        case 'bsbm':
            $stud_course = 2;
            break;
        case 'psychology':
            $stud_course =  3;
    }
    switch ($_POST['status']){
        case 'Regular':
            $status = 1;
            break;
        case 'Irregular':
            $status = 2;
            break;
    }

    if (get_Student_info($stud_id)[5] != $stud_course){
        if(update_stud_info($stud_id,$fname,$lname,$cnum,$b_date,$email,$status,$stud_course)){
            drop_all_subject($stud_id);
            foreach (get_course_subjects_info($stud_course)as $subj_info){
                enroll_course_sub($stud_id,$subj_info[0]);
            }
            echo "<script>alert('Updated!')</script>";
        }
    }
    else{
        update_stud_info($stud_id,$fname,$lname,$cnum,$b_date,$email,$status,$stud_course);
    }
}
?>

<?php

if(isset($_SESSION['login_id'])) {
        if(isset($_GET['stud_id'])){
            $stud_id = $_GET['stud_id'];
            $student_data =get_Student_info($stud_id);
            $enrolment_data = get_enrollments($stud_id);
    }else{
            header("Location: dashboard.php");
        }
}
else{
    header("Location: dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="bg-info">
    <div class="container d-flex align-items-center justify-content-center">
        <div class="col-10">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-head mb-lg-3 mt-lg-3">
                    <a href="dashboard.php" class="btn btn-outline-secondary ml-3" style="font-weight: bold;font-size: 20px;padding-right: 20px;padding-left: 20px">←</a>
                    <a href="student_profile.php?stud_id=<?php echo $stud_id?>" class="btn btn-info" style="margin-left:695px;font-weight: bold;font-size: 20px;padding-right: 20px;padding-left: 20px">Profile</a>
                    <h1 class="text-center font-weight-light my-4">Edit Student</h1>
                    <small class="text-muted ml-3">Date of Enrollment: <u><i><?php echo date("Y-m-d", strtotime($enrolment_data[2])); ?></i></u></small><br>
                    <small class="text-muted ml-3">Enrolled by: <u><i> <?php echo $enrolment_data[0]." ".$enrolment_data[1]?></i></u></small>
                </div>
                <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <label>First name</label>
                                        <input class="form-control" id="inputFirstName" type="text" value="<?php  echo $student_data[0]?>" name="first_name" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <label>Last name</label>
                                        <input class="form-control" id="inputLastName" type="text" value="<?php  echo $student_data[1]?>" name="last_name" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <label>Contact Number</label>
                                        <input value="<?php  echo $student_data[2]?>" class="form-control" id="inputContactNumber" type="text" placeholder="" name="contact_number" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <label>Date of birth</label>
                                        <input class="form-control" id="inputAge" type="date" value="<?php echo $student_data[4]?>" name="date_of_birth" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <label>Email</label>
                                <input class="form-control" id="inputEmail" type="email" name="email" value="<?php  echo $student_data[3]?>" required/>
                            </div>
                            <h5 class="mt-lg-4">Change Status</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="Regular"<?php if($student_data[7] == "Regular") echo"checked"?>>
                                <label class="form-check-label mr-5" for="exampleRadios1">
                                    Regular
                                </label>
                                <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="Irregular"<?php if($student_data[7] == "Irregular") echo"checked"?>>
                                <label class="form-check-label" for="exampleRadios2">
                                    Irregular
                                </label>
                            </div>
                            <div class="form-group">
                                <h5 class="mt-lg-4">Change Course</h5>
                                <select class="form-control" name="course_choice" id="" style="height: 50px">
                                    <option value="com_sci" <?php if ($student_data[6] == "Computer Science") echo "selected"; ?>>Computer Science</option>
                                    <option value="bsbm" <?php if ($student_data[6] == "Business Administration") echo "selected"; ?>>Business Administration</option>
                                    <option value="psychology" <?php if ($student_data[6] == "Psychology") echo "selected"; ?>>Psychology</option>
                                </select>
                            </div>
                            <table class="table table-hover">
                                <thead class="bg-info text-bg-light">
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Name</th>
                                    <th>Professor</th>
                                    <th class="bg-danger"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                stud_enrolled_subj($stud_id)
                                ?>
                                </tbody>
                            </table>
                            <?php
                            $query = "SELECT SUM(total_count) AS total
                                    FROM (
                                    SELECT COUNT(*) AS total_count
                                    FROM tbl_subjects
                                    WHERE course_id = $student_data[5]
                                ) AS subquery";

                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            $total = $row['total'];
                                if($total>get_total_enrolled_sub($stud_id)){
                                    echo "<div class='mb-lg-5'>";
                                    echo '<div class="d-flex align-items-center justify-content-center">';
                                    echo "<a href='add_subject.php?stud_id=".$stud_id."' class='btn btn-info'>Add Subject</a>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            ?>
                            <div class="card-footer text-center py-3">
                                <div class="mt-5">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <input type="submit" class="btn btn-outline-success" name="save" value="Save" style="padding-right: 100px;padding-left: 100px; font-size: 20px">
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div
        </div>
        <section class="mt-lg-5"></section>
    </div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>



