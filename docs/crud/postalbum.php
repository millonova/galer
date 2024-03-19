<?php
session_start();

require_once("../db/conn.php");

$id_user = $_SESSION['id_user'];

// Generate unique ID for the album
$random_numbers = rand(1000, 99999999);
$random_numbers = substr($random_numbers, 0, rand(4, 8)); // Limit to 4-8 digits

// Concatenate random numbers with id_user from session
$id_album = $random_numbers . $_SESSION['id_user'];

// If the total length of id_album exceeds 11 characters, truncate it
$id_album = substr($id_album, 0, 11);

if (isset($_POST['daftar'])) {
    $nama_album = isset($_POST['nama_album']) ? htmlspecialchars($_POST['nama_album']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '';  
    $tgl_buat = date("Y-m-d");

    // Validation and error handling
    $error = '';
    if (empty($nama_album)) {
        $error = "Nama album is required.";
    }
    // Add more validation rules as needed

    if (empty($error)) {
        try {
            // Insert album data into the database
            $sql = 'INSERT INTO album (id_album, nama_album, deskripsi, tgl_buat, id_user) 
            VALUES (:id_album, :nama_album, :deskripsi, :tgl_buat, :id_user)';
            $query1 = $conn->prepare($sql);

            $query1->bindParam(':id_album', $id_album);
            $query1->bindParam(':nama_album', $nama_album);
            $query1->bindParam(':deskripsi', $deskripsi);
            $query1->bindParam(':tgl_buat', $tgl_buat);
            $query1->bindParam(':id_user', $id_user);

            if ($query1->execute()) {
                // Retrieve the auto-increment value if needed
                $autoIncrementValue = $conn->lastInsertId();

                // Proceed with the second query if necessary
            } else {
                echo "Failed to insert album data.";
            }
        } catch (PDOException $e) {
            echo "Failed to connect to or operate on the database: " . $e->getMessage();
        }
    } else {
        echo $error;
    }
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
    <?php
        require_once("../layouts/navbar.php");
    ?>
    <main role="main">
    <div class="container-fluid mb-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="form-floating mb-3">
                    <input type="text" name="nama_album" class="form-control" id="nama_album"
                        placeholder="Nama Produk">
                    <label class="mx-2" for="nama_album">Nama Album</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="deskripsi" class="form-control" id="deskripsi"
                        placeholder="Nama Produk">
                    <label class="mx-2" for="deskripsi">Deskripsi</label>
                </div>
                <div class="col-6">
                    <input class="btn btn-primary btn-block w-100" type="submit" name="daftar"
                        value="daftar">
                </div>
                <div class="col-6">
                    <input class="btn btn-danger btn-block w-100" type="reset">
                </div>
            </div>
        </form>
    </div>

    </main>

    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/theme.js"></script>
    
    <?php
        require_once("../layouts/footer.php");
    ?>

</body>
</html>