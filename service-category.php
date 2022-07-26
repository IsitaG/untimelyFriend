<?php
    
    session_start();
    $isLoggedIn = false;
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : "";

    // First check user is already logged in or not
    if(isset($_SESSION['valid']) && $_SESSION['valid']){
        $isLoggedIn = true;
    }

    // Include database file
    require_once("class/database.php");
    $db = new database();

    $settings = $db->getSettings();
    $settings = $settings[0];
    $hasData = false;
    $category = false;
    $socialWorkers = false;

    // Get all categories for dropdown
    if(isset($_GET["category_id"]) && !empty($_GET["category_id"])){
        $category = $db->getServiceCategoryById($_GET["category_id"]);
        if($category){
            $category = $category[0];

            // Get Social worker list under selected category
            $socialWorkers = $db->getAllSocialWorkersById($_GET["category_id"]);
        }
    }


?>
<?php include("header.php"); ?>

    <header id="masthead" class="site-header">
        <nav id="primary-navigation" class="site-navigation">
            <div class="container">

                <div class="navbar-header">
                   
                    <a class="site-title"><span><?php $projectName = explode(" ", $settings["project_name"]); echo $projectName[0]; ?></span><?php echo $projectName[1]; ?></a>

                </div><!-- /.navbar-header -->

                <div class="collapse navbar-collapse" id="agency-navbar-collapse">

                    <ul class="nav navbar-nav navbar-right">

                        <li><a href="index.php">Home</a></li>
                        <!--
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages<i class="fa fa-caret-down hidden-xs" aria-hidden="true"></i></a>

                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                              <li><a href="portfolio.html">Portfolio</a></li>
                              <li><a href="blog.html">Blog</a></li>
                            </ul>

                        </li>
                        -->
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><?php if($role == "Admin"): ?><a href="dashboard">Dashboard</a><?php elseif($role == "Customer"): ?> <a href="my-account.php">My Account</a> <?php endif; ?></li>
                        <li><?php if($isLoggedIn): ?><a href="logout.php">Log Out</a><?php else: ?> <a href="login">Login</a> <?php endif; ?></li>

                    </ul>

                </div>

            </div>   
        </nav><!-- /.site-navigation -->
    </header><!-- /#mastheaed -->

     <div id="hero" class="hero overlay subpage-hero contact-hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1><?php if($category){ echo $category["name"]; } else{ echo "Wrong Category"; } ?></h1>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active">Category</li>
                </ol>
            </div><!-- /.hero-text -->
        </div><!-- /.hero-content -->
    </div><!-- /.hero -->

    <main id="main" class="site-main">

        <section class="site-section section-services gray-bg text-center">
            <div class="container">
                <h2 class="heading-separator"><?php echo $settings["social_worker_title"]; ?></h2>
                <p class="subheading-text"><?php echo $settings["social_worker_subtitle"]; ?></p>
                <div class="row">
                    <?php if($socialWorkers): ?>
                    <?php foreach($socialWorkers as $row): ?>
                    <div class="col-md-3 col-xs-6">
                        <a href="single-worker.php?worker_id=<?php echo $row["id"]; ?>">
                            <div class="single-service">
                                <img class="img-thumbnail" src="<?php echo "uploads/".$row["photo"]; ?>" alt="">
                                <h3 class="service-title"><?php echo $row["name"]; ?></h3>
                                <p class="worker-info"> <?php echo $row["address"]; ?></p>
                                <a href="book.php?worker_id=<?php echo $row["id"]; ?>" class="btn btn-border mb-10">Book Now</a>
                            </div><!-- /.service -->
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="error-text">No Social Worker Found</div>
                    <?php endif; ?>
                </div>
            </div>
        </section><!-- /.section-services -->
    </main><!-- /#main -->

    <footer id="colophon" class="site-footer">
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <p>&copy; <?php echo $settings["project_name"]; ?> | All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.copyright -->
    </footer><!-- /#footer -->

<?php include("footer.php"); ?>