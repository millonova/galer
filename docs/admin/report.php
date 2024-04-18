<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Activity Report</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<h2>User Activity Report</h2>
<?php
// Include the database connection file
require_once("../db/conn.php");
include 'adminpage.php';

try {
    $sql = "
        SELECT
            'Upload Foto' AS Activity,
            f.judul_foto AS Item,
            f.tgl_unggah AS Activity_Date,
            u.username AS User
        FROM
            foto f
        JOIN
            user u ON f.id_user = u.id_user
        UNION
        SELECT
            'Edit Album' AS Activity,
            a.nama_album AS Item,
            a.tgl_buat AS Activity_Date,
            u.username AS User
        FROM
            album a
        JOIN
            user u ON a.id_user = u.id_user
        UNION
        SELECT
            'Beri Komentar' AS Activity,
            k.isi_komen AS Item,
            k.tgl_komen AS Activity_Date,
            u.username AS User
        FROM
            komentar k
        JOIN
            user u ON k.id_user = u.id_user
        UNION
        SELECT
            'Beri Like' AS Activity,
            CONCAT('Foto: ', f.judul_foto) AS Item,
            l.tgl_like AS Activity_Date,
            u.username AS User
        FROM
            likes l
        JOIN
            foto f ON l.id_foto = f.id_foto
        JOIN
            user u ON l.id_user = u.id_user
        ORDER BY
            Activity_Date DESC;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        echo "<table id='example' class='table table-striped' style='width:100%'>";
        echo "<thead><tr><th>User</th><th>Activity</th><th>Item</th><th>Activity_Date</th></tr></thead>";
        echo "<tbody>";
        foreach ($result as $row) {
            echo "<tr><td>" . htmlspecialchars($row["User"]) . "</td><td>" . htmlspecialchars($row["Activity"]) . "</td><td>" . $row["Item"] . "</td><td>" . htmlspecialchars($row["Activity_Date"]) . "</td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No activities found";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$conn = null;
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
});
</script>
</body>
</html>
