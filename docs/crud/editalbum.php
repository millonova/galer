<?php
session_start();
require_once("../db/conn.php");

$id_album = isset($_GET['id_album']) ? htmlspecialchars($_GET['id_album']) : '';

if(!$id_album) {
    die("Album ID is required.");
}

// Fetch existing data
try {
    $stmt = $conn->prepare("SELECT * FROM album WHERE id_album = :id_album");
    $stmt->bindParam(':id_album', $id_album);
    $stmt->execute();
    $album = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if (isset($_POST['update'])) {
    $nama_album = isset($_POST['nama_album']) ? htmlspecialchars($_POST['nama_album']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '';
    $tgl_buat = date("Y-m-d"); // Update the date or keep the original
    $error = '';

    if (empty($nama_album)) {
        $error = "Nama album is required.";
    }

    if (empty($error)) {
        try {
            // Update existing album
            $sql = "UPDATE album SET nama_album = :nama_album, deskripsi = :deskripsi, tgl_buat = :tgl_buat WHERE id_album = :id_album";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_album', $id_album);
            $stmt->bindParam(':nama_album', $nama_album);
            $stmt->bindParam(':deskripsi', $deskripsi);
            $stmt->bindParam(':tgl_buat', $tgl_buat);

            if ($stmt->execute()) {
                echo "Album data updated successfully.";
                $redirectUrl = "http://localhost/galer/docs/profile/user.php?id_user=" . $_SESSION['id_user'];
                header('Location: ' . $redirectUrl);
            } else {
                echo "Failed to update album data.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
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
                        placeholder="Nama Album" value="<?php echo isset($album['nama_album']) ? $album['nama_album'] : ''; ?>">
                    <label for="nama_album">Nama Album</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="deskripsi" class="form-control" id="deskripsi"
                        placeholder="Deskripsi" value="<?php echo isset($album['deskripsi']) ? $album['deskripsi'] : ''; ?>">
                    <label for="deskripsi">Deskripsi</label>
                </div>
                <div class="col-12">
                    <input class="btn btn-primary btn-block w-100" type="submit" name="update"
                        value="Update Album">
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