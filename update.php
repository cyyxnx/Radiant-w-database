<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $age = $email = $password = "";
$name_err = $address_err = $age_err = $email_err = $password_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter the age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a valid age.";
    } else{
        $age = $input_age;
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
    if(!empty($input_password)){
        if(strlen($input_password) < 6){
            $password_err = "Password must have at least 6 characters.";
        } else{
            $password = password_hash($input_password, PASSWORD_DEFAULT); // Hash the password
        }
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($age_err) && empty($email_err) && empty($password_err)){
        // Prepare an update statement
        if(empty($password)){
            $sql = "UPDATE users SET name=?, address=?, age=?, email=? WHERE id=?";
        } else{
            $sql = "UPDATE users SET name=?, address=?, age=?, email=?, password=? WHERE id=?";
        }
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            if(empty($password)){
                mysqli_stmt_bind_param($stmt, "ssisi", $param_name, $param_address, $param_age, $param_email, $param_id);
            } else{
                mysqli_stmt_bind_param($stmt, "ssisisi", $param_name, $param_address, $param_age, $param_email, $param_password, $param_id);
            }
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_age = $age;
            $param_email = $email;
            $param_id = $id;
            if(!empty($password)){
                $param_password = $password;
            }
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $age = $row["age"];
                    $email = $row["email"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f2f2f2; /* Light gray background */
            background: linear-gradient(90deg, rgba(242,242,242,1) 0%, rgba(253,168,29,1) 53%, rgba(252,155,69,1) 100%); /* Gradient with orange */
            font-family: Arial, sans-serif;
        }
        .wrapper{
            width: 600px;
            margin: 0 auto;
            background-color: white; /* White background */
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
        label {
            color: #ff6600; /* Orange labels */
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
            color: #333; /* Dark text */
            border: 1px solid #ccc; /* Light gray border */
        }
        .form-control {
            border-color: #ccc; /* Light gray border for form controls */
        }
        .form-control.is-invalid {
            border-color: #dc3545; /* Red border for invalid form controls */
        }
        .invalid-feedback {
            color: #dc3545; /* Red text for error messages */
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
        }b 
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the user's record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="details.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
