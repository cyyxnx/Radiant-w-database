<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <style>
        <style>
            .footer {
    background-color: #f37329; /* Orange background color */
    color: white; /* White text color */
    padding: 20px 0; /* Padding for top and bottom */
    position: fixed;
    bottom: 0;
    width: 100%;
                }
        h1{
    text-align: center;
    margin-top: 10%;
    color: white;
    font-size: 65px;
            }
    .footer {
            background-color: #bd904f;
            color: white;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            right: 0;
            width: 100%;
            text-align: right
        }

        .footer a {
            color: white;
            text-decoration: none;
            background-color: white; /* White background color for buttons */
            color: #bd904f; /* Orange text color for buttons */
            border: 2px solid #bd904f; /* Orange border for buttons */
            padding: 10px 20px; /* Padding for buttons */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
            display: inline-block;
            margin-right: 30px;
        }

        .footer a:hover {
            color: #f7a05a;
        }

.footer a.btn {
    background-color: white; /* White background color for buttons */
    color: #bd904f;; /* Orange text color for buttons */
    border: 2px solid #bd904f; /* Orange border for buttons */
    padding: 10px 20px; /* Padding for buttons */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition */
    text-align: right;
}

.footer a.btn:hover {
    background-color: #bd904f; /* Orange background color on hover */
    color: white; /* White text color on hover */
}

.moving-text {
  height: 100%;
  text-align: center;
  color: white;
  font-size: 20px;
  
  /* animation properties */
  -moz-transform: translateY(-100%);
  -webkit-transform: translateY(-100%);
  transform: translateY(-100%);
  
  -moz-animation: my-animation 5s linear infinite;
  -webkit-animation: my-animation 5s linear infinite;
  animation: my-animation 5s linear infinite;
}

/* for Firefox */
@-moz-keyframes my-animation {
  from { -moz-transform: translateY(-100%); }
  to { -moz-transform: translateY(100%); }
}

/* for Chrome */
@-webkit-keyframes my-animation {
  from { -webkit-transform: translateY(-100%); }
  to { -webkit-transform: translateY(100%); }
}

@keyframes my-animation {
  from {
    -moz-transform: translateY(-100%);
    -webkit-transform: translateY(-100%);
    transform: translateY(-100%);
  }
  to {
    -moz-transform: translateY(100%);
    -webkit-transform: translateY(100%);
    transform: translateY(100%);
  }
}
.text {
            color: darkorange;
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

        .text {
            color: darkorange;
        }
        .password-modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            justify-content: center; 
            align-items: center; 
        }
        .password-modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="header">
        <div class="logo" onclick="openPasswordModal()" style="cursor: pointer;">
            <img src="assets/logo1.png" alt="Radiant Gourmet Hub Logo">
        </div>
        <nav>
            <a href="home.php"><b>Home</b></a> &#8226; 
            <a href="about.html">About</a> &#8226; 
            <a href="recipe.html">Recipes</a> &#8226; 
            <a href="contact.html">Contact</a> &#8226;
        </nav>
    </div>

    <h1 class="my-5">Hi, <b class="text"><?php echo htmlspecialchars($_SESSION["name"]); ?></b>. <br>
    Welcome to Radiant Gourmet Hub.</h1>
    <p class="moving-text">Explore our wide range of delicious recipes and learn how to cook your favorite dishes.</p>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <a href="logout.php" class="btn">Logout</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="password-modal" id="passwordModal">
        <div class="password-modal-content">
            <h2>Please enter the password</h2>
            <input type="password" id="passwordInput">
            <button onclick="checkPassword()">Submit</button>
            <button onclick="closePasswordModal()">Cancel</button>
        </div>
    </div>

    <script>
        function openPasswordModal() {
            document.getElementById('passwordModal').style.display = 'flex';
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }

        function checkPassword() {
            var password = document.getElementById('passwordInput').value;

            if (password === 'admin00') {
                // Redirect to the desired page upon successful password entry
                window.location.href = 'details.php';
            } else {
                alert('Incorrect password. Please try again.');
            }
        }
    </script>
</body>
</html>
