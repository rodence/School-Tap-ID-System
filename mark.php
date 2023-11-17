<?php
include 'includes/db_conn.php';

$sql = "SELECT * FROM users;";

$sqlResult = mysqli_query($connection, $sql);

if ($_POST['mark']) {
    foreach ($_POST['mark'] as $student_id => $value) {
        echo $student_id . '<br>';
        // 1. SELECT * FROM users WHERE user_id = $student_id
        // 2. INSERT INTO attendace VALUES ()
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="" method="post">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Present</th>
                </tr>
            </thead>

            <?php

            if ($sqlResult) {
                if (mysqli_num_rows($sqlResult) == '0') {
                    echo '<h1>No Result found</h1>';
                }
                $sno = 0;
                while ($attendance_row = mysqli_fetch_assoc($sqlResult)) {
                    $student_id = $attendance_row['user_id'];
                    $student_name = $attendance_row['first_name'] . $attendance_row['last_name'];
                    $sno++;

            ?>
                    <tr>
                        <td><?php echo $sno ?></td>
                        <td><?php echo $student_name ?></td>
                        <td> <input type="checkbox" name="mark[<?php echo $student_id ?>]" value="Present"></td>

                    </tr>

            <?php
                }
            }

            ?>


            <tbody>








            </tbody>

        </table>
        <input type="submit" value="submit">
    </form>

</body>

</html>