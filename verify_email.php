<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
session_start();
include 'includes/db_conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//Load Composer's autoloader
require 'vendor/autoload.php';

$error_msg = '';
$error2_msg = '';
$vcode = "";



if (isset($_POST["Send"])) {
    $stid_pre = htmlspecialchars($_POST['id-prefix']);
    $stid = htmlspecialchars($_POST['id-prefix'] . $_POST['id']);
    $email = htmlspecialchars($_POST['au_mail']);
    $csql = "SELECT * FROM users WHERE user_id = '$stid'";
    $_SESSION['reg_type'] = $_POST['reg_type'];

    $result = mysqli_query($connection, $csql);
    if (mysqli_num_rows($result) > 0) {
        // Stid already exists, return error
        $error_msg = "Student ID already exists";
    } else {
        // Stid does not exist, check email
        $csql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $csql);
        if (mysqli_num_rows($result) > 0) {
            // Email already exists, return error
            $error_msg =  "Student ID or Email already exists";
        } else {
            $mail = new PHPMailer(true);

            try {
                //Enable verbose debug output
                $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;

                //Send using SMTP
                $mail->isSMTP();

                //Set the SMTP server to send through
                $mail->Host = 'smtp.gmail.com';

                //Enable SMTP authentication
                $mail->SMTPAuth = true;

                //SMTP username
                $mail->Username = 'PAUSCTIS@gmail.com';

                //SMTP password
                $mail->Password = 'dbzurbjyhidfdhfb';

                //Enable TLS encryption;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('PAUSCTIS@gmail.com', 'Tap ID');

                //Add a recipient
                $mail->addAddress($email);

                //Set email format to HTML
                $mail->isHTML(true);

                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $vcode = $verification_code;

                $mail->Subject = 'Email verification';
                $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

                $mail->send();

                $_SESSION['verification_code'] = $verification_code;
                $error_msg =  'Verification code sent to your Phinma email.';
            } catch (Exception $e) {
                $error_msg =  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }


    // $name = $_POST["name"];
    // echo "slfksdjfsdkjd";
    // $email = htmlspecialchars($_POST["au_mail"]);
    // $_SESSION['reg_email'] = $email;
    // $password = $_POST["password"];

    //Instantiation and passing `true` enables exceptions

}

function verifyCode($code)
{
    if (!isset($_SESSION['verification_code'])) {
        return false;
    }

    $vcode = $_SESSION['verification_code'];
    unset($_SESSION['verification_code']);
    return $code == strval($vcode);
}



if (isset($_POST['Submit'])) {
    $code = htmlspecialchars($_POST["v_code"]);
    if (verifyCode($code)) {
        $_SESSION['reg_email'] = $_POST['au_mail_2'];
        $_SESSION['stid'] = $_POST['id_2'];

        $regType = $_SESSION['reg_type'];
        if ($regType == 'Student') {
            header("Location: register_student.php");
            exit();
        } elseif ($regType == 'Teacher') {
            header("Location: register_teacher.php");
            exit();
        } else {
            // Invalid registration type, handle the error
            $error2_msg = "Invalid registration type.";
        }
    } else {
        // Code is not valid, show error message
        $error2_msg = "Invalid code, please try again.";
        // echo $_SESSION['verification_code'];
    }
}








?>

<!DOCTYPE html>
<html>

<head>
    <title>PAUSC-TIS v1.0</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            background-color: #f7f7f7;
            box-shadow: 0px 0px 10px #ccc;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="email"],
        input[type="text"],
        select {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
        }


        input[type="submit"] {
            background-color: #006de2;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }

        /* Add these styles for invalid inputs */
        .invalid-input {
            background-color: #ffdddd;
            color: #d60000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 style="text-align:center;">PAUSC-TIS v1.0</h1>
        <form action="verify_email.php" method="POST">
            <h3 style="text-align:center;">Choose registration type and enter Student/Teacher ID and email for verification:</h3>

            <label for="reg_type">Registration Type:</label><br>
            <select name="reg_type" id="reg_type" required>
                <option value="Student" <?php echo (isset($_POST['Send']) && $stid_pre == '01-') || !isset($_POST['Send']) ? 'selected' : ''; ?>>Student</option>
                <option value="Teacher" <?php echo (isset($_POST['Send']) && $stid_pre == '02-') ? 'selected' : ''; ?>>Teacher</option>
            </select>


            <label id="id-label" for="id" style="display: inline-block; width: 100px; text-align: center !important;">Student ID:</label>
            <div style="display: inline-block;">
                <input type="text" class="form-control" id="id-prefix" name="id-prefix" readonly style="width: 15%; margin-left: 5px;">
                <input type="text" class="form-control" id="id" name="id" placeholder="1234-12345" required="required" style="width: 82%;" value="<?php echo isset($_POST['Send']) ? htmlspecialchars($_POST['id']) : ''; ?>">



            </div>

            <script>
                // const idInput = document.getElementById("id");
                const idPrefixInput = document.getElementById("id-prefix");
                const regTypeSelect = document.getElementById("reg_type");
                const idLabel = document.getElementById("id-label");

                regTypeSelect.addEventListener('change', function() {
                    const selectedValue = regTypeSelect.value;
                    if (selectedValue === "Student") {
                        idPrefixInput.value = "01-";
                        // idInput.placeholder = "1234-12345";
                        // idInput.pattern = "^\\d{4}-\\d{5}$";
                        // idInput.setCustomValidity('Please enter a valid Student ID in the format 1234-12345');
                        idLabel.textContent = "Student ID:";
                    } else if (selectedValue === "Teacher") {
                        idPrefixInput.value = "02-";
                        // idInput.placeholder = "1234-12345";
                        // idInput.pattern = "^\\d{4}-\\d{5}$";
                        // idInput.setCustomValidity('Please enter a valid Teacher ID in the format 1234-12345');
                        idLabel.textContent = "Teacher ID:";
                    }

                });

                // Set initial values and label based on the default selection
                const defaultSelectedValue = regTypeSelect.value;
                if (defaultSelectedValue === "Student") {
                    idPrefixInput.value = "01-";
                    idLabel.textContent = "Student ID:";
                } else if (defaultSelectedValue === "Teacher") {
                    idPrefixInput.value = "02-";
                    idLabel.textContent = "Teacher ID:";
                }

                idInput.addEventListener('input', function() {
                    if (idInput.checkValidity()) {
                        idInput.setCustomValidity('');
                    } else {
                        idInput.setCustomValidity(idInput.validationMessage);
                    }
                });
            </script>


            <label for="au_mail">Phinma email:</label><br>
            <input type="email" name="au_mail" id="au_mail" placeholder="example@phinmaed.com" pattern="^[a-zA-Z0-9._%+-]+@phinmaed\.com$" required <?php if (isset($_POST["Send"])) echo "value='" . $email . "'" ?>>


            <input type="submit" value="Send" name="Send">
            <?php echo $error_msg; ?>
        </form>


        <form action="verify_email.php" method="POST">
            <label for="v_code">Type the code here that is sent in your email:</label>
            <input type="text" id="v_code" name="v_code" placeholder="Verification code" required>
            <input type="email" name="au_mail_2" id="au_mail_2" <?php if (isset($_POST['Send'])) echo "value='" . $email . "'" ?> style="display:none;">
            <input type="text" name="id_2" id="id_2" <?php if (isset($_POST['Send'])) echo "value='" . $stid . "'" ?> style="display:none;">
            <input type="submit" value="Submit" name="Submit">
            <?php echo $error2_msg; ?>
        </form>
    </div>

    <script>
        const emailInput = document.getElementById('au_mail');
        const codeInput = document.getElementById('v_code');

        emailInput.addEventListener('input', function() {
            if (emailInput.validity.valid) {
                emailInput.classList.remove('invalid-input');
            } else {
                emailInput.classList.add('invalid-input');
            }
        });

        codeInput.addEventListener('input', function() {
            if (codeInput.validity.valid) {
                codeInput.classList.remove('invalid-input');
            } else {
                codeInput.classList.add('invalid-input');
            }
        });
    </script>
</body>

</html>