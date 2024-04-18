<?php
if ($_SESSION['hak_akses'] !== 'admin') {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var elements = document.getElementsByClassName("admin"); // Replace with the actual class name
            for (var i = 0; i < elements.length; i++) {
                elements[i].classList.add("d-none");
            }
        });
    </script>';
}
?>