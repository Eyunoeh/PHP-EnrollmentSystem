<?php
session_start();
include '../../Database/mysql_query.php';
include '../../Database/database_config.php';

if(isset($_SESSION['login_id']) and isset($_GET['stud_id'])) {
    $stud_id = $_GET['stud_id'];
    $student_data =get_Student_info($stud_id);
    $enrolment_data = get_enrollments($stud_id);

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
                    <a href="dashboard.php" class="btn btn-outline-secondary ml-3" style="font-weight: bold;font-size: 20px;padding-right: 20px;padding-left: 20px">Dashboard</a>
                    <h1 class="text-center font-weight-light my-4">Student Profile</h1>
                    <small class="text-muted ml-3">Date of Enrollment: <u><i><?php echo date("Y-m-d", strtotime($enrolment_data[2])); ?></i></u></small><br>
                    <small class="text-muted ml-3">Enrolled by: <u><i> <?php echo $enrolment_data[0]." ".$enrolment_data[1]?></i></u></small>
                </div>
                <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <label>First name</label>
                                        <input class="form-control" id="inputFirstName" type="text" value="<?php  echo $student_data[0]?>" name="first_name" disabled/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <label>Last name</label>
                                        <input class="form-control" id="inputLastName" type="text" value="<?php  echo $student_data[1]?>" name="last_name" disabled/>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <label>Contact Number</label>
                                        <input value="<?php  echo $student_data[2]?>" class="form-control" id="inputContactNumber" type="text" placeholder="" name="contact_number"disabled/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <label>Birth Date</label>
                                        <input class="form-control" id="inputAge" type="date" name="date_of_birth"
                                               value="<?php echo $student_data[4]?>" disabled/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <label>Email</label>
                                <input class="form-control" id="inputEmail" type="email" name="email" value="<?php  echo $student_data[3]?>" disabled/>
                            </div>
                            <section>
                                <div class="form-floating mb-3">
                                    <label>Status</label>
                                    <input class="form-control" id="inputEmail" type="text"  value="<?php echo $student_data[7]?>" disabled/>
                                </div>
                                <div class="form-floating mb-3">
                                    <label>Course</label>
                                    <input class="form-control" id="inputEmail" type="text"  value="<?php echo $student_data[6]?>" disabled/>
                                </div>
                            </section>
                            <table class="table table-hover">
                                <thead class="bg-info text-bg-light">
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Name</th>
                                    <th>Professor</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query ="SELECT tbl_subjects.subject_code, tbl_subjects.subject_name,  tbl_teachers.name,tbl_student_subj_enrollment.stud_subj_id
    FROM tbl_students
    JOIN tbl_student_subj_enrollment ON tbl_students.stud_id = tbl_student_subj_enrollment.stud_id
    JOIN tbl_subjects ON tbl_student_subj_enrollment.sub_id = tbl_subjects.sub_id
    JOIN tbl_teachers ON tbl_subjects.prof_id = tbl_teachers.prof_id
    WHERE
    tbl_students.stud_id = $stud_id";
                                $result = mysqli_query($conn,$query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<th>" . $row['subject_code'] . "</th>";
                                    echo "<th>" . $row['subject_name'] . "</th>";
                                    echo "<th>" . $row['name'] . "</th>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="mb-lg-5 mt-5">
                                <div class="d-flex align-items-center justify-content-center">
                                    <input type="submit" class="btn btn-outline-success" name="save" value="Print" style="padding-right: 100px;padding-left: 100px; font-size: 20px">
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



