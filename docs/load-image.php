<?php
require_once("db/conn.php");
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 10;

$sql = "SELECT * FROM foto AS j 
        INNER JOIN user AS u ON j.id_user = u.id_user
        INNER JOIN album AS t ON j.id_album = t.id_album
        ORDER BY j.tgl_unggah ASC LIMIT :offset, :limit";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    echo "<div class='card card-pin'>";
    echo "<img class='card-img' src='img/{$row['id_foto']}/{$row['judul_foto']}' alt='Card image'>";
    echo "<div class='overlay'>";
    echo "<h2 class='card-title title'>{$row['judul_foto']}</h2>";
    echo "<div class='more'>";
    echo "<a href='post.php?id_foto={$row['id_foto']}'>More <i class='fa fa-arrow-circle-o-right' aria-hidden='true'></i></a>";
    echo "</div></div></div>";
}
?>
