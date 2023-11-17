<?php

session_start();
// if (!isset($_SESSION['reg_email']) || !isset($_SESSION['stid'])) {
//     header("Location: index.php");
//     exit();
// }

include 'includes/db_conn.php';
include 'includes/header.php';
// include 'navbar.php';

$first_name = "";
$last_name = "";
$user_id = "";
$phone = "";
$course = "";
$section = "";
$year = "";
$email = "";

if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $id = $_POST['id'];
    $u_id = $id;
    $id = strtoupper($id);
    $password = $_POST['password'];
    // $cPass = $_POST['confirm_password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $year = $_POST['year'];
    $course = $_POST['course'];
    $section = $_POST['section'];
    // $semester = $_POST['semester'];

    $file_name = $_FILES["profile_pic"]["name"];
    $file_type = $_FILES["profile_pic"]["type"];
    $file_size = $_FILES["profile_pic"]["size"];
    $file_content = file_get_contents($_FILES["profile_pic"]["tmp_name"]);

    if (isset($_POST['subjects'])) {
        $selected_subjects = $_POST['subjects'];
    }


    $insertQuery = "INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `phone`, `password`, `course`, `section`, `year`, `registered_as`)
                    VALUES ('$id', '$first_name', '$last_name', '$email', '$phone', '$hash', '$course', '$section', '$year', 'Student')";

    $result = mysqli_query($connection, $insertQuery);
    if ($result) {
        include('includes/qr_maker.php');


        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ PICTURE +++++++++++++++++++++++++++
        if (isset($_FILES['profile_pic'])) {
            // Get the file info
            $file_name = mysqli_real_escape_string($connection, $file_name);
            $file_type = mysqli_real_escape_string($connection, $file_type);
            $file_size = mysqli_real_escape_string($connection, $file_size);
            $file_content = mysqli_real_escape_string($connection, $file_content);

            $insertQuery = "INSERT INTO `profile_picture` (`user_id`, `file_name`, `file_type`, `file_size`, `file_content`)
            VALUES('$u_id', '$file_name', '$file_type', '$file_size', '$file_content')";

            $result = mysqli_query($connection, $insertQuery);

            if (!$result) {
                // handle error
            }

            // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++ SUBJECTS +++++++++++++++++++++++++++++++++++

            if (isset($_POST['subjects'])) {
                foreach ($selected_subjects as $subject) {
                    $insertQuery = "INSERT INTO `user_subject` (`user_id`, `subject`)
                                VALUES('$id', '$subject')";
                    $result = mysqli_query($connection, $insertQuery);
                    if (!$result) {
                        echo '
                                        <script>alert("We are facing some error while inserting user-subjects. Please Try again")</script>
                                        <script>window.location.href = "./register_student.php"</script>
                                    ';
                        // exit;
                    }
                }
            }

            session_destroy();
            // session_destroy('stid');
            echo '
            <script>alert("Your Account has been created Successfully.")</script>
            <script>window.location.href = "./index.php"</script>
            ';
        } else {
            echo '
            <script>alert("We are facing some error. Please Try again")</script>
            <script>window.location.href = "./register_student.php"</script>
            ';
        }
    }  // INSERT SUCCESS


    //emailresult true
    //result true

}




?>
<div class="page-inner">
    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!--================================-->
        <!-- Breadcrumb Start -->
        <!--================================-->
        <div class="pageheader pd-t-25 pd-b-35">
            <div class="pd-t-5 pd-b-5">
                <h1 class="pd-10 mg-0 tx-20" style="font-size: 4vw;">PHINMA AU-South Campus<br>Tap Identification System</h1>
            </div>
        </div>

    </div>

    <body class="flex-column text-center justify-content-center align-items-center vh-100 bg-light" data-new-gr-c-s-check-loaded="14.1001.0" data-gr-ext-installed="">

        <center>
            <div class="signup-form bg-light" style="width: 50%;">
                <form class="p-4 mb-3" action="register_student.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
                    <!-- <h2 class="mb-3">School Tap ID System</h2> -->
                    <h2 class="mb-3">Student Register</h2>
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col"><input type="text" class="form-control" name="first_name" placeholder="First Name" required="required"></div>
                            <div class="col"><input type="text" class="form-control" name="last_name" placeholder="Last Name" required="required"></div>
                        </div>
                    </div>
                    <div class="form-group mb-3">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">


                                    <div class="col-md-12">
                                        <label for="id" style="display: inline-block; width: 100px; text-align: right !important;">Student ID:</label>
                                        <input type="text" class="form-control" id="id" name="id" placeholder="01-1234-12345" required="required" value="<?php echo $_SESSION['stid']; ?>" readonly>
                                    </div>

                                </div>

                            </div>

                        </div>




                    </div>
                    <div class=" form-group mb-3">

                        <input type="email" class="form-control" name="email" placeholder="Email" readonly value="<?php echo $_SESSION['reg_email']; ?>">



                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="phone" placeholder="Phone Number eg. 09xxxxxxxxx" required="required" pattern="[0-9]{11}" title="Please Enter 11 digits Phone Number">
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <script>
                            const togglePassword = document.querySelector('#togglePassword');
                            const password = document.querySelector('#password');
                            togglePassword.addEventListener('click', function(e) {
                                // toggle the type attribute
                                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                password.setAttribute('type', type);
                                // toggle the eye slash icon
                                this.querySelector('i').classList.toggle('fa-eye-slash');
                            });
                        </script>





                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="course" placeholder="Course" required="required">
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="section" placeholder="Section" required="required">
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="year" placeholder="Year" required="required">
                        </div>

                        <br>
                        <div class="form-group mb-3">
                            <label for="profile_pic">Select profile picture:</label>
                            <input type="file" name="profile_pic" id="profile_pic" onchange="validateFile()" class="form-control" required="required">
                            <span id="error-msg"></span>
                            <br>
                        </div>
                        <div class="form-group mb-3">
                            <select name="subjects[]" id="subjects" multiple class="form-control" required>
                                <!-- slkdfjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj -->
                                <?php
                                $sql = "SELECT subject FROM subjects";
                                $result = mysqli_query($connection, $sql);

                                // Loop through the results and create an option for each subject
                                while ($row = $result->fetch_assoc()) {
                                    $subject = $row['subject'];
                                    echo "<option value=\"$subject\">$subject</option>";
                                }
                                // Close the database connection
                                ?>
                        </div>
                        <?php if (isset($_SESSION['error'])) { ?>
                            <span class="text-warning">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        } ?></span>
                            <div class="form-group mt-4">
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-lg btn-block w-100">
                            </div>
                </form>
            </div>
        </center>
        <!-- <div class="text-center text-black">Already have an account? <a class="text-black" href="login.php">Sign
            in</a></div> -->




        <script>
            $("#user_type").on("change", function() {
                $("#id_prefix").val($(this).val() + "-")


            });


            function validateFile() {
                // Get the selected file
                var fileInput = document.getElementById("profile_pic");
                var file = fileInput.files[0];

                // Check that a file has been selected
                if (!file) {
                    document.getElementById("error-msg").innerHTML = "Please select a file";
                    fileInput.setCustomValidity("Please select a file");
                    return;
                } else {
                    fileInput.setCustomValidity("");
                }

                // Check that the file is a PNG or JPG image
                if (file.type !== "image/png" && file.type !== "image/jpeg") {
                    document.getElementById("error-msg").innerHTML = "File must be a PNG or JPG image";
                    fileInput.setCustomValidity("File must be a PNG or JPG image");
                    return;
                } else {
                    fileInput.setCustomValidity("");
                }

                // Check that the file size is less than 1MB
                if (file.size > 1 * 1024 * 1024) {
                    document.getElementById("error-msg").innerHTML = "File size must be less than 1MB";
                    fileInput.setCustomValidity("File size must be less than 1MB");
                    return;
                } else {
                    fileInput.setCustomValidity("");
                }

                // If all validation checks pass, clear any error messages
                document.getElementById("error-msg").innerHTML = "";
            }
        </script>


    </body>