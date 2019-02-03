<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<title>Login Page</title>
</head>
<body>
<!-- Login Form -->
<div class="container" style="margin-top: 100px;">
  <div class="row justify-content-center">
    <div class="col-md-6 col-md-offset-3" align="center">

      <div class="border border-primary" style="padding: 80px;">
        <h2>User Login Form</h2><br>
      <form method ="post" action="loginpage.php">
        <label>Email: </label><input class="form-control" name="email" type="email" placeholder="Your email address..."><br>
        <label>Password: </label><input class="form-control" name="password" type="password" placeholder="Your password..."><br>
        <input class="btn btn-primary form-control" name="submit" type="submit" value="Login"><br>
      </form>
      <div style="padding: 10px;">
        <a href="http://pd.webcreator.co.ke/userregister.php" style="padding-right: 20px;"></a>
        <a href="http://pd.webcreator.co.ke/forgotpassword.php">Forgot your password?</a>   
      </div>
      </div> 
<!-- End of Login Form -->

<?php
    //session info
    session_start();

    //DB connection
	$con = new PDO("mysql:host=localhost;dbname=db_database", "db_user", "db_password");
    //set the PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    if (isset($_POST['submit'])) {

        $stmt = $con->prepare("SELECT * FROM usr_t WHERE email = ?");
        $stmt->bindParam(1, $_POST['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();
        
        
        if ($rows > 0) {
            if ($user && password_verify($_POST['password'], $user['password'])){
              //in the database, you'll have a user_type field that will have the integer '1' to signify the user is an admin
              //user with an empty value, that is a normal user
              if (!$user['type'] == 1 || $user['type'] == " ") {
                
                //session info
                $_SESSION['role'] = $user['type'];
                $_SESSION['user']= $user['email'];
    
                //redirect to users page
                header('Location: users.php');          
              } else {
    
                //session info
                $_SESSION['user'] = $user['email'];
                $_SESSION['role'] = $user['type'];
    
                //redirect to admin page
                header('Location: admin.php');
              }
              
            } else {
               //redirect to forbidden page
               header('Location: forbidden.php');
            }
        	
		} else {
		    //no entry found in DB
        	echo '<div class="alert alert-danger">User account not found!</div>';
		}
    }         
    $con = null; 
	?> 
    </div>    
  </div>  
</div>

</body>
</html>