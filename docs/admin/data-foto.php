<?php
session_start();
require_once("../db/conn.php");
$hak = $_SESSION['hak_akses']
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
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        require_once("../layouts/navbar.php");
        include 'adminpage.php';
    ?>
    <main role="main">
    <div id="">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Tables</h1>
                <ol class="breadcrumb mb-4">
                </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id='example' class='table table-striped' style='width:100%'>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>ID FOTO</th>
                                        <th>Judul Foto</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Unggah</th>
                                        <th>Id Album</th>
                                        <th>Id User</th>
                                        <th>Nama User</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $no = 1;
                                    // Updated query to join the 'foto' table with the 'user' table
                                    $query = "SELECT foto.*, user.username AS nama_user FROM foto INNER JOIN user ON foto.id_user = user.id_user";
                                    $stmt = $conn->query($query);

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['id_foto']); ?></td>
                                    <td><?= htmlspecialchars($row['judul_foto']); // Corrected 'judul-foto' to 'judul_foto' if necessary ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                    <td><?= htmlspecialchars($row['tgl_unggah']); ?></td>
                                    <td><?= htmlspecialchars($row['id_album']); ?></td>
                                    <td><?= htmlspecialchars($row['id_user']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_user']); ?></td>
                                    <td>
                                        <a class="btn btn-warning btn-sm" type="button" href="edit-foto.php?id_foto=<?= $row['id_foto']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a class="btn btn-danger btn-sm" type="button" onclick="return confirm('Data akan di Hapus?')" href="hapus-foto.php?id_foto=<?= $row['id_foto']; ?>"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </main>
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
