<?php
    // Start session
    session_start();
    require_once("../db/conn.php");
    $nama_user = "User Not Found";
    $username = "N/A";


    // Get the user ID from the URL
    if (isset($_GET['id_user'])) {
        $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

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
    <!-- Add your CSS and meta tags here -->
</head>
<body>
    <p>Your Username: <?php echo $username; ?></p>
    
    <!-- Add more profile details as needed -->

    <a href="logout.php">Logout</a> <!-- Link to logout page -->
</body>
</html>