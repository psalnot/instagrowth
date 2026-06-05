<!doctype html>
<html lang="en">

<?php



   
#ini_set('display_errors',1); error_reporting(E_ALL);


session_cache_limiter('nocache');
include_once("class/cleaninput.class.php");
include_once("class/user.class.php");

include_once("class/mysql.class.php");




   include_once("utils/defines.php");
   include_once("class/buildmenu.class.php");
include_once("config.php"); 
$mlocation = "location: " . $SIGNUP_URL;
$mthirdpart = "location: /" . $SIGNUP_THIRD;



$msql = new MMsql();

$muser = new MUser();
$buildmenu = new Mbuildmenu();

$cstring = $msql->dbconnect($DBUSER,$DBPASS,$DBNAME);


if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}


//RF4242
//A decommenter après developpement
//$cid=5;
/*
session_start();
if ( ! isset($_SESSION['name42']))
{

	header($mlocation);
	die();
}
$cemail = $_SESSION['name42'];
       
$cid = $_SESSION['id42'];
$cname = $_SESSION['familyname42'];
*/


session_start();
if ( ! isset($_SESSION['name42']))
{

	header($mlocation);
	die();
}
$cemail = $_SESSION['name42'];
       
$cid = $_SESSION['id42'];
$cname = $_SESSION['familyname42'];



$user_info = $muser->getInstagrowthUserInfo($cstring,$cid);
$user_abo = $muser->getInstagrowthUserAbo($cstring,$cid);

$urlmail = $muser->geturlmailid($cstring,$cid);
$htags = $muser->getInstaGrowthHashtags($cstring,$cid);
$friendlist = $muser->getInstaGrowthFriends($cstring,$cid);

$test_period_end = strtotime($user_abo[7]);

$mtoday = time();
$info_test = 0;
if ( ! $user_abo[1] )
{
$user_abo_status = $muser->getInstagrowthUserAboStatus($cstring,$cid,0);

$montant_ht = $user_abo_status[1]/100 . " &euro;";
$montant_ttc = $user_abo_status[2]/100 . " &euro;";

}
else
{

$montant_ht = $user_abo[1]/100 . " &euro;";
$montant_ttc = $user_abo[2]/100 . " &euro;";

}


?>


  
<head>
    <meta charset="utf-8">
    <title>Subscription fees </title>
    <?php include_once("utils/instagrowth-les-influenceurs-header-meta-en.php"); ?>
    
</head>

<body data-smooth-scroll-offset="100">
    
  <div class="content--wrap">
    	
    <div class="main-container">
      
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                          <h1><a href="<?php echo "/" . $BACKOFFICE_FRONT_EN; ?>" class="text--logo light color--primary"> InstaGrowth</a></h1>
			  
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
	    
            <section id="shopping">
                <div class="container">
                    <div class="row">
                      <div class="col-sm-6 col-xs-12">
			
			
			<section class="main-container">
			  
           
			  <?php
			     
			     if ($mtime < $test_period_end){
			     
			     $info_test = 1;		  
			     	      
			     echo "<div class=\"row\">";
			    
                             echo "<div class=\"col-sm-12\">";
                             echo "<div align=\"center\" class=\"alert rounded bg--primary-light\">";
                             echo "<div class=\"alert__body container text-center\">";
			     echo "<span class=\"text-black\">Free trial until : " .  date('m/d/Y',$test_period_end) .  " </span> </div>";
                             echo "</div>";
                             echo "</div>";
			
			     echo "</div>";
			

			
			
			     
			     }
			     ?>
			 <p></p>
			  <div class="row">
			    
                            <div class="col-sm-12">
                              <div align="left" class="alert rounded bg--secondary">
                                <div class="alert__body container">
				  <form>
				    <div class="form-group row">
				      <!--<div class="form-group col-md-6">
					<label for="inputEmail4">Amount excluding tax</label>
					<input type="email" class="form-control" id="inputEmail4" placeholder="<?php echo $montant_ht ?>" disabled>
				      </div>-->
				      <div class="form-group col-md-6">
					<label for="inputPassword4">Amount </label>
					<input type="text" class="form-control" id="inputPassword4" placeholder="<?php echo $montant_ttc; ?>" disabled>
				      </div>
				    </div>
				    <div class="form-group">
				      <!--<center><a class="btn btn--primary " href="<?php echo $CALENDLY_RDV; ?>"> <span class="btn__text">Changer d'abonnement</span> </a></center>-->
				      
				      
				    </div>
				    <div class="form-group">
				      <center><a class="btn btn-info" href="<?php echo "/" . $BACKOFFICE_UPDATE_ABO_EN;  ?>">
					  <span class="btn__text">
					    <?php
					       if ($user_abo[8]) { echo "Cancel subscription"; }
					       else { echo "Activate subscription";}
					       
					       ?>
					       </span> </a></center>
					       <?php
					       if ($info_test){
					       echo "<center><small> </small></center>";
					       }
					       ?>
				      
				    </div>
				    
				    </form>
				  
				  

				</div>
				
                  <!--<div class="row mt--2 ">
		    
		    
                    <div class="col-sm-4">
		    <span ><b>A l'inscription</b>  </span>    

		    </div>

		    
                    <div class="col-sm-4">
		      <span><b>10000</b> abonné(s)</span>  

		    </div>

		    
                    <div class="col-sm-4">
		      <span><b>100000 </b>follower(s)</span>  
                    </div>
		    
		  </div>-->
		


				
                  <!--<div class="row mt--1 ">
		    
		    
                    <div class="col-sm-4">
		      

		    </div>

		    
                    <div class="col-sm-4">
		      <span class="p text--logo text-info"><center>Editer votre mot de passe Instagram</center></span>  

		    </div>

		    
                    <div class="col-sm-4">
		      
                    </div>
		    
		  </div>

                  </div>-->
                            </div>
			
			  </div>

			   
	   		  
			  <div class="container">
			
			    <div class="row">
			          
			      <!-- main start -->
			      <!-- ================ -->
			      <!--<div class="main col-md-12">-->
			      <div class="main2">
				
			      <!--<div class="text-right">
				<a href="shop-cart.html" class="btn btn-group btn-default"><i class="icon-left-open-big"></i> Go Back To Cart</a>
				<a href="shop-checkout-payment.html" class="btn btn-group btn-default">Next Step <i class="icon-right-open-big"></i></a>
			      </div>-->
		              </div>
			      
				
			
			    </div>
			  </div>
			</section>
                        
                        
			</div>
			<?php include_once("/utils_backoffice/backoffice-menu.php");?>
			<!--<?php echo $buildmenu->buildmenuinstagrowthen($BACKOFFICE_EDIT_INSTAGRAM_EN,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO_EN,$BACKOFFICE_EDIT_COMPTE,$MAIL_SUPPORT,$MAIL_SUBJECT,$BACKOFFICE_FRONT_EN); ?>-->
			<?php echo $buildmenu->buildmenuinstagrowthen($BACKOFFICE_EDIT_INSTAGRAM_EN,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO_EN,$BACKOFFICE_EDIT_COMPTE_EN,$MAIL_SUPPORT,$MAIL_SUBJECT_EN,$BACKOFFICE_FRONT_EN); ?>
			
			
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
