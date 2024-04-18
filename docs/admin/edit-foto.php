<?php
session_start();
require_once("../db/conn.php");

// Check for session user's ID
if (!isset($_SESSION['id_user'])) {
    echo "You need to login to view this page.";
    exit;
}

if (isset($_GET['id_foto'])) {
    $id_foto = $_GET['id_foto'];
    // Fetch the photo without checking the owner
    $stmt = $conn->prepare("SELECT * FROM foto WHERE id_foto = :id_foto");
    $stmt->execute([':id_foto' => $id_foto]);
    $foto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$foto) {
        echo "No image found with that ID!";
        exit;
    }
} else {
    echo "No ID provided!";
    exit;
}

if (isset($_POST['daftar']) && $foto) {
    $judul_foto = htmlspecialchars($_POST['judul_foto'] ?? '');
    $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
    $id_album = htmlspecialchars($_POST['id_album'] ?? '');
    $tgl_unggah = date("Y-m-d");

    $error = '';
    if (empty($judul_foto)) {
        $error = "Judul foto is required.";
    }

    if (empty($error)) {
        $uploadedFile = $_FILES["gambar"];
        if ($uploadedFile["error"] === UPLOAD_ERR_OK) {
            $gambarNama = $uploadedFile["name"];
            $lokasi_file = $_SERVER['DOCUMENT_ROOT'] . "/galer/image/" . $gambarNama;

            if (move_uploaded_file($uploadedFile["tmp_name"], $lokasi_file)) {
                $updateImage = true;
            } else {
                echo "Failed to upload image to the server.";
                exit;
            }
        } else {
            // No file was uploaded, keep the existing image
            $lokasi_file = $foto['lokasi_file'];
            $updateImage = false;
        }

        // Build the SQL query dynamically based on whether a new image was uploaded
        $sql = 'UPDATE foto SET judul_foto = ?, deskripsi = ?, tgl_unggah = ?, id_album = ?' . ($updateImage ? ', lokasi_file = ?' : '') . ' WHERE id_foto = ?';
        $params = [$judul_foto, $deskripsi, $tgl_unggah, $id_album, $id_foto];
        if ($updateImage) {
            array_splice($params, 4, 0, $lokasi_file); // Insert the new image location before the id_foto
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        echo "Image successfully updated.";
    } else {
        echo $error;
    }
}
$query = "SELECT id_album, nama_album FROM album WHERE id_user = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
$stmt->execute();
$albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="container-fluid mb-5">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-floating mb-3">
                        <input type="text" name="judul_foto" class="form-control" id="judul_foto"
                            placeholder="Judul Foto" value="<?php echo htmlspecialchars($foto['judul_foto']); ?>">
                        <label class="mx-2" for="judul_foto">Judul Foto</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="deskripsi" class="form-control" id="deskripsi"
                            placeholder="Deskripsi" value="<?php echo htmlspecialchars($foto['deskripsi']); ?>">
                        <label class="mx-2" for="deskripsi">Deskripsi</label>
                    </div>
                    <div class="">
                        <select name="id_album" class="form-select mb-3" aria-label=".form-select-lg example">
                            <option hidden disabled value="">Pilih Album</option>
                            <?php foreach ($albums as $album): ?>
                                <option value="<?php echo $album['id_album']; ?>"
                                    <?php echo $album['id_album'] == $foto['id_album'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($album['nama_album']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Input Gambar PNG, JPEG, JPG</label>
                        <input class="form-control" type="file" id="formFile" name="gambar" accept=".png, .jpeg, .jpg">
                    </div>
                    <div class="col-6">
                        <input class="btn btn-primary btn-block w-100" type="submit" name="daftar" value="Update">
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