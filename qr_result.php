<!DOCTYPE html>
<?php include 'includes/header.php' ?>

<body id="page-top">
    <!-- Navigation-->
    <?php include 'includes/navbar.php' ?>
    <!-- Masthead-->
    <header class="masthead vh-100">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center">


                <?php
                include 'includes/db_conn.php';
                if (isset($_GET['id'])) {
                    $student_id = $_GET['id'];
                    $sql = "SELECT * FROM users WHERE user_id='$student_id'";
                    $result = mysqli_query($connection, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['user_id'];
                            $name = $row['first_name'] . ' ' . $row['last_name'];
                            date_default_timezone_set("Asia/Kolkata");
                            $date = date("Y-m-d");
                            $time = date("H:i:s");
                ?>
                            <form action="includes/insert_attendance.php" class="w-50" method="POST">
                                <label for="id" class="text-white">Student Id</label>
                                <input type="text" name="id" readonly="" value="<?php echo $id ?>" class="form-control">
                                <label for="name" class="mt-3 text-white">Student Name</label>
                                <input type="text" name="name" readonly="" value="<?php echo $name ?>" class="form-control">
                                <label for="date" class="mt-3 text-white">Date</label>
                                <input type="text" name="date" readonly="" value="<?php echo $date ?>" class="form-control">
                                <?php
                                if ($_SESSION['logged_as'] == 'Teacher') {
                                ?>
                                    <label for="time" class="mt-3 text-white">Time</label>
                                    <input type="text" style="margin-bottom: 30px;" name="time" readonly="" value="<?php echo $time ?>" class="form-control">

                                    <input type="submit" name="submit" value="Mark as Present" class="btn btn-primary mt">
                                <?php
                                } else if ($_SESSION['logged_as'] == 'Librarian') {
                                ?>
                                    <label for="book_id" class="mt-3 text-white">Enter Book ID</label>
                                    <input type="text" style="margin-bottom: 30px;" name="book_id" class="form-control">
                                    <label for="due_date" class="mt-3 text-white">Enter Due Date</label>
                                    <input type="date" style="margin-bottom: 30px;" name="due_date" class="form-control">

                                    <input type="submit" name="submit" value="Submit Book" class="btn btn-primary mt">
                                <?php
                                }
                                ?>
                            </form>
            </div>
<?php
                        }
                    }
                }
?>
        </div>
        </div>
    </header>
    <?php include 'includes/footer.php' ?>
</body>

</html>