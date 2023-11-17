<?php
session_start();
if ($_SESSION['logged_as'] != 'Admin') {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_conn.php';
$is_scanned = false;
$id = "";
$name = "";
$date = "";
$time = "";
$lastime = "";
$minutes = '';
$show = false;
$log = '';
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++ SEND SMS ++++++++++++++++++++++++++++++++++++++++

$name = "Please scan your QR code to make a log";
$time = "";
if ((isset($_GET['id']))) {

    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id='$id'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {

        $sql = "SELECT * FROM attendance WHERE student_id = '$id' ORDER BY entry_id DESC LIMIT 1";
        $result2 = mysqli_query($connection, $sql);

        if ($result2) {
            while ($row3 = $result2->fetch_assoc()) {
                date_default_timezone_set("Asia/Manila");
                $lastime = date_create($row3['time']);
                $time = date_create(date("H:i:s"));

                $interval = date_diff($time, $lastime);
                $minutes = $interval->days * 24 * 60;
                $minutes += $interval->h * 60;
                $minutes += $interval->i;
            }
        }
        if (mysqli_num_rows($result2) == 0  or $minutes >= 1) {
            // echo $minutes;
            while ($row = $result->fetch_assoc()) {
                $id = $row['user_id'];
                $name = $row['first_name'] . ' ' . $row['last_name'];
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("g:i A");
            }


            $sql = "SELECT * FROM attendance WHERE student_id = '$id' ORDER BY entry_id DESC LIMIT 1";
            $result = mysqli_query($connection, $sql);

            // $nrows = mysqli_num_rows($result);
            $nrows = mysqli_fetch_assoc($result);

            if ($nrows['date'] == $date) {
                if ($nrows['log'] == 'OUT') {
                    $query = "INSERT INTO `attendance` (`student_id`, `date`, `time`, `log`) VALUES ('$id', '$date','$time', 'IN')";
                    $log = "IN";
                } else {
                    $query = "INSERT INTO `attendance` (`student_id`, `date`, `time`, `log`) VALUES ('$id', '$date','$time', 'OUT')";
                    $log = "OUT";
                }
            } else {
                $query = "INSERT INTO `attendance` (`student_id`, `date`, `time`, `log`) VALUES ('$id', '$date','$time', 'IN')";
                $log = "IN";
            }
            $result = mysqli_query($connection, $query);

            $show = true;
        } else {
            $show = true;
            // $name = "Wait " . (2 - $minutes) . " more minutes.";
            $name = "You Already Signed In \n Please Avoid double tapping";
            $time = '';
        }
    } else {
        $show = true;
        $name = "Invalid QR Code";
        $time = '';
    }
} else {
    $show = false;
}

$show = true;
?>
<style>
    #preview {
        /* add padding top */
        padding-top: 10px;

    }

    h3 {
        font-size: 30px;
    }
</style>

<body id="page-top">
    <!-- Navigation-->
    <?php include 'includes/navbar.php' ?>
    <!-- Masthead-->
    <header class="masthead vh-100">
        <div class="container h-100">
            <div class="row h-100 align-items-center text-center">



                <?php if ($show) { ?>
                    <table style="width: 90%; text-align: center;">

                        <tr>
                            <td style="vertical-align: middle;">
                                <video id="preview" width="400px" height="400px"></video>
                            </td>
                            <td style="vertical-align: middle;">
                                <?php
                                // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ SHOW IMAGE ++++++++++++++++++++++++++++++++++++

                                $i_sql = "SELECT file_content, file_type FROM profile_picture WHERE user_id = '$id'";
                                // echo $id;
                                $i_result = mysqli_query($connection, $i_sql);
                                if (mysqli_num_rows($i_result) > 0) {

                                    $i_row = mysqli_fetch_assoc($i_result);
                                    $i_image_data = $i_row["file_content"];
                                    $i_image_type = $i_row["file_type"];
                                    echo '<img src="data:image/' . $i_image_type . ';base64,' . base64_encode($i_image_data) . '"  width="300" height="300"/>';
                                } else {
                                    echo '<img src="img/scan here.png"  width="300" height="300"/>';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="vertical-align: middle; padding-top: 20px;">
                                <?php
                                if ($log == "IN") {
                                    echo '<h3 style="color: #39FF14; font-size: 40px;">' . $log . '</h3>';
                                } else {
                                    echo '<h3 style="color: red; font-size: 40px;">' . $log . '</h3>';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="vertical-align: middle;">
                                <h2 style="font-size: 50px;"><?php echo $name ?></h2>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="vertical-align: middle;">
                                <h3 style="font-size: 30px;"><?php echo $time ?></h3>
                            </td>
                        </tr>

                    </table>


                <?php } ?>

            </div>
        </div>
    </header>
    <?php include 'includes/footer.php' ?>
    <script src="js/jquery.js"></script>

    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            // alert("asdad");lff---------------------------------------------------------------------------------------
            window.location.href = "scan_qr.php?id=" + content;
            // ---------====================++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // alert("Scanned");

            // +_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    </script>
</body>

</html>