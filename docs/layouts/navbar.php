<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top px-5">
    <a class="navbar-brand font-weight-bolder mr-3" href="http://localhost/galer/docs/index.php"><img src="http://localhost/galer/docs/assets/img/logo.png"></a>
    <button class="navbar-light navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsDefault" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsDefault">
    	<ul class="navbar-nav ml-auto align-items-center">
    		<li class="nav-item">
    		<a class="nav-link active" href="http://localhost/galer/docs/index.php">Home</a>
    		</li>
			<?php
			 if ($_SESSION['hak_akses'] == 'admin') : ?>
				<li class="nav-item">
				<a class="nav-link text-danger" href="http://localhost/galer/docs/admin/data-foto.php">Data Foto</a>
				</li>
				<li class="nav-item">
				<a class="nav-link text-danger" href="http://localhost/galer/docs/admin/data-user.php">Data User</a>
				</li>
				<li class="nav-item">
				<a class="nav-link text-danger" href="http://localhost/galer/docs/admin/report.php">Report</a>
				</li>
			<?php endif; ?>
    		<li class="nav-item for-not-logged-in">
    		<a class="nav-link text-danger for-not-logged-in" href="http://localhost/galer/docs/login.php">Log in</a>
    		</li>
    		<li class="nav-item for-not-logged-in">
    		<a class="nav-link text-danger for-not-logged-in" href="http://localhost/galer/docs/register.php">Sign Up</a>
    		</li>
			<li class="nav-item for-logged-in">
    		<a class="nav-link text-danger for-logged-in" href="http://localhost/galer/docs/logout.php">Log out</a>
    		</li>
    		<li class="nav-item for-logged-in">
    		<a class="nav-link for-logged-in" href="http://localhost/galer/docs/profile/user.php?id_user=<?php echo $_SESSION['id_user']; ?>"><img class="rounded-circle mr-2" src="http://localhost/galer/docs/assets/img/av.png" width="30"><span class="align-middle"><?php echo $_SESSION['nama_user']; ?></span></a>
    		</li>
    	</ul>
    </div>
</nav>    