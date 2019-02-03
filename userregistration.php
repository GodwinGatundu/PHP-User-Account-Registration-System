<!DOCTYPE html>
<html lang="en">
<head>
	  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="">

	<title>User Registration</title>
</head>

<body>
<!-- Register user Form -->
<div class="container" style="margin-top: 10px;">
	<div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-3" align="center">

			<div class="border border-primary" style="padding: 20px;">
				<h2>User Registration</h2><br>
			<form method ="post" action="userregister.php">
				<label>First name: </label><input class="form-control" name="fname" type="text" placeholder="Enter your name..."><br>
				<label>Surname: </label><input class="form-control" name="sname" type="text" placeholder="Your Surname..."><br>
				<label>Email: </label><input class="form-control" name="email" type="email" placeholder="Your email address..."><br>
				<label>Password: </label><input class="form-control" name="password" type="password" placeholder="Your password..."><br>
				<label>Confirm password: </label><input class="form-control" name="cpassword" type="password" placeholder="Confirm your password..."><br>
				<input class="btn btn-primary form-control" name="submit" type="submit" value="Register"><br>
				<a href="loginpage.php">Already a member? click here to login</a>
			</form>
			</div>

			<?php
			
			use PHPMailer\PHPMailer\PHPMailer;

			//alert messages stored in variables
			$error = '<div class="alert alert-danger"><strong>Passwords</strong> entered do not match</div>';
			$success = '<div class="alert alert-success" style="padding-top: 20px;">You have registered your account successfully</div>';
			$user_exists = '<div class="alert alert-danger">User account already exists.</div>';
			$passwords_not_match = '<div class="alert alert-danger">Passwords entered don\'t match.</div>';
			$input_fields = '<div class="alert alert-danger">Please fill in all the fields.</div>';
			$email_error = '<div class="alert alert-danger">email not sent</div>';


			//DB connection
			$con = new PDO("mysql:host=localhost;dbname=db_database", "db_user", "db_password");
		    //set the PDO error mode to exception
		    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    
		    if (isset($_POST['submit'])) {
		        
		        if ($_POST['fname'] == '' || $_POST['sname'] == '' || $_POST['email'] == '' || $_POST['password'] == '' || $_POST['cpassword'] == '') {
		    		//no input entered in fields
		    		echo $input_fields;
		    	} else {
		        //check if email is already registered
		    	$stmt = $con->prepare("SELECT count(*) FROM usr_t WHERE email = ?");
		        $stmt->bindParam(1, $_POST['email']);
		        $stmt->execute();
		        $rows = $stmt->fetchColumn();

		        if ($rows > 0) {
		        	//entry found in DB
		        	echo $user_exists;

		        } else if($_POST['password'] == " " && $_POST['cpassword'] == " ") {

		        	//no passwords entered
		        	echo $input_fields;

		        } else if ($_POST['password'] == $_POST['cpassword']) {

		        	//no entry in DB
		        	//and passwords entered match
		        	try{	
			    		$password = $_POST['password'];
				    	$hashpassword = password_hash($password, PASSWORD_BCRYPT);

				    	$stmt = $con->prepare("insert into usr_t(fname,sname,email,password) values(?,?,?,?)");
						$stmt->bindParam(1, $_POST['fname']);
	                    $stmt->bindParam(2, $_POST['sname']);
	                    $stmt->bindParam(3, $_POST['email']);
	                    $stmt->bindParam(4, $hashpassword);
	                    $stmt->execute();
	                    
	                    require_once "PHPMailer/PHPMailer.php";
                        require_once "PHPMailer/Exception.php";
                		
                		//email 
                        $mail = new PHPMailer();
                        $mail->addAddress($_POST['email']);
                        $mail->setFrom("support@companyemail.com", "Some Company");
                        $mail->Subject = "Account Created";
                        $mail->isHTML(true);
                        $mail->Body = "
                            Hi ".$_POST['fname'].",<br><br>

                            Welcome onboard. Thank you for choosing us.<br><br>
                            
                            Check the writer's page for orders you might want to start on.<br><br>
                            
                            We look forward to seeing your work. Feel free to check our support page if you have any issues or concerns.<br><br>
                
                            Kind Regards,<br><br>
                
                            Support.
                        ";
                        if ($mail->send()) {
                            echo $success;
                        } else {
                            echo $email_error;
                        }
	                    
		    		} catch(PDOException $e){
		    			echo "Entry Failed: " . $e->getMessage();
		    		}
		        } else {

		        	echo $passwords_not_match;
		        }
		    	}
		    }		    	
			$con = null;
			?>			
		</div>		
	</div>	
</div>

</body>
</html>