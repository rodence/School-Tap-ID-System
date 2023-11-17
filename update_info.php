<?php
include 'includes/db_conn.php';



if($_POST["submit"]){

    $user_id = $_POST["id"];
    $phone = $_POST['phone'];

    $old_pass =  $_POST['old_password'];
    $new_pass =  $_POST['new_password'];
    // $old_pass = password_hash($old_pass, PASSWORD_DEFAULT);
    $new_pass_hashed = password_hash($new_pass, PASSWORD_DEFAULT);

    if($new_pass != ""){
        $check_sql = "Select * from users where user_id='".$user_id."' limit 1";
        $check_result = mysqli_query($connection, $check_sql);
    
            if(mysqli_num_rows($check_result) > 0){
                
                while ($user_data = mysqli_fetch_assoc($check_result)) {
                 $password = $user_data["password"];

                    if(password_verify($old_pass,$password )){
                        $sql = "UPDATE users set password ='".$new_pass_hashed."' where user_id='".$user_id."' ";
                        $update_pass = mysqli_query($connection, $sql);





                    }else{
                        echo '
                        <script>alert("Password Incorrect")</script>
                        <script>window.location.href = "view_MyInfo.php"</script>
                        ';
                            exit();
                    }
    



                }

              
            }else{
                echo '
            <script>alert("Password Incorrect")</script>
            <script>window.location.href = "view_MyInfo.php"</script>
            ';
                exit();
            }
    

    }
 



    


    
        $sql = "UPDATE users set phone ='".$phone."' where user_id='".$user_id."' ";
        $sqlResult = mysqli_query($connection, $sql);

        echo '
        <script>alert("Success")</script>
        <script>window.location.href = "view_MyInfo.php"</script>
        ';






}


?>