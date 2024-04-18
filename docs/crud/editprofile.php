<?php  
session_start();
require_once("../db/conn.php");

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('You must be logged in to access this page.'); window.location='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

// Fetch existing user data
$stmt = $conn->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('User not found.'); window.location='http://localhost/galer/docs/profile.php';</script>";
    exit;
}

// Check if form is submitted for user update
if (isset($_POST['update'])) {
    $nama_user = htmlspecialchars($_POST["nama_user"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : $user['password'];

    // Update user in database
    $stmt = $conn->prepare("UPDATE user SET nama_user = ?, username = ?, email = ?, password = ? WHERE id_user = ?");
    $stmt->execute([$nama_user, $username, $email, $password, $id_user]);

    echo "<script>alert('Profile updated successfully!'); window.location='http://localhost/galer/docs/profile.php';</script>";
}

?>

<!DOCTYPE html>  
<html>  
<head>  
    <title>User Profile</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add your CSS and meta tags here -->
    <script type="text/javascript"> (function() { var css = document.createElement('link'); css.href = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css'; css.rel = 'stylesheet'; css.type = 'text/css'; document.getElementsByTagName('head')[0].appendChild(css); })(); </script>
    <link rel="stylesheet" href="../assets/css/app.css">
    <link rel="stylesheet" href="../assets/css/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<main role="main">
<div class="container-fluid mb-5">
    <div class="text-center mb-4 fs-3 fw-semibold">
        Update Profile
    </div>
    <form method="post">
        <!-- Name input -->
        <div class="form-outline mb-4">
            <input name="nama_user" type="text" id="form2Example1" class="form-control" value="<?= htmlspecialchars($user['nama_user']) ?>"/>
            <label class="form-label" for="form2Example1">Display Name</label>
        </div>
        <!-- Username input -->
        <div class="form-outline mb-4">
            <input name="username" type="text" id="form2Example2" class="form-control" value="<?= htmlspecialchars($user['username']) ?>"/>
            <label class="form-label" for="form2Example2">Username</label>
        </div>
        <!-- Email input -->
        <div class="form-outline mb-4">
            <input name="email" type="email" id="form2Example3" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"/>
            <label class="form-label" for="form2Example3">Email address</label>
        </div>
        <!-- Password input -->
        <div class="form-outline mb-4">
            <input name="password" type="password" id="form2Example4" class="form-control" placeholder="Enter new password to change"/>
            <label class="form-label" for="form2Example4">Password</label>
        </div>
        <button type="submit" name="update" class="btn btn-primary btn-block mb-4">Update Profile</button>
    </form>
</div>
</main>


<!-- Bootstrap JS and other libraries -->
</body>  
</html>
