<?php
include 'includes/db_conn.php';
include 'includes/header.php';
if ($_SESSION['logged_as'] != 'Admin') {
    header("Location: index.php");
    exit();
}
// include 'navbar.php';

$first_name = "";
$last_name = "";
$user_id = "";
$phone = "";
$course = "";
$section = "";
$year = "";
$email = "";
// $semester = "1";
$action = "new";

if (isset($_GET['id'])) {


    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '" . $_GET['id'] . "' ");
    while ($row = mysqli_fetch_assoc($result)) {
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $email = $row["email"];

        $user_id = $row["user_id"];

        $phone = $row["phone"];
        $course = $row["course"];
        $section = $row["section"];
        $year = $row["year"];
        // $semester = $row['semester'];
        $action = "update";
    }
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
                <h1 class="pd-10 mg-0 tx-20">PHINMA AU-South Campus Tap Identification System</h1>
            </div>

        </div>

        <body class="flex-column text-center justify-content-center align-items-center vh-100 bg-light" data-new-gr-c-s-check-loaded="14.1001.0" data-gr-ext-installed="">

            <center>
                <div class="signup-form bg-light" style="width: 50%;">
                    <form class="p-4 mb-3" action="includes/registeration.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
                        <!-- <h2 class="mb-3">School Tap ID System</h2> -->
                        <h2 class="mb-3">Register</h2>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col"><input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" placeholder="First Name" required="required"></div>
                                <div class="col"><input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name" required="required"></div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <?php
                            if ($user_id != "") {

                            ?>
                                <input type="text" class="form-control" id="id" name="id" placeholder="Your ID" value="<?php echo $user_id; ?>" required="required" readonly>
                            <?php
                            } else {

                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select class="form-control" id="user_type">
                                                    <option value='01'>Student</option>
                                                    <option value='02'>Teacher</option>
                                                    <option value='03'>Admin</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" id="id_prefix" name="id_prefix" placeholder="Your ID" value="01-" required="required" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="id" name="id" placeholder="Your ID" value="<?php echo $user_id; ?>" required="required">
                                            </div>



                                        </div>

                                    </div>

                                </div>

                            <?php


                                $disable_id = "";
                            }


                            ?>


                        </div>
                        <div class="form-group mb-3">
                            <?php
                            if ($user_id == "") {
                            ?>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $email; ?>" required="required">
                            <?php
                            } else {
                            ?>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $email; ?>" readonly>
                            <?php
                            }

                            ?>


                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="phone" placeholder="Phone Number" value="<?php echo $phone; ?>" required="required" pattern="[0-9]{11}" title="Please Enter 11 digits Phone Number">
                        </div>
                        <?php
                        if ($user_id == "") {
                            $password = "required";
                        } else {
                            $password = "false";
                        }

                        ?>

                        <div class="form-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" <?php echo $password; ?>>
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" <?php echo $password; ?>>
                        </div>

                        <div style="border: 1px solid gray; border-radius: 10px; padding: 10px;">
                            <h2 style="margin-left: -10px; font-size: 130%;">For students</h2>

                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="course" value="<?php echo $course; ?>" placeholder="Course">
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="section" value="<?php echo $section; ?>" placeholder="Section">
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="year" value="<?php echo $year; ?>" placeholder="Year">
                            </div>
                        </div>
                        <br>
                        <div class="form-group mb-3">
                            <label for="profile_pic">Select profile picture:</label>
                            <input type="file" name="profile_pic" id="profile_pic" onchange="validateFile()" <?php echo $password; ?> class="form-control">
                            <span id="error-msg"></span>
                            <br>
                        </div>
                        <div class="form-group mb-3">
                            <select name="subjects[]" id="subjects" multiple class="form-control">
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
                                <input type="submit" name="submit" value="<?php echo $action; ?>" class="btn btn-primary btn-lg btn-block w-100">
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





                // A $( document ).ready() block.
                $(document).ready(function() {

                    // var semester = $("#semester_value").val();
                    // $("#semester").val(semester).change();

                    var user_id = $("#id").val();

                    if (user_id != "") {
                        $.ajax({
                            method: 'GET',
                            url: 'data_processor.php',
                            data: {
                                type: "get_subjects",
                                user_id: user_id
                            },
                            dataType: 'json',
                            success: function(source) {


                                jQuery.each(source, function(index, item) {
                                    // alert(item["subject"]);
                                    var myValue = item["subject"];
                                    $('#subjects ').children("option[value='" + myValue + "']").prop("selected", true);
                                });




                            }

                        });

                    }

                });
            </script>


        </body>