<?php
// Start the session
session_start();
require_once("db/conn.php");

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM foto AS j 
        INNER JOIN user AS u ON j.id_user = u.id_user
        INNER JOIN album AS t ON j.id_album = t.id_album
        WHERE j.judul_foto LIKE :search
        ORDER BY j.tgl_unggah ASC";

$stmt = $conn->prepare($sql);
$searchTerm = "%{$search}%";
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
		<form method="GET">
			<input type="text" name="search" placeholder="Search images..." class="form-control mb-3" value="<?php echo htmlspecialchars($search); ?>">
			<button type="submit" class="btn btn-primary">Search</button>
		</form>
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
	<div class="text-center" id="load-more-container">
		Loading more images...
	</div>
        
    </main>
	<?php
		require_once("layouts/footer.php");
	?>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/theme.js"></script>
	<script>
	$(window).scroll(function() {
		if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
			loadMoreImages();
		}
	});

	var offset = 0;
	function loadMoreImages() {
		offset += 10; // assuming each load brings 10 items
		$.get("load-image.php", {offset: offset}, function(data) {
			if (data.trim().length > 0) {
				$(".card-columns").append(data);
			}
		});
	}
	</script>




</body>
    
</html>
