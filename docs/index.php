<?php
// Start the session
session_start();
require_once("db/conn.php");

$sql = "SELECT * FROM foto AS j 
		INNER JOIN user AS u ON j.id_user = u.id_user
		INNER JOIN album AS t ON j.id_album = t.id_album
		ORDER BY j.tgl_unggah ASC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all rows as an associative array
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - Pintereso Bootstrap Template</title>
    <script type="text/javascript"> (function() { var css = document.createElement('link'); css.href = 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'; css.rel = 'stylesheet'; css.type = 'text/css'; document.getElementsByTagName('head')[0].appendChild(css); })(); </script>
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/theme.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
	<?php
		require_once("layouts/navbar.php");
	?>
    <main role="main">
        
    
    <section class="mt-4 mb-5">
    <div class="container mb-4">
    	<h1 class="font-weight-bold title">Explore</h1>
    	<div class="row">
    		<nav class="navbar navbar-expand-lg navbar-light bg-white pl-2 pr-2">
    		<button class="navbar-light navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExplore" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
    		<span class="navbar-toggler-icon"></span>
    		</button>
    		<div class="collapse navbar-collapse" id="navbarsExplore">
    			<ul class="navbar-nav">
    				<li class="nav-item">
    				<a class="nav-link" href="#">Lifestyle</a>
    				</li>
    				<li class="nav-item">
    				<a class="nav-link" href="#">Food</a>
    				</li>
    				<li class="nav-item">
    				<a class="nav-link" href="#">Home</a>
    				</li>
    				<li class="nav-item">
    				<a class="nav-link" href="#">Travel</a>
    				</li>
    				<li class="nav-item dropdown">
    				<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More</a>
    				<div class="dropdown-menu shadow-lg" aria-labelledby="dropdown01">
    					<a class="dropdown-item" href="#">Astronomy</a>
    					<a class="dropdown-item" href="#">Nature</a>
    					<a class="dropdown-item" href="#">Cooking</a>
    					<a class="dropdown-item" href="#">Fashion</a>
    					<a class="dropdown-item" href="#">Wellness</a>
    					<a class="dropdown-item" href="#">Dieting</a>
    				</div>
    				</li>
    			</ul>
    		</div>
    		</nav>
    	</div>
    </div>
    <div class="container-fluid">
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
    </section>
        
    </main>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/theme.js"></script>
	<?php
		require_once("layouts/footer.php");
	?>
</body>
    
</html>
