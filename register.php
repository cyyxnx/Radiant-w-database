<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $age = $email = $password = $confirm_password = "";
$name_err = $address_err = $age_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter your address.";
    } else{
        $address = trim($_POST["address"]);
    }

    // Validate age
    if(empty(trim($_POST["age"]))){
        $age_err = "Please enter your age.";
    } elseif(!ctype_digit(trim($_POST["age"]))){
        $age_err = "Please enter a valid age.";
    } else{
        $age = trim($_POST["age"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a valid email address.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($age_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, address, age, email, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_age, $param_email, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_age = $age;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
                exit(); // Add exit to prevent further execution
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
    <title>Sign Up</title>
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
            background-color: white; 
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin: 50px auto;
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
            color: #f37329;
        }
        .form-control {
            border: 1px solid #f37329;
            border-radius: 5px;
            padding: 8px;
        }
        .form-control:focus {
            border-color: #f37329;
            box-shadow: 0 0 0 0.2rem rgba(243, 115, 41, 0.25);
        }
        .btn-primary {
            background-color: #f37329;
            border-color: #f37329;
        }
        .btn-primary:hover {
            background-color: #e25e1b;
            border-color: #e25e1b;
        }
        .btn-secondary {
            background-color: #fff;
            border-color: #f37329;
            color: #f37329;
        }
        .btn-secondary:hover {
            background-color: #f9f9f9;
            color: #e25e1b;
            border-color: #e25e1b;
        }
        .invalid-feedback {
            color: #dc3545;
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
        <h2><b>Sign Up</b></h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                <span class="invalid-feedback"><?php echo $age_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php"class="sign-up-link">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>
