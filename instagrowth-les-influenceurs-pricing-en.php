<!doctype html>
<html lang="en">
  
  <?php include_once("utils/defines.php"); ?>
  <?php include_once("class/mysql.class.php"); ?>
     <?php include_once("class/user.class.php"); ?>
   <?php include_once("class/paiement.class.php"); ?>

<head>
    <meta charset="utf-8">
    <title>InstaGrowth pricing </title>
    <?php include_once("utils/instagrowth-les-influenceurs-header-meta-en.php"); ?>
    
</head>

<body data-smooth-scroll-offset="100">
    
    <div class="content--wrap">
        <div class="nav-container">
            <div>
                <div class="bar bar--sm visible-xs">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-3 col-sm-2"> <a href="/index-en.php" class="text--logo light color--primary"> InstaGrowth </a> </div>
                            <div class="col-xs-9 col-sm-10 text-right">
                                <a href="#" class="hamburger-toggle" data-toggle-class="#menu1;hidden-xs hidden-sm"> <i class="icon icon--sm stack-interface stack-menu"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <nav id="menu1" class="bar bar-1 hidden-xs bar--fixed">
                    <div class="container nav-separator">
                        <div class="row">
                            <div class="col-md-1 col-sm-2 hidden-xs">
                                <div class="bar__module"> <a href="/index-en.php" class="text--logo v2 light xl color--primary"> InstaGrowth </a> </div>
                            </div>
			    <!-- Header menu start -->
			    <?php include_once("utils/instagrowth-les-influenceurs-header-menu-en.php"); ?>
			    <!-- Header menu end -->
			    
			    
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                            <h1>Choose your plan !</h1>
                            <p class="lead">All plan can be cancelled at any time ! </p>
                            <a class="btn btn--lg btn--primary type--uppercase" href="<?php echo $SIGNUP_URL_EN; ?>"> <span class="btn__text btntxt-fix">
                                    Try us for free <span class="emoji">👈</span> </span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section id="pricing" class="space--xs">
              <div class="container">
		
		<?php
		
		$msql = new MMsql();
		
		$muser = new MUser();


		$cstring = $msql->dbconnect($DBUSER,$DBPASS,$DBNAME);


		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		      

		   $mpaiement = new Paiement();
		   $i = 0;
		   
		   $result = $mpaiement->getinstaGrowthTarifs($cstring);
		while ($row = mysqli_fetch_object($result)){
		
		
				$tarif_info_ht[$i] = $row->montant_mensuel_ht/100;
				$tarif_info_ttc[$i] = $row->montant_mensuel_ttc/100;
				$tarif_info_name[$i] = $row->name;
		$i++;}
		
		   ?>
                    <div class="row">
                        <div class="tabs-container">
                            <ul class="tabs mt--xs--2">
                                <li class="active">
                                    <div class="tab__title"> <span class="h4 text--logo light">Monthly</span> </div>
                                    <div class="tab__content mt--1">
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border">
                                                <div class="mb--1">
                                                    <h1 class="text--logo light"><?php echo $tarif_info_name[0]; ?></h1>
                                                </div>
                                                <div class="price"><span class="h2">86 &euro;<sup class="p">/Month</sup></span> </div>
                                                <div class="pricing-description"><span class="p">Great for personal accounts and upcoming influencers looking for organic growth</span></div>
                                                <hr>
                                                <ul class="perks">




						  
                                                  <li class="a">Dedicated community manager</li>
                                                    <li class="a">Target Hashtags</li>
						    <li class="a">Your own comments</li>
                                                    <li class="a">Target similar users</li>
                                                    <li class="a">Faster organic growth</li>
                                                    <li class="b">View instagram stories</li>
                                                    <li class="b">Target locations</li>
                                                    <li class="b">Exclude keywords</li>
						    <li class="b">Premium support</li>
						  <li class="b">Connect with influencers</li>
						  
                                                  
						  

						  
                                                  
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="<?php echo $SIGNUP_URL_EN . "?mvalue=3"; ?>"> <span class="btn__text">
                                    Try for free
                                </span> </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border"> <span class="label bg--primary-light">Populaire</span>
                                                <div class="mb--1">
                                                    <h1 class="text--logo light"><?php echo $tarif_info_name[1]; ?></h1>
                                                </div>
                                                <div class="price"><span class="h2">150 &euro; <sup class="p">/Month</sup></span> </div>
                                                <div class="pricing-description">A serious step up from the Lite plan. Faster growth for upcoming influencers and businesses.<span class="p"></div>
                                                <hr>
                                                <ul class="perks">

						  
                                                  <li class="a">Dedicated community manager</li>
                                                    <li class="a">Target Hashtags</li>
						    <li class="a">Your own comments</li>
                                                    <li class="a">Target similar users</li>
                                                    <li class="a">Faster organic growth</li>
                                                    <li class="a">View instagram stories</li>
                                                    <li class="a">Target locations</li>
                                                    <li class="a">Exclude keywords</li>
						  <li class="a">Premium support</li>
						  <li class="b">Connect with influencers</li>
						  
						    
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="<?php echo $SIGNUP_URL_EN . "?mvalue=4"; ?>"> <span class="btn__text">
						    Try for free
                                </span> </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border">
                                                <div class="mb--1">
                                                    <h1 class="text--logo light">Premium</h1>
                                                </div>
                                                <div class="price"><span class="h2">Quotation<sup class="p"></sup></span> </div>
                                                <div class="pricing-description"><span class="p">The fastest option for businesses and influencers that want to unlock their true Instagram potential.</div>
                                                <hr>
                                                <ul class="perks">


						  
                                                  <li class="a">Dedicated community manager</li>
                                                    <li class="a">Target Hashtags</li>
						    <li class="a">Your own comments</li>
                                                    <li class="a">Target similar users</li>
                                                    <li class="a">Faster organic growth</li>
                                                    <li class="a">View instagram stories</li>
                                                    <li class="a">Target locations</li>
                                                    <li class="a">Exclude keywords</li>
						  <li class="a">Premium support</li>
						  <li class="a">Connect with influencers</li>
						  
                                                </ul>
                                                <a class="btn btn--lg rounded btn--primary type--uppercase" href="<?php echo $CALENDLY_RDV; ?>"> <span class="btn__text">
						    Contact us
                                </span> </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!--<li>
                                    <div class="tab__title"> <span class="h4 text--logo light">Yearly</span> </div>
                                    <div class="tab__content mt--1">
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border"> <span class="label bg--primary-light">Save 25%</span>
                                                <div class="mb--1">
                                                    <h1 class="text--logo light">Lite</h1>
                                                </div> <span class="p small">Billed annually at $349</span>
                                                <div class="price"><span class="h2">$29<sup class="p">/Month</sup></span> </div>
                                                <div class="pricing-description"><span class="p">Great for personal accounts and upcoming influencers looking for organic growth.</span></div>
                                                <hr>
                                                <ul class="perks">
                                                    <li class="a">Account Manager</li>
                                                    <li class="a">Target Hashtags</li>
                                                    <li class="a">Target Similar Users</li>
                                                    <li class="b">Faster Organic Growth</li>
                                                    <li class="b">View Instagram Stories</li>
                                                    <li class="b">Target Locations</li>
                                                    <li class="b">Exclude Keywords</li>
                                                    <li class="b">Premium Support</li>
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="https://app.upleap.com/join"> <span class="btn__text">
                                    Try For Free
                                </span> </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border"> <span class="label bg--primary-light">Save 30%</span>
                                                <div class="mb--1">
                                                    <h1 class="text--logo light">Standard</h1>
                                                </div> <span class="p small">Billed annually at $579</span>
                                                <div class="price"><span class="h2">$49<sup class="p">/Month</sup></span> </div>
                                                <div class="pricing-description"><span class="p">A <strong>serious</strong> step up from the Lite plan. Faster growth for upcoming influencers and businesses.</span></div>
                                                <hr>
                                                <ul class="perks">
                                                    <li class="a">Account Manager</li>
                                                    <li class="a">Target Hashtags</li>
                                                    <li class="a">Target Similar Users</li>
                                                    <li class="a">Faster Organic Growth</li>
                                                    <li class="a">View Instagram Stories</li>
                                                    <li class="b">Target Locations</li>
                                                    <li class="b">Exclude Keywords</li>
                                                    <li class="b">Premium Support</li>
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="https://app.upleap.com/join"> <span class="btn__text">
                                    Try For Free
                                </span> </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border"> <span class="label bg--primary">Save 40%</span>
                                                <div class="mb--1">
                                                    <h1 class="text--logo light">Premium</h1>
                                                </div> <span class="p small">Billed annually at $709</span>
                                                <div class="price"><span class="h2">$59<sup class="p">/Month</sup></span> </div>
                                                <div class="pricing-description"><span class="p">The fastest option for businesses and influencers that want to unlock their true Instagram potential.</span></div>
                                                <hr>
                                                <ul class="perks">
                                                    <li class="a">Account Manager</li>
                                                    <li class="a">Target Hashtags</li>
                                                    <li class="a">Target Similar Users</li>
                                                    <li class="a">Faster Organic Growth</li>
                                                    <li class="a">View Instagram Stories</li>
                                                    <li class="a">Target Locations</li>
                                                    <li class="a">Exclude Keywords</li>
                                                    <li class="a">Premium Support</li>
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="https://app.upleap.com/join"> <span class="btn__text">
                                    Try For Free
                                </span> </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>-->
                            </ul>
                        </div>
                        <!--end of tabs container-->
                    </div>
                    <!--<div class="row">
                        <div class="col-sm-12">
                            <div class="">
                                <div class="alert__body container text-center"> <span class="p lead">Tous les tarifs sont Hors Taxes.</span> </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </section>
           			
            <section id="cta" class="bg--white space--sm unpad--bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="cta">
                                <h1> Start your <?php echo $NB_JOURS_TEST;?> days free trial</h1>
                                
                                <a class="btn btn--lg btn-fix btn--primary type--uppercase" href="<?php echo $SIGNUP_URL_EN; ?>"> <span class="btn__text btntxt-fix">
                                    Try us for free <span class="emoji">👈</span> </span>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                    <!--<div class="mt--3 mt--xs--2 pe--none"><img alt="Instagrowth community manager" src="img/pricing-footer.png"></div>-->
		    <!--<div class="mt--3 mt--xs--2 pe--none"><img alt="Instagrowth community manager" src="img/programme-million-influenceurs.jpg"></div>-->
                </div>
            </section>
	    <!-- Start footer -->
	    <?php include_once("utils/instagrowth-les-influenceurs-footer-en.php"); ?>
	    <!-- End footer -->
	    
        </div>
</div>

    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/upleap.min.js"></script>

</body>

</html>
