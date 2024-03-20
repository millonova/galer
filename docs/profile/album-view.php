<?php
    // Start session
    session_start();
    require_once("../db/conn.php");
    $nama_user = "User Not Found";
    $username = "N/A";
    $edit_button = '';
    $delete_button = '';
    $name_album = ""; // Define the variable
    $deskripsi = ""; // Define the variable

    // Get the user ID from the URL
    if (isset($_GET['id_album'])) {
        $id_album = isset($_GET['id_album']) ? $_GET['id_album'] : null;
        
        $query2 = "SELECT user.id_user FROM user INNER JOIN album ON user.id_user = album.id_user WHERE album.id_album = :id_album";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindParam(':id_album', $id_album, PDO::PARAM_INT);
		$stmt2->execute();
		$user_data = $stmt2->fetch(PDO::FETCH_ASSOC);

		if ($user_data) {
			// User id found
			$id_user = $user_data['id_user'];
		} else {
			// User id not found
			$id_user = null; // Set id_user to null if not found
		}
        // Query the database to fetch user information
        if(isset($_SESSION['id_user']) && $_SESSION['id_user'] == $id_user) {
            $edit_button = '<button class="btn btn-danger mb-2" onclick="window.location.href=\'http://localhost/galer/docs/crud/editalbum.php\'">Edit Album</button>';
            $delete_button = '<button class="btn btn-outline-danger mb-2" onclick="window.location.href=\'http://localhost/galer/docs/crud/deletealbum.php?id_album=' . $id_album . '\'">Delete Album</button>';
        }

        $user_query = "SELECT user.id_user, user.nama_user, user.username, album.nama_album, album.deskripsi FROM user
                        INNER JOIN album ON user.id_user = album.id_user
                        WHERE album.id_album = :id_album";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bindParam(':id_album', $id_album, PDO::PARAM_INT);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Assign user details if found
            $id_user = $user_data['id_user'];
            $nama_user = $user_data['nama_user'];
            $username = $user_data['username'];
            $name_album = $user_data['nama_album']; // Assign album name
            $deskripsi = $user_data['deskripsi']; // Assign album name

            // Fetch all photos associated with the user and specific album
            $photo_query = "SELECT foto.* FROM foto
                            INNER JOIN album ON foto.id_album = album.id_album
                            WHERE album.id_album = :id_album AND foto.id_user = :id_user";
            $photo_stmt = $conn->prepare($photo_query);
            $photo_stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $photo_stmt->bindParam(':id_album', $id_album, PDO::PARAM_INT);
            $photo_stmt->execute();

            // Fetch all photos associated with the user and specific album
            $result = $photo_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Handle the case where the user ID was not found
            echo "User not found.";
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

    <div class="container mb-4">
    <h1 class="font-weight-bold title"><?php echo $name_album;?></h1>
    <h6 class="title"><?php echo $deskripsi;?></h6>
    <div class="row justify-content-center">
        <div class="col-lg-12 align-items-center mb-5">
            <!-- Inside your HTML body -->
            <?php echo $edit_button; ?>
            <?php echo $delete_button; ?>
        </div>
    </div>
    <div class="container-fluid mb-5">
    	<div class="row">
    		<div class="card-columns">
			<?php
				if (count($result) > 0) {
				foreach ($result as $row) {
					$id_foto = $row['id_foto'];
					$judul_foto = $row['judul_foto'];
					$judul_foto = str_replace(['(', ')'], '', $judul_foto);
					$judul_foto = str_replace(' ', '-', $judul_foto); // Replace spaces with hyphens
					$lokasi_file = 'img/' . $id_foto . '/' . $judul_foto;
					echo'<div class="card card-pin">';
					echo'<img class="card-img" src="' . str_replace($_SERVER["DOCUMENT_ROOT"], "", $row["lokasi_file"]) . '" alt="Card image">';
					echo'<div class="overlay">';
					echo'<h2 class="card-title title">' . $row['judul_foto'] . '</h2>';
					echo'<div class="more">';
					echo'<a href="http://localhost/galer/docs/post.php?id_foto=' . $row['id_foto']. '">';
					echo'<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> More </a>';
					echo'</div>';
					echo'</div>';
					echo'</div>';
				}}else {
					echo "No images found.";
				}
				?>
    		</div>
    	</div>
    </div>
        
    </main>

    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/theme.js"></script>
    
    <?php
        require_once("../layouts/footer.php");
    ?>

</body>
</html>