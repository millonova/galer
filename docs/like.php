<?php
session_start();
require_once("db/conn.php");

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    http_response_code(403);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foto_id'])) {
    $fotoId = $_POST['foto_id'];
    $userId = $_SESSION['id_user'];
    $tglLike = date("Y-m-d H:i:s");

    try {
        // Check if the user has already liked the photo
        $checkLikeQuery = "SELECT * FROM likes WHERE id_foto = :foto_id AND id_user = :user_id";
        $stmt = $conn->prepare($checkLikeQuery);
        $stmt->bindParam(':foto_id', $fotoId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // User has already liked the photo, remove the like
            $deleteLikeQuery = "DELETE FROM likes WHERE id_foto = :foto_id AND id_user = :user_id";
            $stmt = $conn->prepare($deleteLikeQuery);
            $stmt->bindParam(':foto_id', $fotoId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            echo 'unliked';
        } else {
            // User has not liked the photo yet, add the like
            $addLikeQuery = "INSERT INTO likes (id_foto, id_user, tgl_like) VALUES (:foto_id, :user_id, :tgl_like)";
            $stmt = $conn->prepare($addLikeQuery);
            $stmt->bindParam(':foto_id', $fotoId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':tgl_like', $tglLike, PDO::PARAM_STR);
            $stmt->execute();

            echo 'liked';
        }
    } catch (PDOException $e) {
        // Handle any database errors
        http_response_code(500);
        exit('Database error: ' . $e->getMessage());
    }
} else {
    http_response_code(400);
    exit('Bad request');
}
?>
