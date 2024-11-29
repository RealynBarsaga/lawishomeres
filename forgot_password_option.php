<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madridejos Home Residence Management System</title>
    <link rel="icon" type="x-icon" href="img/lg.png">
    <!-- Add FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/received_1185064586170879.jpeg');
            background-attachment: fixed;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease-in-out;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 90%; /* Make container width flexible */
            box-sizing: border-box; /* Include padding in width calculation */
        }
        h2 {
            text-align: center;
            color: #333;
        }
        /* Style for the option buttons */
        .button-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .option-btn {
            padding: 12px 28px;
            background-color: #f0f2f5;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            text-align: left;
            cursor: pointer;
            width: 100%; /* Full width on smaller screens */
            transition: background-color 0.3s ease;
        }

        .option-btn:hover {
            background-color: #e1e4e8; /* Darker background on hover */
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
            flex-direction: column;
        }
        
        /* Style for the continue button */
        .continue-btn {
            background-image: url(img/bg.jpg);
            color: #fff;
            border: none;
            margin-left: -21px;
            width: 100%; /* Full width on smaller screens */
            padding: 12px 28px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        /* Hover effect for continue button */
        .continue-btn:hover {
            background-color: #0056b3;
        }
        
        /* Style for the login button */
        .login-btn {
            background-image: url(img/bg.jpg);
            color: #fff;
            border: none;
            color: #fff;
            padding: 12px 28px;
            width: 100%; /* Full width on smaller screens */
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        /* Hover effect for login button */
        .login-btn:hover {
            background-color: #e1e4e8;
        }
        
        a {
            text-decoration: none;
        }
        p {
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 15px;
            }
            .option-btn {
                padding: 10px 20px;
                font-size: 14px;
            }
            .login-btn, .continue-btn {
                font-size: 14px;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .container {
                max-width: 100%;
                padding: 10px;
            }
            h2 {
                font-size: 18px;
            }
            .option-btn {
                padding: 8px 16px;
                font-size: 14px;
            }
            .login-btn, .continue-btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
        .reset-methods {
        display: flex;
        flex-direction: column;
        gap: 15px;
        }
        .reset-option {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .reset-option:hover {
            background-color: #f0f0f0;
            border-color: #512da8;
        }
        .reset-option i {
            font-size: 24px;
            margin-right: 15px;
            color: #512da8;
        }
        .option-text {
            text-align: left;
        }
        .option-title {
            display: block;
            font-weight: bold;
            color: #333;
        }
        .option-desc {
            display: block;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Forgot Password</h2>
    <p>Choose how you want to reset your password:</p>
    
    <!-- Selection Options -->
    <div class="reset-methods">
        <button onclick="showForm('email_link')" class="reset-option">
            <i class="fa fa-link"></i>
            <div class="option-text">
                <span class="option-title">Reset via Email Link</span>
                <span class="option-desc">Receive a reset link via email</span>
            </div>
        </button>
        <button onclick="showForm('email_otp')" class="reset-option">
            <i class="fa fa-envelope"></i>
            <div class="option-text">
                <span class="option-title">Reset via Email OTP</span>
                <span class="option-desc">Receive a code via email</span>
            </div>
        </button>
        <button onclick="showForm('sms')" class="reset-option">
            <i class='fas fa-mobile-alt'></i>
            <div class="option-text">
                <span class="option-title">Reset via SMS</span>
                <span class="option-desc">Receive a code via SMS</span>
            </div>
        </button>
    </div>
    
    <div class="button-container">
        <a href="login.php">
            <button class="login-btn">Login</button>
        </a>
    </div>
</div>
<script>
function showForm(method) {
    // Redirect to different pages based on the selected method
    if (method === 'email_link') {
        window.location.href = '../email_link_form'; // Redirect to the email link form page
    } else if (method === 'email_otp') {
        window.location.href = 'email_otp_form.php'; // Redirect to the email OTP form page
    }
}

// Optional: if you want to trigger a redirect on button click for "Go back to Login"
document.querySelector('.login-btn').addEventListener('click', function() {
    // Redirect to login page
    window.location.href = 'login.php'; // Redirect to the login page
});
</script>
</body>
</html>