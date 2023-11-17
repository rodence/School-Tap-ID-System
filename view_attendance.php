<!DOCTYPE html>
<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_conn.php';
// session_start();
// $mode = "";

$button_b = "btn btn-secondary";
$button_a = "btn btn-primary";

if ($_SESSION['logged_as'] == 'Admin') { //+++++++++++++++++++++++++++++++ADMIN
    if (isset($_POST['mode_button'])) {
        $mode = $_POST['mode_button'];
        $_SESSION['admin_mode'] = $mode;
    } elseif (isset($_SESSION['admin_mode'])) {
        $mode = $_SESSION['admin_mode'];
    } else {
        $mode = "Students Attendance";
    }

    if ($mode == "Students Attendance") {
        $button_b = "btn btn-secondary";
        $button_a = "btn btn-primary";
    } else {
        $button_a = "btn btn-secondary";
        $button_b = "btn btn-primary";
    }
} elseif ($_SESSION['logged_as'] == 'Teacher') { //+++++++++++++++++++++++++++++++TEACHER
    if (isset($_POST['mode_button'])) {
        $mode = $_POST['mode_button'];
        $_SESSION['teacher_mode'] = $mode;
    } elseif (isset($_SESSION['teacher_mode'])) {
        $mode = $_SESSION['teacher_mode'];
    } else {
        $mode = "My Attendance";
    }

    if ($mode == "Mark Class") {
        $button_a = "btn btn-secondary";
        $button_c = "btn btn-secondary";
        $button_b = "btn btn-primary";
        $button_d = "btn btn-secondary";
    } elseif ($mode == "My Attendance") {
        $button_b = "btn btn-secondary";
        $button_a = "btn btn-primary";
        $button_c = "btn btn-secondary";
        $button_d = "btn btn-secondary";
    } elseif ($mode == "Report") {
        $button_b = "btn btn-secondary";
        $button_a = "btn btn-secondary";
        $button_c = "btn btn-secondary";
        $button_d = "btn btn-primary";
    } else {
        $button_b = "btn btn-secondary";
        $button_a = "btn btn-secondary";
        $button_c = "btn btn-primary";
        $button_d = "btn btn-secondary";
    }
} elseif ($_SESSION['logged_as'] == 'Student') { //+++++++++++++++++++++++++++++++STUDENT
    // if (isset($_POST['mode_button'])) {
    //     $mode = $_POST['mode_button'];
    // } else {
    //     $mode = "Campus Attendance";
    // }

    // if ($mode == "Campus Attendance") {
    //     $button_a = "btn btn-secondary";
    //     $button_b = "btn btn-primary";
    // } else {
    //     $button_b = "btn btn-secondary";
    //     $button_a = "btn btn-primary";
    // }
}

?>

<body id="page-top">
    <!-- Navigation-->
    <?php include 'includes/navbar.php' ?>
    <!-- Masthead-->
    <header class="masthead vh-100 vw-100">
        <div class="container h-100">
            <div class="h-100 justify-content-center bg-white">
                <br>



                <!-- <h2 class="text-center m-2">View Attendance</h2> -->
                <?php
                if ($_SESSION['logged_as'] == 'Teacher') {
                ?><center>
                        <br>
                        <br>

                        <form method="post">
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button class="<?php echo $button_a ?>" type="submit" name="mode_button" value="My Attendance" style="width:24%;">My Attendance</button>
                                <button class="<?php echo $button_b ?>" type="submit" name="mode_button" value="Mark Class" style="width:24%;">Mark Class</button>
                                <button class="<?php echo $button_c ?>" type="submit" name="mode_button" value="View Mark" style="width:24%;">View Mark</button>
                                <button class="<?php echo $button_d ?>" type="submit" name="mode_button" value="Report" style="width:24%;">Report</button>
                            </div>
                        </form>
                        <br>
                        <h2 class="text-center m-2"><?php echo $mode ?></h2>
                    </center>
                    <br>
                    <?php

                    if ($mode == "My Attendance") {
                        include 'includes/attendance_teacher.php';
                    } elseif ($mode == "Mark Class") {
                        include 'includes/attendance_teacher_mark.php';
                    } elseif ($mode == "View Mark") {
                        include 'includes/attendance_teacher_past.php';
                    } else {
                        include 'includes/attendance_teacher_report.php';
                    }
                } elseif ($_SESSION['logged_as'] == 'Admin') {
                    ?>


                    <!-- add padding below -->
                    <center>
                        <form method="post">
                            <!-- add padding top 20 -->
                            <br>
                            <br>
                            <br>
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button class="<?php echo $button_a ?>" type="submit" name="mode_button" value="Students Attendance" style="width:40%;">Students</button>
                                <button class="<?php echo $button_b ?>" type="submit" name="mode_button" value="Teachers Attendance" style="width:40%;">Teachers</button>
                            </div>
                        </form>
                        <br>
                        <h2 class="text-center m-2"><?php echo $mode ?></h2>


                        <br>
                    </center>

                <?php

                    if ($mode == "Students Attendance") {
                        include 'includes/attendance_admin_student.php';
                    } else {
                        include 'includes/attendance_admin_teacher.php';
                    }
                } elseif ($_SESSION['logged_as'] == 'Student') {

                    if (isset($_GET['as'])) {
                        if ($_GET['as'] == "campus") {

                            include 'includes/attendance_student.php';
                        } elseif ($_GET['as'] == "class") {
                            include 'includes/attendance_student_class.php';
                        } else {
                            include 'includes/attendance_student.php';
                        }
                    } else {
                        include 'includes/attendance_student.php';
                    }
                }

                ?>