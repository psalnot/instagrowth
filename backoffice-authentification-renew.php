<!doctype html>
<html lang="fr">

<?php



   
#ini_set('display_errors',1); error_reporting(E_ALL);


session_cache_limiter('nocache');
include_once("class/cleaninput.class.php");
include_once("class/user.class.php");

include_once("class/mysql.class.php");
include_once("class/buildmenu.class.php");



include_once("utils/defines.php");
include_once("config.php"); 
$mlocation = "location: /" . $BACKOFFICE_FRONT;
$mthirdpart = "location: /" . $SIGNUP_THIRD;



$msql = new MMsql();

$muser = new MUser();
$buildmenu = new Mbuildmenu();

$cstring = $msql->dbconnect($DBUSER,$DBPASS,$DBNAME);


if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}
//$cid = 6;




session_start();
if (  isset($_SESSION['name42']))
{
   header($mlocation);
   die();
}


#$user_info = $muser->getInstagrowthUserInfo($cstring,$cid);

#echo " Information " . $user_info[0];

?>


  
<head>
    <meta charset="utf-8">
    <title>Authentification InstaGrowth</title>

    <?php include_once("utils/instagrowth-les-influenceurs-header-meta.php"); ?>
    
</head>

<body data-smooth-scroll-offset="100">
    
  <div class="content--wrap">
    	
    <div class="main-container">
      
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                          
			  <h1><a href="/index.php" class="text--logo light color--primary"> InstaGrowth</a></h1>
			<!-- main-container start -->
			<!-- ================ -->
			     <!--<p class="lead">Choisissez votre formule !<br> Vous pouvez résilier votre abonnement à tout moment. </p>
                            <a class="btn btn--lg btn--primary type--uppercase" href="https://app.upleap.com/join"> <span class="btn__text btntxt-fix">
                                    Try Us For Free First <span class="emoji">👈</span> </span>
                            </a>-->
                        </div>
                    </div>
                </div>
            </section>
	    
            <!--<section id="trusted" class="bg--white">
                <div class="container">
                    <div class="row opacity--3 text-center">
                        <div class="col-sm-3 col-xs-6"> <img alt="Image" class="image--sm" src="img/clients/customer-1.png"> </div>
                        <div class="col-sm-3 col-xs-6"> <img alt="Image" class="image--sm" src="img/clients/customer-2.png"> </div>
                        <div class="col-sm-3 col-xs-6 mt--xs--1"> <img alt="Image" class="image--sm" src="img/clients/customer-3.png"> </div>
                        <div class="col-sm-3 col-xs-6  mt--xs--1"> <img alt="Image" class="image--sm" src="img/clients/customer-4.png"> </div>
                    </div>
                </div>
            </section>-->
            <section id="shopping">
                <div class="container">
                    <div class="row">
                      <div class="col-sm-6 col-xs-12">
			
			
			<section class="main-container">
			  <center><h3 class="text--logo light color--black">Authentifiez-vous
			      <br><small>Si vous êtes déjà insccris sur InstaGrowth</small></h3></center>
			  <div class="container">
			
			    <div class="row">

			      <!-- main start -->
			      <!-- ================ -->
			      <!--<div class="main col-md-12">-->
				<div class="main2">
			      <fieldset>
				
				
				  <form role="form" class="form-horizontal" method="post" id="cprofilconf" action="<?php echo "/" .  $DEV_BACKOFFICE_AUTHENTIFICATION_RENEW; ?>">
				    <div id="shipping-information" class="space-bottom">
				      <div class="row">
					<!--<div class="col-lg-3">
					  <h3 class="text--logo light color--black">Démarrez votre inscription</h3>
					</div>-->
					
					<div class="col-lg-14 col-lg-offset-1">
					 

					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label"><b>Compte</b><small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <input type="email" class="form-control" name="inputEmail" id="inputEmail" value="" placeholder="Votre adresse mail" required>
					    </div>

					  </div>
					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label"><b>Password</b><small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <input type="password" class="form-control" name="inputPassword" id="inputPassword" value="" placeholder="Votre mot de passe" required>
					    </div>

					   
					  </div>

					  
					<div class="form-group">
					    <div class="text-right">
					      <button type="submit" value="submit" border="none" class="btn bg--primary-light">Authentification</button> 
					      <!--<button type="submit" value="submit" >Soumettre </button>-->
					      
					    </div>
					    <center><small><b> En vous authentifiant, vous acceptez les CGV et CGU du site </b></small></center>
					    <center><small><b> <a href="<?php echo $BACKOFFICE_FRONT_RESET_PASSWORD; ?>">Mot de passe oublié</a>  </b></small></center>
					</div>
					   
					  
					 
					  
					  
					    
					  </div>
					  
					</div>
					
					
				  </form>
			      </fieldset>
			      <!--<div class="text-right">
				<a href="shop-cart.html" class="btn btn-group btn-default"><i class="icon-left-open-big"></i> Go Back To Cart</a>
				<a href="shop-checkout-payment.html" class="btn btn-group btn-default">Next Step <i class="icon-right-open-big"></i></a>
			      </div>-->
		              </div>
			      
				
			
			    </div>
			  </div>
			</section>
                        
                        
			</div>
			
                        <div class="col-sm-5 col-sm-offset-1 col-xs-12 mt--xs--2">
                            <div class="pricing pricing-2 boxed boxed--border boxed--lg row no-gutters">
                                <div class="col-lg-12">
                                    <h2>Authentification</h2> <span class="p">Nous accompagnons plus de  50.000 Influenceurs<img class="rating-xs" src="img/4-5-star.png"></span>
                                    <hr> </div>
                                <div class="col-lg-12">
                                    <ul class="benefits">
                                        <li> <span class="checkmark bg--green"></span> <span>Instagrowth vous aide à acquérir des followers</span> </li>
                                        <li> <span class="checkmark bg--green"></span> <span>Instagrowth augmente le "reach" de vos publications</span> </li>
                                        <li> <span class="checkmark bg--green"></span> <span>Instagrowth vous met en relation avec un community manager</span> </li>
                                        
                                    </ul>
                                    <a class="btn btn--primary btn--lg" href="<?php echo $SIGNUP_URL; ?>"> <span class="btn__text">Inscription gratuite</span> </a>
                                    <p class="small type--fineprint" style="margin-top: 10px;">100% sans risque - Testez notre solution gratuitement pendant <?php echo $NB_JOURS_TEST;?>  jours</p>
                                </div>
                                <!--end of pricing-->
                            </div>
                        </div>
	
                    </div>
                </div>
            </section>
            <section id="cta" class="bg--white space--sm unpad--bottom">
                <div class="container">
                  <div class="row">
		    	
                    </div>
                    <div class="mt--3 mt--xs--2 pe--none"><img alt="Instagrowth community manager" src="img/pricing-footer.png"></div>
		    <!--<div class="mt--3 mt--xs--2 pe--none"><img alt="Instagrowth community manager" src="img/programme-million-influenceurs.jpg"></div>-->
                </div>
            </section>
	    <!-- Start footer -->
	    <?php include_once("utils/instagrowth-les-influenceurs-footer.php"); ?>
	    <!-- End footer -->
	    
        </div>
    </div>
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/upleap.min.js"></script>

</body>

</html>
