<?php
	require_once "authenticate.php";
	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Now we check if the data from the login form was submitted, isset() will check if the data exists.
		if ( !isset($_POST['username'], $_POST['password']) ) {
			// Could not get the data that should have been sent.
			exit('Please fill both the username and password fields!');
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $_POST['username']);
			$stmt->execute();
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
			
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id, $password);
				$stmt->fetch();
				// Account exists, now we verify the password.
				// Note: remember to use password_hash in your registration file to store the hashed passwords.
				if (password_verify($_POST['password'], $password)) {
					// Verification success! User has logged-in!
					// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
					session_regenerate_id();
					$_SESSION['loggedin'] = TRUE;
					$_SESSION['name'] = $_POST['username'];
					$_SESSION['id'] = $id;
					header("location: index.php");
				} else {
					// Incorrect password
					echo 'Incorrect username and/or password!';
				}
			} else {
				// Incorrect username
				echo 'Incorrect username and/or password!';
			}

			$stmt->close();
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="css/login.css" rel="stylesheet" >
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>