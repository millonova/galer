<?php  
    session_start();

if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message">
        <span>'.$message.'</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
require_once("db/conn.php");

// Check if form is submitted
if (isset($_POST['register'])) {
    $nama_user = htmlspecialchars($_POST["nama_user"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $hak_akses = 'client';
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $username_exists = $stmt->fetch();

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $email_exists = $stmt->fetch();
    
    // If username or email already exists, display error
    if ($username_exists || $email_exists) {
        echo "<script>alert('Username or email already exists. Please choose a different one.');</script>";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Get current date and time
        
        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO user (nama_user, username, email, password, hak_akses) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama_user, $username, $email, $hashed_password, $hak_akses]);
        
        echo "<script>alert('Registration successful! You can now log in.');</script>";
    }
}

?>
<!DOCTYPE html>  
<html>  
    <head>  
        <title>Login | Admin</title> 
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Webkolah</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="alertsweet1.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <body>
    <div class="row">
            <div class="container mt-5 px-3 py-3 card col-5">
                <div class="card-body mt-2">
                    <div class="text-center mb-4 fs-3 fw-semibold">
                        Login User
                    </div>
                    <form method="post">
                                <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input name="nama_user" type="text" id="form2Example1" class="form-control" />
                            <label class="form-label" for="form2Example1">Display Name</label>
                        </div>
                                <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input name="username" type="text" id="form2Example2" class="form-control" />
                            <label class="form-label" for="form2Example2">Username</label>
                        </div>
                                <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input name="email" type="email" id="form2Example3" class="form-control" />
                            <label class="form-label" for="form2Example3">Email address</label>
                        </div>
                                <!-- Password input -->
                        <div class="form-outline mb-4">
                            <input name="password" type="password" id="form2Example4" class="form-control" />
                            <label class="form-label" for="form2Example4">Password</label>
                        </div>
                        <button type="submit" name="register" value="register" class="btn btn-primary btn-block mb-4">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="dashboardjs/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
      </body>  
 </html>  