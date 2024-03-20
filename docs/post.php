<?php
    // Start session
    session_start();
    require_once("db/conn.php");
    $nama_user = "User Not Found";
    $username = "N/A";
    $edit_button = '';
    $delete_button = '';
    $comment_form = ''; // Initialize comment form as empty
    $judul_foto = "";
    $foto_deskripsi = "";
    $album_deskripsi = "";
    $tgl_unggah = "";
    $lokasi_file = "";
    $comments = array(); // Initialize comments array
    $isLiked = false; // Initialize isLiked variable

    // Get the user ID from the URL
    if (isset($_GET['id_foto'])) {
        $id_foto = isset($_GET['id_foto']) ? $_GET['id_foto'] : null;
        
        $query2 = "SELECT user.id_user FROM user INNER JOIN foto ON user.id_user = foto.id_user WHERE foto.id_foto = :id_foto";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
        $stmt2->execute();
        $user_data = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // User id found
            $id_user = $user_data['id_user'];
        } else {
            // User id not found
            $id_user = null; // Set id_user to null if not found
        }

        // Check if the session ID matches the ID retrieved from the URL
        if(isset($_SESSION['id_user'])) {
            // Display edit and delete buttons only if the user is the owner of the photo
            if ($_SESSION['id_user'] == $id_user) {
                $edit_button = '<button class="btn btn-danger mb-2" onclick="window.location.href=\'http://localhost/galer/docs/crud/editprofile.php\'">Edit Image</button>';
                $delete_button = '<button class="btn btn-outline-danger mb-2" onclick="window.location.href=\'http://localhost/galer/docs/crud/deletephoto.php?id_foto=' . $id_foto . '\'">Delete Image</button>';
            }
            // Display comment form for the owner of the photo or for any logged-in user
            $comment_form = '
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="isi_komen" class="form-label">Leave a comment:</label>
                        <textarea class="form-control" id="isi_komen" name="isi_komen" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="comment" value="comment" class="btn btn-primary">Submit</button>
                </form>';
        }

        // Query the database to fetch user information
        $user_query = "SELECT *, u.deskripsi AS foto_deskripsi, t.deskripsi AS album_deskripsi 
        FROM user AS j 
        INNER JOIN foto AS u ON j.id_user = u.id_user
        INNER JOIN album AS t ON u.id_album = t.id_album
        WHERE j.id_user = :id_user"; 
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT); // Bind id_user retrieved from URL
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // Assign user details if found
            $nama_user = $user_data['nama_user'];
            $username = $user_data['username'];
            $judul_foto = $user_data['judul_foto'];
            $foto_deskripsi = $user_data['foto_deskripsi'];
            $album_deskripsi = $user_data['album_deskripsi'];
            $tgl_unggah = $user_data['tgl_unggah'];

            // Fetch all photos associated with the user
            $photo_query = "SELECT lokasi_file FROM foto WHERE id_user = :id_user";
            $photo_stmt = $conn->prepare($photo_query);
            $photo_stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT); // Bind id_user retrieved from URL
            $photo_stmt->execute();

            // Fetch all photos associated with the user
            $photos = $photo_stmt->fetchAll(PDO::FETCH_ASSOC);
            $lokasi_fotos = array_column($photos, 'lokasi_file');
            
            // Check if any photos exist and assign the first one to $lokasi_file
            if (!empty($lokasi_fotos)) {
                $lokasi_file = $lokasi_fotos[0];
            }

			$comment_query = "SELECT k.*, u.username 
						FROM komentar k 
						INNER JOIN user u ON k.id_user = u.id_user 
						WHERE k.id_foto = :id_foto";
			$comment_stmt = $conn->prepare($comment_query);
			$comment_stmt->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
			$comment_stmt->execute();
			$comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Handle the case where the user ID was not found
            $nama_user = "User Not Found";
            $username = "N/A";
            $judul_foto = "";
            $foto_deskripsi = "";
            $album_deskripsi = "";
            $tgl_unggah = "";
            $lokasi_file = ""; // Set all user-related data to empty
        }

        // Check if the user is logged in to determine like status and show/hide likes and comments
        $likes_and_comments = '';
        if (isset($_SESSION['id_user'])) {
            $likes_and_comments = '
                <button id="likeButton" class="btn btn-primary mb-2">' . ($isLiked ? 'Unlike' : 'Like') . '</button>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Comments</h5>
                        <!-- Comment Form -->
                        ' . $comment_form . '
                        <!-- Comments Section -->
                        <div class="mt-4">';
            // Add comments if available
            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    $username = $comment['username'];
                    $isi_komen = $comment['isi_komen'];
                    $tgl_komen = $comment['tgl_komen'];
                    // Display the comment
                    $likes_and_comments .= '
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">' . $username . ' - ' . $tgl_komen . '</h6>
                                <p class="card-text">' . $isi_komen . '</p>
                            </div>
                        </div>';
                }
            } else {
                // No comments found
                $likes_and_comments .= '<p>No comments found.</p>';
            }
            // Close comments section and likes_and_comments variable
            $likes_and_comments .= '
                        </div>
                    </div>
                </div>';
        }


        // Output error message if necessary
        if ($id_user === null) {
            echo "Error: User not found.";
            exit();
        }
    }
	if(isset($_POST['comment'])) {
		// Validate the comment content
		$isi_komen = $_POST['isi_komen']; // Assuming isi_komen is the name of the textarea
		$tgl_komen = date("Y-m-d");
		// Insert the comment into the database
		$insert_comment_query = "INSERT INTO komentar (id_foto, id_user, isi_komen, tgl_komen) VALUES (:id_foto, :id_user, :isi_komen, :tgl_komen)";
		$insert_comment_stmt = $conn->prepare($insert_comment_query);
		$insert_comment_stmt->bindParam(':id_foto', $id_foto, PDO::PARAM_INT);
		$insert_comment_stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT); // Assuming id_user is stored in session
		$insert_comment_stmt->bindParam(':isi_komen', $isi_komen, PDO::PARAM_STR);
		$insert_comment_stmt->bindParam(':tgl_komen', $tgl_komen, PDO::PARAM_STR);
		
		// Execute the insert query
		if($insert_comment_stmt->execute()) {
			// Redirect or display a success message
			header("Location: post.php?id_foto=$id_foto"); // Redirect back to the post after comment submission
			exit();
		} else {
			// Handle the case where comment insertion failed
			echo "Failed to insert comment.";
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
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
        require_once("layouts/navbar.php");
    ?>
    <main role="main">       
    <section class="bg-gray200 pt-5 pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <article class="card mb-5">
                <img class="card-img-top mb-2" src="<?php echo str_replace($_SERVER["DOCUMENT_ROOT"], "", $lokasi_file); ?>" alt="Card image">
                <div class="card-body">
                    <h1 class="card-title display-4">
                    <?php echo $judul_foto; ?></h1>
                    <p>
                        <?php echo $foto_deskripsi; ?>
                    </p>
                    <p>
                        Uploaded by : <?php echo $nama_user; ?>
                    </p>
                    <p>
                        Uploaded at : <?php echo $tgl_unggah; ?>
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-lg-12 align-items-center">
                            <!-- Inside your HTML body -->
                            <?php
                                echo $likes_and_comments;
                                echo $edit_button;
                                echo $delete_button;
                            ?>
                        </div>
                    </div>
                </div>
                </article>
            </div>
        </div>
    </div>
    </section>
        
    </main>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php
        require_once("layouts/footer.php");
    ?>
    <script>
        // Inside your JavaScript script tag
        $(document).ready(function() {
            $('#likeButton').click(function() {
                var fotoId = <?php echo $id_foto; ?>;
                $.ajax({
                    url: 'like.php',
                    type: 'POST',
                    data: { foto_id: fotoId },
                    success: function(response) {
                        if (response === 'liked') {
                            $('#likeButton').text('Unlike');
                        } else if (response === 'unliked') {
                            $('#likeButton').text('Like');
                        } else if (response === 'login_required') {
                            // Handle case where user needs to login to like
                            alert('Please login to like the photo.');
                        } else {
                            // Handle other response scenarios
                            console.error('Unexpected response:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>