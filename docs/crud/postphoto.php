<?php
session_start();

require_once("../db/conn.php");

$id_user = $_SESSION['id_user'];

// Generate unique ID for the photo
$random_numbers = rand(1000, 99999999);
$random_numbers = substr($random_numbers, 0, rand(4, 8)); // Limit to 4-8 digits

// Concatenate random numbers with id_user from session
$id_foto = $random_numbers . $_SESSION['id_user'];

// If the total length of id_album exceeds 11 characters, truncate it
$id_foto = substr($id_foto, 0, 11);

if (isset($_POST['daftar'])) {
    $judul_foto = isset($_POST['judul_foto']) ? htmlspecialchars($_POST['judul_foto']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '';
    $id_album = isset($_POST['id_album']) ? htmlspecialchars($_POST['id_album']) : '';       
    $tgl_unggah = date("Y-m-d");

    // Validation and error handling
    $error = '';
    if (empty($judul_foto)) {
        $error = "Judul foto is required.";
    }
    // Add more validation rules as needed

    if (empty($error)) {
        $uploadedFile = $_FILES["gambar"];

        if ($uploadedFile["error"] === UPLOAD_ERR_OK) {
            $gambarNama = $uploadedFile["name"];
            $lokasi_file = $_SERVER['DOCUMENT_ROOT'] . "/galer/image/" . $gambarNama;

            if (move_uploaded_file($uploadedFile["tmp_name"], $lokasi_file)) {
                try {
                    // Insert photo data into the database
                    $sql = 'INSERT INTO foto (id_foto, judul_foto, deskripsi, tgl_unggah, lokasi_file, id_album, id_user) VALUES (?, ?, ?, ?, ?, ?, ?)';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$id_foto, $judul_foto, $deskripsi, $tgl_unggah, $lokasi_file, $id_album, $id_user]);

                    echo "Gambar berhasil diunggah dan disimpan di basis data.";
                } catch (PDOException $e) {
                    echo "Failed to connect to or operate on the database: " . $e->getMessage();
                }
            } else {
                echo "Gagal mengunggah gambar ke server.";
            }
        } else {
            echo "Terjadi kesalahan saat mengunggah gambar.";
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
                    <input type="text" name="judul_foto" class="form-control" id="judul_foto"
                        placeholder="Nama Produk">
                    <label class="mx-2" for="judul_foto">Judul Foto</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="deskripsi" class="form-control" id="deskripsi"
                        placeholder="Nama Produk">
                    <label class="mx-2" for="deskripsi">Deskripsi</label>
                </div>
                <div class="form-floating mb-3">
                </div>
                <div class="">
                    <select name="id_album" class="form-select mb-3"
                        aria-label=".form-select-lg example">
                        <option selected hidden disabled value="">Pilih Album</option>
                        <option value="1">Makanan</option>
                        <option value="2">Minuman</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Input Gambar PNG, JPEG, JPG</label>
                    <input class="form-control" type="file" id="formFile" name="gambar" accept=".png, .jpeg, .jpg">
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