<?php  
    session_start();
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
</head>
<body>
<?php
    require_once("db/conn.php");
    
    // Check if form is submitted
    if (isset($_POST['login'])) {
        // Check if login_identifier exists in $_POST array
        if(isset($_POST['login_identifier'])) {
            $loginIdentifier = htmlspecialchars($_POST["login_identifier"]); // Input field for username or email
            $password = htmlspecialchars($_POST["password"]);
            
            try {
                // Prepare SQL statement to check both username and email
                $sql = "SELECT * FROM user WHERE username = :login_identifier OR email = :login_identifier";

                // Prepare the statement
                $stmt = $conn->prepare($sql);
            
                // Bind parameters
                $stmt->bindParam(':login_identifier', $loginIdentifier, PDO::PARAM_STR);
            
                // Execute the statement
                $stmt->execute();
            
                // Fetch the result
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if (count($result) === 1) {
                    $row = $result[0];
            
                    if (password_verify($password, $row['password'])) {
                        // Set session variables
                        $_SESSION['nama_user'] = $row['nama_user'];
                        $_SESSION['id_user'] = $row['id_user'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['hak_akses'] = $row['hak_akses'];
                        
                        // Set login session
                        $_SESSION['login'] = true;
            
                        // Set remember me cookie if checked
                        if (isset($_POST['remember'])) {
                            setcookie('id', $row['id_user'], time() + 60);
                            setcookie('key', hash('sha256', $row['username']), time() + 60);
                        }
            
                        // Redirect to profile page
                        header("Location: http://localhost/galer/docs/index.php");
                        exit();
                    } else {
                        // Incorrect password
                        $_SESSION['username'] = '';
                        echo "<script>alert('Username/Email atau Password Salah!');</script>";
                    }
                } else {
                    // No user found
                    $_SESSION['username'] = '';
                    echo "<script>alert('Username/Email atau Password Salah!');</script>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            // Handle case where login_identifier is not set
            echo "<script>alert('Login Identifier is not set');</script>";
        }
    }
?>

<div class="row">
    <div class="container mt-5 px-3 py-3 card col-5">
        <div class="card-body mt-2">
            <div class="text-center mb-4 fs-3 fw-semibold">
                Login User
            </div>
            <form method="post">
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input name="login_identifier" type="text" id="form2Example1" class="form-control" />
                    <label class="form-label" for="form2Example1">Username or Email</label>
                </div>
                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input name="password" type="password" id="form2Example2" class="form-control" />
                    <label class="form-label" for="form2Example2">Password</label>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block mb-4">Sign in</button>
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
