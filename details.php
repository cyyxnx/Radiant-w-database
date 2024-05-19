<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background: #f2f2f2; /* Light gray background */
            background: linear-gradient(90deg, rgba(242,242,242,1) 0%, rgba(253,168,29,1) 53%, rgba(252,155,69,1) 100%); /* Gradient with orange */
            font-family: Arial, sans-serif;
        }
        .wrapper{
            width: 800px;
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
        .table {
            background-color: #fff; /* White background for table */
        }
        table tr th {
            background-color: orange; /* Orange header background */
            color: #fff; /* White text */
        }
        table tr td {
            color: #333; /* Dark text */
        }
        .btn-success {
            background-color: orange; /* Orange button background */
            border: none;
        }
        .btn-success:hover {
            background-color: #e65c00; /* Darker orange on hover */
        }
        .alert-danger {
            background-color: #f8d7da; /* Light red background */
            border-color: #f5c6cb; /* Red border color */
            color: #721c24; /* Dark red text */
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
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <a href="home.php">⬅</a>
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Website User's Details</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New User</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT id, name, address, age, email FROM users";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Age</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['address'] . "</td>";
                                        echo "<td>" . $row['age'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>