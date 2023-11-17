<?php include 'includes/header.php' ?>

<body id="page-top">
  <!-- Navigation-->
  <?php include 'includes/navbar.php' ?>
  <!-- Masthead-->
  <br>
  <br>
  <br>
  <br>
  <header class="masthead vh-100">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-10 align-self-end">
          <h1 class="text-uppercase black font-weight-bold">
            <?php
            if (isset($_SESSION['loggedin'])) {
              echo '
            Welcome to the Dashboard<br>' . $_SESSION['name'] . '
            ';
            } else {
              echo '
            Welcome to the <br> PHINMA AU-South Campus Tap Identification System
            ';
            }
            ?>
          </h1>
          <hr class="divider my-4" />
        </div>
        <div class="col-lg-8 align-self-baseline">
          <?php
          if (isset($_SESSION['loggedin'])) {
            echo '
            <p class="text-white-75 font-weight-light mb-5">
            Now you can access all the features of the website.
          </p>';
            if ($_SESSION['logged_as'] == 'Student' || $_SESSION['logged_as'] == 'Teacher') {
              echo '
          <a class="btn btn-primary btn-xl js-scroll-trigger" href="view_qr.php">View QR Code</a>
            ';
            } else {
              echo '
          <a class="btn btn-primary btn-xl js-scroll-trigger" href="dash2.php">Dashboard</a>
            ';
            }
          } else {
            echo '
          <p class="text-white-75 font-weight-light mb-5">
            We help students and teachers to take attendance using QR Code. <br> Please Login now to access all the features.
          </p>
          <a class="btn btn-primary btn-xl js-scroll-trigger" href="login.php">Sign In</a>
          ';
          }
          ?>
        </div>
      </div>
    </div>
  </header>
  <?php include 'includes/footer.php' ?>
</body>

</html>