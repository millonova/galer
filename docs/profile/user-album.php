<?php
    // Start session
    session_start();
    require_once("../db/conn.php");
    $nama_user = "User Not Found";
    $username = "N/A";
    $edit_button = '';
    $post_button = '';

    // Get the user ID from the URL
    if (isset($_GET['id_user'])) {
        $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

        // Check if the session ID matches the ID retrieved from the URL
        $edit_button = '';
        if(isset($_SESSION['id_user']) && $_SESSION['id_user'] == $id_user) {
            $edit_button = '<button class="btn btn-danger mb-2" onclick="editProfile()">Edit Profile</button>';
            $post_button = '<button class="btn btn-outline-danger mb-2" onclick="postPhoto()">Post Image</button>';
            $post1_button = '<button class="btn btn-outline-danger mb-2" onclick="postAlbum()">Post Album</button>';
        }

        // Query the database to fetch user information
        $user_query = "SELECT * FROM user WHERE id_user = :id_user";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Assign user details if found
            $nama_user = $user_data['nama_user'];
            $username = $user_data['username'];

            // Query the database to fetch photos associated with the user
            $photo_query = "SELECT Lokasi_File FROM foto WHERE id_user = :id_user";
            $photo_stmt = $conn->prepare($photo_query);
            $photo_stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $photo_stmt->execute();

            // Fetch all photos associated with the user
            $photos = $photo_stmt->fetchAll(PDO::FETCH_ASSOC);
            $lokasi_fotos = array_column($photos, 'lokasi_file');
        } else {
            // Handle the case where the user ID was not found
            echo "User not found.";
        }
    }

    // Close the database connection (if needed, depending on your connection method)
    // $conn = null;
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
        
    
    <div class="jumbotron border-round-0 min-50vh" style="background-image:url(https://images.unsplash.com/photo-1522204657746-fccce0824cfd?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=84b5e9bea51f72c63862a0544f76e0a3&auto=format&fit=crop&w=1500&q=80);">
    </div>
    <div class="container mb-4">
    	<img src="../assets/img/av.png" class="mt-neg100 mb-4 rounded-circle" width="128">
        <div class="row">
            <div class="col-lg-10">
    	        <h1 class="font-weight-bold title"><?php echo $nama_user; ?></h1>
                <p>
                    <?php echo $username; ?>
                </p>
            </div>
            <div class="col-lg-2 align-items-center">
                <?php echo $edit_button; ?>
                <?php echo $post_button; ?>
                <?php echo $post1_button; ?>
            </div>
        </div>
        <ul class="nav nav-underline">
        <li class="nav-item">
            <a class="nav-link text-black fw-semibold" aria-current="page" href="#">Image</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Album</a>
        </li>
        </ul>
    </div>
    <div class="container-fluid mb-5">
    	<div class="row">
    		<div class="card-columns">
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
    			<div class="card card-pin">
					<ul class="list-unstyled text-center my-2">
						<a href="" class="text-decoration-none">Folder 1
						</a>
					</ul>
    			</div>
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