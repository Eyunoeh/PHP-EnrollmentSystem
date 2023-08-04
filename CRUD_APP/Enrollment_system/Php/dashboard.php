
<?php
session_start();
include '../../Database/mysql_query.php';
if(isset($_POST['profile_save'])){
    $editFirstname= $_POST['first_name'];
    $editLastname= $_POST['last_name'];
    $editContact_num= $_POST['contact_number'];
    $editEmail= $_POST['email'];
    $editBirth_date= $_POST['date_of_birth'];
    $editProfile_image= $_FILES['profile_image'];
    if(edit_profile($_SESSION['login_id'],$editFirstname,$editLastname,$editContact_num,$editEmail,$editBirth_date,
        upload_image( fetch_users($_SESSION['login_id'])[7],$editProfile_image))){
        echo "<script>alert('Profile Updated');window.location.href='dashboard.php'</script>";
    }
}


?>
<?php
if (isset($_SESSION['login_id'])) {
    $user = fetch_users($_SESSION['login_id']);
    $first_name = $user[2];
    $last_name = $user[3];
    $contact_num = $user[4];
    $email = $user[5];
    $birth_date = $user[6];
    $prof_image_path=$user[7];
}
else{
    header("Location: ../Template/login.html");
    exit();
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<div class="container-fluid">
    <section class="mt-lg-3">
        <div class="tab-container ">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active btn-info" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Students List</button>
                <button class="nav-link btn btn-info" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-enrollStudent" role="tab" aria-controls="v-pills-messages" aria-selected="false">Add Student</button>
                <button class="nav-link btn btn-info" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Profile</button>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card shadow-lg border-0 rounded-lg mt-md-0">
                                <div class="card-header">
                                    <img src="Account_Images/<?php echo isset($prof_image_path) ? $prof_image_path : 'default.png'; ?>" class="rounded float-md-end" alt="User Image" width="150" height="130">
                                    <h5><?php echo $first_name." ".$last_name?></h5>
                                    <small class="text-muted"><?php echo "Birth Date: $birth_date
                                        <br>Age ".calculateAge($birth_date)."<br>";echo $contact_num."<br>".$email;?>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputFirstName" type="text" value="<?php echo $first_name?>"  placeholder="Enter your first name" name="first_name" required/>
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="inputLastName" value="<?php echo $last_name?>" type="text" placeholder="Enter your last name" name="last_name" required/>
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputContactNumber"  type="text" placeholder="ContactNumber" value ="<?php echo $contact_num?>" name="contact_number" required/>
                                                    <label for="inputFirstName">Contact number</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="inputAge" type="date"
                                                           placeholder= "Birthdate" name="date_of_birth"
                                                           value="<?php echo $birth_date?>" required/>
                                                    <label for="inputLastName">Birth Date</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" type="email" value ="<?php echo $email?>"  placeholder="Email"  name="email" required/>
                                            <label for="inputEmail">Email</label>
                                        </div>
                                        <label class="form-label">Upload Profile</label>
                                        <input class="form-control col-4" type="file" name="profile_image">
                                        <div class="mt-lg-5 mb-0">
                                            <div class="d-grid">
                                                <input type="submit" name="profile_save" class="btn btn-info btn-block" value="Save">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="logout.php">Logout</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-container tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="tab-container my-2">
                        <div class="card bg-secondary border-0 mt-2 mb-3" style="border-radius: 20px;">
                            <form method="post">
                                <input name="search" class="a ml-3 form-control-lg" style="width: 500px; outline-color:rgb(52, 58, 64); " type="text" placeholder="Search Student">
                                <button name="submit_search" class="btn btn-dark mb-2 mr-3" style="font-size: 20px;padding-left: 20px;padding-right: 20px">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="scrollable-table">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th></th>
                                    <th>Full Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Enrolled By</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($_POST['submit_search'])){
                                $search= $_POST['search'];
                                search_Student($search);

                            }else{
                                enrolled_list();
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div  class="tab-pane fade" id="v-pills-enrollStudent" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                    <div class="w3-card-3 w3-light-grey">
                        <header class="w3-container w3-dark-grey">
                            <h1 class="text-center w3-margin" style="font-weight: bold">Student Form</h1>
                        </header>
                        <section class="mt-lg-5 ml-5 mr-5 mb-lg-5">
                                <form action="enroll_student.php" method="post">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 mb-md-0">
                                                <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" name="stud_first_name" required/>
                                                <label for="inputFirstName">First name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" name="stud_last_name" required/>
                                                <label for="inputLastName">Last name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 mb-md-0">
                                                <input class="form-control" id="inputContactNumber" type="text" placeholder="Enter your first name" name="stud_contact_number" required/>
                                                <label for="inputFirstName">Contact number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input class="form-control" id="inputAge" type="date" placeholder="Enter your last name" name="stud_date_of_birth" required/>
                                                <label for="inputLastName">Birth Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="inputEmail" type="email" name="stud_email" placeholder="Email" required/>
                                        <label for="inputEmail">Email</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Course</label>
                                        <select class="form-control" name="enroll_course" id="" style="height: 50px">
                                            <option value="com_sci">Computer Science</option>
                                            <option value="bsbm"> Business Administration</option>
                                            <option value="psychology">Psychology</option>
                                        </select>
                                    </div>
                                    <h5 class="mt-lg-4">Status</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="Regular" checked>
                                        <label class="form-check-label mr-5" for="exampleRadios1">
                                            Regular
                                        </label>
                                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="Irregular">
                                        <label class="form-check-label" for="exampleRadios2">
                                            Irregular
                                        </label>
                                    </div>
                                    <div class="form-check">

                                    </div>
                                    <div class="mt-lg-5 mb-5">
                                        <div class="d-grid">
                                            <input type="submit" class="btn btn-success" value="Enroll Student">
                                        </div>
                                    </div>
                                </form>
                                <div class="mt-lg-5">
                                    <footer class="w3-container w3-light-grey"></footer>
                                </div>
                        </section>
                    </div>
                </div>
            </div>
    </section>
</div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>



