<?php
include 'includes/db_conn.php';
include 'includes/header.php';



?>
<style>
    #form {
        height: 300px;
    }
</style>
<body class="flex-column text-center d-flex justify-content-center align-items-center vh-100 bg-light" data-new-gr-c-s-check-loaded="14.1001.0" data-gr-ext-installed="">
    <div class="signup-form bg-light">
        <form class="p-4 mb-3" action="includes/login_process.php" method="post" id="form">
            <h2 class="mb-3">PAUSC-TIS v1.0</h2>
            <h2 class="mb-3">Log In</h2>

            <input type="email" class="form-control" name="email" placeholder="Email" required="required">
            <br>
            <div class="form-group mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required="required">
            </div>
            </span>

            <?php if (isset($_SESSION['error'])) { ?>
                <span class="text-warning"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']);
                                        } ?></span>
                <div class="form-group mt-4">
                    <input type="submit" name="submit" class="btn btn-primary btn-lg btn-block w-100" value="Sign In">
                </div>
        </form>
    </div>
    <!-- <div class="text-center text-dark">Dont have account? <a class="text-dark" href="signup.php">Sign
            Up</a></div> -->

</body>