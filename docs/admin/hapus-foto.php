<?php
session_start();
require_once("../db/conn.php");

if(isset($_GET['id_foto'])) {
    $id_foto = $_GET['id_foto'];

    // Delete related likes
    $delete_likes_query = "DELETE FROM likes WHERE id_foto = :id_foto";
    $delete_likes_stmt = $conn->prepare($delete_likes_query);
    $delete_likes_stmt->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
    $delete_likes_stmt->execute();

    // Delete related comments
    $delete_comments_query = "DELETE FROM komentar WHERE id_foto = :id_foto";
    $delete_comments_stmt = $conn->prepare($delete_comments_query);
    $delete_comments_stmt->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
    $delete_comments_stmt->execute();

    // Fetch the photo data from the database
    $query = "SELECT lokasi_file FROM foto WHERE id_foto = :id_foto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
    $stmt->execute();
    $photo_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($photo_data) {
        // Delete the photo from the server
        $lokasi_file = $photo_data['lokasi_file'];
        if(file_exists($lokasi_file)) {
            unlink($lokasi_file); // Delete the file from the server
        }

        // Delete the photo data from the database
        $delete_query = "DELETE FROM foto WHERE id_foto = :id_foto";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
        if($delete_stmt->execute()) {
            // Photo deleted successfully
            echo "Photo deleted successfully.";
        } else {
            // Failed to delete photo from the database
            echo "Failed to delete photo from the database.";
        }
    } else {
        // Photo not found in the database
        echo "Photo not found.";
    }
}
include 'adminpage.php';
?>
