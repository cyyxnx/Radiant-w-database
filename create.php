<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $age = $address = $email = $password = "";
$name_err = $age_err = $address_err = $email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter an age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a valid age.";
    } else{
        $age = $input_age;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email address.";     
    } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a valid email address.";
    } else{
        $email = $input_email;
    }

    // Validate password
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Please enter a password.";     
    } elseif(strlen($input_password) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = password_hash($input_password, PASSWORD_DEFAULT); // Hash the password
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($age_err) && empty($address_err) && empty($email_err) && empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, age, address, email, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_age, $param_address, $param_email, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_age = $age;
            $param_address = $address;
            $param_email = $email;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: details.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f2f2f2; /* Light gray background */
            background: linear-gradient(90deg, rgba(242,242,242,1) 0%, rgba(253,168,29,1) 53%, rgba(252,155,69,1) 100%); /* Gradient with orange */
            font-family: Arial, sans-serif;
        }
        .wrapper {
            width: 600px;
            margin: 0 auto;
            background-color: white; /* White background for the form */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Soft shadow effect */
            margin-top: 50px;
            h2{
                color: orange;
                text-align: center;
                font-weight: bold;
            }
            p{
                color: orange;
                text-align: center;
            }
        }
        h2 {
            color: #ff6600; /* Orange text */
            margin-bottom: 20px;
        }
        .form-group label {
            color: #ff6600; /* Orange labels */
        }
        .form-control {
            border: 1px solid #ff6600; /* Orange border for input fields */
        }
        .btn-primary {
            background-color: #ff6600; /* Orange button background */
            border: none;
        }
        .btn-primary:hover {
            background-color: #e65c00; /* Darker orange on hover */
        }
        .btn-secondary {
            background-color: #fff; /* White button background */
            color: #ff6600; /* Orange button text */
            border: 1px solid #ff6600; /* Orange border */
        }
        .btn-secondary:hover {
            background-color: #f2f2f2; /* Light gray on hover */
        }
                /* Responsive styles */
                @media (max-width: 768px) {
            .wrapper {
                width: 95%; /* Adjusted width for smaller screens */
            }
        }

        @media (max-width: 576px) {
            .wrapper {
                width: 100%; /* Full width on extra small screens */
            }
        }

</style>

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add user's record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>