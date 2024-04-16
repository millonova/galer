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
    		<li class="nav-item">
    		<a class="nav-link" href="http://localhost/galer/docs/post.php">Post</a>
    		</li>
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
    		<a class="nav-link for-logged-in" href="http://localhost/galer/docs/profile/user.php?id_user=<?php echo $_SESSION['id_user']; ?>"><img class="rounded-circle mr-2" src="http://localhost/galer/docs/assets/img/av.png" width="30"><span class="align-middle">Author</span></a>
    		</li>
    		<li class="nav-item dropdown">
    		<a class="nav-link" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    		<svg style="margin-top:10px;" class="_3DJPT" version="1.1" viewbox="0 0 32 32" width="21" height="21" aria-hidden="false" data-reactid="71"><path d="M7 15.5c0 1.9-1.6 3.5-3.5 3.5s-3.5-1.6-3.5-3.5 1.6-3.5 3.5-3.5 3.5 1.6 3.5 3.5zm21.5-3.5c-1.9 0-3.5 1.6-3.5 3.5s1.6 3.5 3.5 3.5 3.5-1.6 3.5-3.5-1.6-3.5-3.5-3.5zm-12.5 0c-1.9 0-3.5 1.6-3.5 3.5s1.6 3.5 3.5 3.5 3.5-1.6 3.5-3.5-1.6-3.5-3.5-3.5z" data-reactid="22"></path></svg>
    		</a>
    		<div class="dropdown-menu dropdown-menu-right shadow-lg" aria-labelledby="dropdown02">
    			<h4 class="dropdown-header display-4">Download Pintereso<br/> HTML Bootstrap Template</h4>
    			<div class="dropdown-divider">
    			</div>
    			<span class="dropdown-item"><a href="https://github.com/wowthemesnet/template-pintereso-bootstrap-html/archive/master.zip" class="btn btn-primary d-block"><i class="fa fa-download"></i> Download</a></span>
    		</div>
    		</li>
    	</ul>
    </div>
</nav>    