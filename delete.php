<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
    require_once "config.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM users WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = (int)$_POST["id"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: details.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
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
    <title>Delete Record</title>
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
        }
        .alert-danger {
            background-color: #f8d7da; /* Light red background */
            border-color: #f5c6cb; /* Red border color */
            color: #721c24; /* Dark red text */
        }
        .btn-danger {
            background-color: #dc3545; /* Red button background */
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333; /* Darker red on hover */
        }
        .btn-secondary {
            background-color: #fff; /* White button background */
            color: #333; /* Dark text */
            border: 1px solid #ccc; /* Light gray border */
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
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this user record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="details.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
