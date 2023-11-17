<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php' ?>

<body id="page-top">
    <!-- Navigation-->
    <?php


    include 'includes/navbar.php' ?>
    <!-- Masthead-->


    <br>
    <br>
    <br>
    <header class="masthead vh-100">
        <div class="container h-100">
            <div class="row h-100  align-items-center justify-content-center text-center">

                <div style="flex-direction: column" class="d-flex">
                    <img src="img/<?php echo $_SESSION['user_id'] ?>.png" height="250px" width="250px" style="border: 3px solid black" alt="Your QR Will be here">
                    <a href="includes/download.php?file=<?php echo $_SESSION['user_id'] ?>.png" class="btn btn-primary mt-2">Download QR</a>
                </div>
            </div>
        </div>
    </header>
    <?php include 'includes/footer.php' ?>
</body>

</html>