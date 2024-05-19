<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to home page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $password = "";
$name_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if name is empty
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter name.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($name_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, name, password FROM users WHERE name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Set parameters
            $param_name = $name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $name, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;                            
                            
                            // Redirect user to home page
                            header("location: home.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            background: rgb(242,242,242);
            background: linear-gradient(90deg, rgba(242,242,242,1) 0%, rgba(253,168,29,1) 53%, rgba(252,155,69,1) 100%);
        }
        .wrapper { 
            width: 360px; 
            padding: 20px; 
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin: 50px auto;
            margin-top: 100px;
            background-color: #ffffff; /* White background */
        }
        .wrapper h2{
            color: orange;
            font-family: sans-serif;
            font-size: 35px;
            font-style: bold;
            text-align: center;
        }
        .wrapper p{
            text-align: center;
            color: orange;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #f37329; /* Orange label color */
        }
        .form-control {
            border: 1px solid #f37329; /* Orange border */
            border-radius: 5px;
            padding: 8px;
        }
        .form-control:focus {
            border-color: #f37329; /* Orange border on focus */
            box-shadow: 0 0 0 0.2rem rgba(243, 115, 41, 0.25); /* Orange box shadow on focus */
        }
        .btn-primary {
            background-color: #f37329; /* Orange button background */
            border-color: #f37329; /* Orange button border */
        }
        .btn-primary:hover {
            background-color: #e25e1b; /* Darker orange on hover */
            border-color: #e25e1b; /* Darker orange border on hover */
        }
        .invalid-feedback {
            color: #dc3545; /* Red error message */
        }
        .sign-up-link {
            color: #f37329; /* Light orange color */
            transition: color 0.3s; /* Smooth transition effect */
        }
        .sign-up-link:hover {
            color: orange; /* Orange color on hover */
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <h2><b>Login</b></h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php" class="sign-up-link">Sign up now</a>.</p>

        </form>
    </div>
