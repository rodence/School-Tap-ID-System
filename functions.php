<?php include 'includes/header.php';
include 'includes/db_conn.php';
$is_scanned = false;
$id = "";
$name = "";
$date = "";
$time = "";
$lastime = "";
$minutes = '';
$show = false;

if ((isset($_GET['id']))) {

    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id='$id'";
    $result = mysqli_query($connection, $sql);

    if ($result) {

        $sql = "SELECT * FROM attendance WHERE student_id = '$id' ORDER BY entry_id DESC LIMIT 1";
        $result2 = mysqli_query($connection, $sql);

        if ($result2) {
            while ($row3 = $result2->fetch_assoc()) {
                date_default_timezone_set("Asia/Kolkata");
                $lastime = date_create($row3['time']);
                $time = date_create(date("H:i:s"));

                $interval = date_diff($time, $lastime);
                $minutes = $interval->days * 24 * 60;
                $minutes += $interval->h * 60;
                $minutes += $interval->i;
            }
        }
        if (!$result2   or $minutes >= 2) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['user_id'];
                $name = $row['first_name'] . ' ' . $row['last_name'];
                date_default_timezone_set("Asia/Kolkata");
                $date = date("Y-m-d");
                $time = date("H:i:s");
            }
            $sql = "SELECT * FROM attendance WHERE student_id = '$id'";

            $result = mysqli_query($connection, $sql);

            $nrows = mysqli_num_rows($result);

            if (($nrows % 2) == 0) {
                $query = "INSERT INTO `attendance` (`student_id`, `name`, `date`, `time`, `status`, `log`) VALUES ('$id', '$name', '$date','$time', 'Present', 'IN')";
            } else {
                $query = "INSERT INTO `attendance` (`student_id`, `name`, `date`, `time`, `status`, `log`) VALUES ('$id', '$name', '$date','$time', 'Present', 'OUT')";
            }
            $result = mysqli_query($connection, $query);

            $show = true;
        } else {
            $show = true;
            $name = "Wait " . (2 - $minutes) . " more minutes.";
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

?>

<body id="page-top">
    <!-- Navigation-->
    <?php include 'includes/navbar.php' ?>
    <!-- Masthead-->
    <header class="masthead vh-100">
        <div class="container h-100">
            <div class="row h-100 align-items-center text-center">
                <div style="flex-direction: column" class="d-flex">
                    <video id="preview" width="400px" height="400px"></video>
                </div>
                <?php if ($show) { ?>
                    <h2><?php echo $name; ?></h2><br>
                    <h3><?php echo $time ?></h3>
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
            window.location.href = "functions.php?id=" + content;
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