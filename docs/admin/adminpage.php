<?php
if ($_SESSION['hak_akses'] == 'client') {
    echo "<script>alert('Data tidak valid.');window.location='data-user.php';</script>";
}
?>