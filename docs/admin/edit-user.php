<?php
session_start();
require_once("../db/conn.php");


if (isset($_POST['simpan'])) {

$nama_user = filter_input(INPUT_POST, 'nama_user', FILTER_SANITIZE_STRING);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$password2 = password_hash($_POST["password2"], PASSWORD_DEFAULT);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$id_user = $_POST['id_user'];

// Gunakan prepared statement untuk mencegah SQL injection

if(isset($_POST['hak_akses'])) {
    $akses = $_POST['hak_akses'];
} 
if (empty($nama_user) || empty($username) || empty($email) || empty($_POST["hak_akses"])) {
    $error = "Form kosong";
}
if ($_POST["password"] !== $_POST["password2"]) {
    echo "<script>cpassworderror();</script>";
    $error = "Password tidak sama";
}
if(!isset($error)){
    //no error
                //Securly insert into database
    $sql = 'UPDATE user SET id_user=:id_user, username=:username, password=:password, nama_user=:nama_user, email=:email, hak_akses=:akses WHERE id_user=:id_user';
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':nama_user', $nama_user);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':akses', $akses);
        
    $stmt->execute();
    echo "<script>document.location.href='http://localhost/galer/docs/admin/user-data.php';</script>";
} else {
    $sql = 'UPDATE user SET id_user=:id_user, username=:username, nama_user=:nama_user, email=:email, hak_akses=:akses WHERE id_user=:id_user';
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nama_user', $nama_user);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':akses', $akses);
        
    $stmt->execute();
    echo "<script>document.location.href='http://localhost/galer/docs/admin/user-data.php';</script>";
}
}

// Gunakan prepared statement untuk mencegah SQL injection
$id_user = $_GET['id_user'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id_user = :id_user");
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();
$edit = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <?php
        require_once("../layouts/navbar.php");
        include 'adminpage.php';
    ?>
<main role="main">
<div class ="container-fluid mb-5">
    <form action="" method="POST">
        <input type="hidden" name="id_user" id="id_user" value="<?= $edit['id_user']; ?>">
        <div class="row">
            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="username" value="<?= $edit['username'] ?>">
                <label class="mx-2" for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="nama_user" class="form-control" id="nama_user" value="<?= $edit['nama_user'] ?>">
                <label class="mx-2" for="nama_user">Nama</label>
            </div>
            <div class="form-floating mb-3">
            <input class="form-control" type="password" id="password1" name="password"/>
                <label class="mx-2" for="floatingPassword">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password2" class="form-control" id="rfloatingPassword">
                <label class="mx-2" for="rfloatingPassword">Repeat Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="floatingInput" value="<?= $edit['email'] ?>">
                <label class="mx-2" for="floatingInput">Email address</label>
            </div>
            <div>
            <select name="hak_akses" class="form-select form-select mb-3" aria-label=".form-select-lg example">
                <option selected hidden disabled>-- Pilih Hak Akses --</option>
                <option value="admin" <?= ($edit['hak_akses'] == 'admin') ? 'selected' : '' ?>>admin</option>
                <option value="client" <?= ($edit['hak_akses'] == 'client') ? 'selected' : '' ?>>client</option>
            </select>

            </div>
            <div class="col-6">
                <input class="btn btn-success btn-block w-100" type="submit" name="simpan" value="Simpan">
            </div>
            <div class="col-6">
                <input class="btn btn-danger btn-block w-100" type="reset">
            </div>   
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <?php
    $conn = null;
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
    </script>
        <script src="../assets/js/app.js"></script>
    <script src="../assets/js/theme.js"></script>
    <?php
        require_once("../layouts/footer.php");
    ?>
</body>

</html>
