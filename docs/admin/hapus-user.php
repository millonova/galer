<?php
require_once("../db/conn.php");
$id = $_GET["id_user"];
//mengambil id yang ingin dihapus
try {
    //jalankan query DELETE untuk menghapus data
    $query = "DELETE FROM user WHERE id_user = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Cek apakah baris terpengaruh (berhasil dihapus)
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Data berhasil dihapus.');window.location='data-user.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');window.location='data-user.php';</script>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
include 'adminpage.php';
?>