<?php
// Include config file
require_once "config.php";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Prepare a select statement
    $sql = "SELECT * FROM users WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                // Fetch result row as an associative array
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field values
                $name = $row["name"];
                $age = $row["age"];
                $address = $row["address"];
                $email = $row["email"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
            h1{
                color: orange;
                text-align: center;
                font-weight: bold;
            }
        }
        label {
            color: #ff6600; /* Orange labels */
        }
        p b {
            color: #333; /* Dark text */
        }
        .btn-primary {
            background-color: #ff6600; /* Orange button background */
            border: none;
        }
        .btn-primary:hover {
            background-color: #e65c00; /* Darker orange on hover */
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
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <!-- Display user record here -->
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $name; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <p><b><?php echo $age; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p><b><?php echo $address; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p><b><?php echo $email; ?></b></p>
                    </div>
                    <p><a href="details.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
