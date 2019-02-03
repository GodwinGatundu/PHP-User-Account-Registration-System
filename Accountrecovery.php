<?php 
//check if user already logged in 
if(isset($_SESSION['user'])){
    header('Location: loginpage.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
include('functions.php');

if(isset($_POST['email'])){

	//DB connection
	$con = new mysqli('localhost','db_user','db_password','db_database');

	$email = $con->real_escape_string($_POST['email']);

	//check if email entered is in DB
  	$sql = $con->query("SELECT email FROM usr_t WHERE email='$email'"); 

  if ($sql->num_rows > 0) {

  	//generate new password
    $newPassword = generateRandomString();
    //hash pasword to be stored in user table
    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();
    $mail->addAddress($email);
    $mail->setFrom("support@companyemail.com", "Some Company");
    $mail->Subject = "Account Password Recovery";
    $mail->isHTML(true);
    $mail->Body = "
        Greetings,<br><br>

        We are pleased to inform you that your password recovery is successful.<br><br>

        Your new password is: ".$newPassword."<br><br>If you didnt not request a recovery of your account password, please go to your account and change your password immediately. Thank you!<br><br>

        Kind Regards,<br><br>
        Support.";

    if ($mail->send()) {

      //insert record into account password reset table
       $sql = "INSERT INTO `passwordreset` (`id`, `user`, `time_reset`) VALUES (NULL, '$email', current_timestamp())";
       $con->query($sql);     

      //update user table with new hashed password
      $con->query("UPDATE usr_t SET password = '$newPasswordHash' WHERE email = '$email'");

      exit(json_encode(array("status" => 1, "msg" => 'Please check your email inbox!')));

    } else {
      exit(json_encode(array("status" => 0, "msg" => 'Something went wrong!')));
    }
    
  } else {
    exit(json_encode(array("status" => 0, "msg" => 'Sorry! Email not found!')));
  }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>User Account Recovery</title>
</head>
<body>
	
<!-- Reset Password Form -->
<div class="container" style="margin-top: 100px;">
  <div class="row justify-content-center">
    <div class="col-md-6 col-md-offset-3" align="center">
       
      <div class="border border-primary" style="padding: 80px;">
        <h2>Reset Password</h2><br>
          <label>Enter your email address: </label>
          <input class="form-control" id="email" placeholder="Your Email Address..."><br>
          <input type="button" class="btn btn-primary form-control"  value="Reset Password">
          <br><br>
          <p id="response"></p>
        </div> 

    </div>    
  </div>  
</div>
<script
        src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
        <script type="text/javascript">
          var email = $("#email");

          $(document).ready(function () {
            $('.btn-primary').on('click', function () {
              if (email.val() != "") {
                email.css('border', '1px solid green');

                $.ajax({
                  url: 'Accountrecovery.php',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                    email: email.val()
                  }, success: function (response) {
                    if (!response.success)
                      $("#response").html(response.msg).css('color', "green");
                    else
                      $("#response").html(response.msg).css('color', "red");
                  
                  }
                });

              } else {
                email.css('border', '1px solid red');
              }

            });

          });
        </script>
</body>
</html>