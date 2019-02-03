<?php 
//ensure admin is logged in
if(!isset($_SESSION['user']) && $_SESSION['user_type'] != 1){    
    header('Location: forbidden.php');
    exit;
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

    <title>Admin Page</title>
</head>
<body>

<div class="container" style="margin-top: 100px;">
	<div class="row justify-content-center">
		<div class="col-md-6 col-md-offset-3" align="center">

			<div class="border border-primary" style="padding: 80px;">
				<h2>Welcome admin. your work is protected!</h2><br>
			</div>

		</div>		
	</div>	
</div>

</body>
</html>
