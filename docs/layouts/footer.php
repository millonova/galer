<footer class="footer pt-5 pb-5 text-center">
    <div class="container">
        <div class="socials-media">
        <ul class="list-unstyled">
            <li class="d-inline-block ml-1 mr-1"><a href="#" class="text-dark"><i class="fa fa-facebook"></i></a></li>
            <li class="d-inline-block ml-1 mr-1"><a href="#" class="text-dark"><i class="fa fa-twitter"></i></a></li>
            <li class="d-inline-block ml-1 mr-1"><a href="#" class="text-dark"><i class="fa fa-instagram"></i></a></li>
            <li class="d-inline-block ml-1 mr-1"><a href="#" class="text-dark"><i class="fa fa-google-plus"></i></a></li>
            <li class="d-inline-block ml-1 mr-1"><a href="#" class="text-dark"><i class="fa fa-behance"></i></a></li>
            <li class="d-inline-block ml-1 mr-1"><a href="#" class="text-dark"><i class="fa fa-dribbble"></i></a></li>
        </ul>
        </div>
        <!--
            All the links in the footer should remain intact.
            You may remove the links only if you donate:
            https://www.wowthemes.net/freebies-license/
        -->
        <p>©  <span class="credits font-weight-bold">        
        <a target="_blank" class="text-dark" href="https://www.wowthemes.net/pintereso-free-html-bootstrap-template/"><u>Pintereso Bootstrap HTML Template</u> by WowThemes.net.</a>
        </span>
        </p>
    </div>
</footer>   
<?php
// Check if the user is logged in
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    // User is logged in, display navigation links for logged-in users
    echo '
    <script>
        document.querySelectorAll(".for-logged-in").forEach(element => {
            element.style.display = "block";
        });
        document.querySelectorAll(".for-not-logged-in").forEach(element => {
            element.style.display = "none";
        });
    </script>
    ';
} else {
    // User is not logged in, display navigation links for non-logged-in users
    echo '
    <script>
        document.querySelectorAll(".for-logged-in").forEach(element => {
            element.style.display = "none";
        });
        document.querySelectorAll(".for-not-logged-in").forEach(element => {
            element.style.display = "block";
        });
    </script>
    ';
}
?> 

