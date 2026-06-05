
<!doctype html>
<html lang="en">

<?php



include_once("/class/mailgun.class.php");


include_once("class/cleaninput.class.php");
include_once("class/user.class.php");
include_once("class/mysql.class.php");




   include_once("utils/defines.php");
   include_once("class/buildmenu.class.php");
   
include_once("config.php"); 
$mlocation = "location: /" . $SIGNUP_URL_EN;
$mthirdpart = "location: /" . $SIGNUP_THIRD_EN;

$buildmenu = new Mbuildmenu();   
//VF42
//$cid = 5;
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


$msql = new MMsql();

$muser = new MUser();


$cstring = $msql->dbconnect($DBUSER,$DBPASS,$DBNAME);


if (mysqli_connect_errno()) {
       printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}

$user_info = $muser->getInstagrowthUserInfo($cstring,$cid);
$urlmail = $muser->geturlmailid($cstring,$cid);
#$htags = $muser->updateInstagrowthHashtags($cstring,$cid, $chashtag1,$chashtag2,$chashtag3,$chashtag4,$chashtag5,$cremoteip,$cremotehost,$cremoteuseragent);
$hcommentaires = $muser->getInstagrowthCommentaires($cstring,$cid);

#$friendlist = $muser->getInstaGrowthFriends($cstring,$cid);
  
   
?>


  
<head>
    <meta charset="utf-8">
    <title>Manage your comments </title>
    <?php include_once("utils/instagrowth-les-influenceurs-header-meta-en.php"); ?>
    
</head>

<body data-smooth-scroll-offset="100">
    
  <div class="content--wrap">
    	
    <div class="main-container">
      
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                          
			  <h1><a href="<?php echo $BACKOFFICE_FRONT_EN; ?>" class="text--logo light color--primary"> InstaGrowth</a></h1>
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
			<center><h3 class="text--logo light color--black">Manage comments</h3></center>  
			  <div class="container">
			
			    <div class="row">

			      <!-- main start -->
			      <!-- ================ -->
			      <!--<div class="main col-md-12">-->
				<div class="main2">
			      <fieldset>
				
				
				  <form role="form" class="form-horizontal" method="post" id="cprofilconf" action="/bxoffice-traitement/update-list-commentaires-en.php">
				    <div id="shipping-information" class="space-bottom">
				      <div class="row">
					<!--<div class="col-lg-3">
					  <h3 class="text--logo light color--black">Démarrez votre inscription</h3>
					</div>-->
					
					<div class="col-lg-14 col-lg-offset-1">
					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Comments<small class="text-default"></small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputcommentaire1" id="inputcommentaire1" value="<?php echo $hcommentaires[0]; ?>" placeholder="<?php echo $hcommentaires[0]; ?>" >
					    </div>
					  </div>

					   
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Commentair2<small class="text-default"></small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputcommentaire2" id="inputcommentaire2" value="<?php echo $hcommentaires[1]; ?>" placeholder="<?php echo $hcommentaires[1]; ?>" >
					    </div>
					  </div>

					   
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Comment3<small class="text-default"></small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputcommentaire3" id="inputcommentaire3" value="<?php echo $hcommentaires[2]; ?>" placeholder="<?php echo $hcommentaires[2]; ?>" >
					    </div>
					  </div>
					   
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Comment4<small class="text-default"></small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputcommentaire4" id="inputcommentaire4" value="<?php echo $hcommentaires[3]; ?>" placeholder="<?php echo $commentaires[3]; ?>" >
					    </div>
					  </div>
 
					 
					 
					  
					  
					    
					  </div>
					  
					  <!--<div class="form-group has-feedback">
					    <label for="inputSecteur" name="inputSecteur" class="col-md-2 control-label">Secteur<small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <select name="inputSecteur" class="form-control" required>
						<option value="" selected disabled hidden>Votre secteur d'activit&eacute;</option>
						<option value="alimentation">Alimentation</option>
						<option value="industrie">Industrie</option>
						<option value="mode-lifestyle">Mode/lifestyle</option>
						<option value="politique">Politique</option>
						<option value="sport">Sport</option>
					      </select>
					    </div>
					   </div>
					</div>-->
					  
					<div class="form-group">
					    <div class="text-right">
					      <button type="submit" value="submit" border="none" class="btn bg--primary-light">Save</button> 
					      <!--<button type="submit" value="submit" >Soumettre </button>-->
					      
					    </div>
					    <!--<center><small><b> En vous enregistrant, vous acceptez les CGV et CGU du site </b></small></center>-->
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
			<!--<?php echo $buildmenu->buildmenuinstagrowthen($BACKOFFICE_EDIT_INSTAGRAM_EN,$BACKOFFICE_LOGOUT_EN,$BACKOFFICE_EDIT_ABO_EN,$BACKOFFICE_EDIT_COMPTE_EN,$MAIL_SUPPORT,$MAIL_SUBJECT_EN,$BACKOFFICE_FRONT_EN); ?>-->
			<?php echo $buildmenu->buildmenuinstagrowthen($BACKOFFICE_EDIT_INSTAGRAM_EN,$BACKOFFICE_LOGOUT_EN,$BACKOFFICE_EDIT_ABO_EN,$BACKOFFICE_EDIT_COMPTE_EN,$MAIL_SUPPORT,$MAIL_SUBJECT_EN,$BACKOFFICE_FRONT_EN); ?>
			<!--<?php echo $buildmenu->buildmenuinstagrowthen($BACKOFFICE_EDIT_INSTAGRAM,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO,$BACKOFFICE_EDIT_COMPTE,$MAIL_SUPPORT,$MAIL_SUBJECT,$BACKOFFICE_FRONT); ?>-->
			
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
    <!--<script src='https://cdn.useproof.com/proof.js?acc=FA2xKGldHxQujtbWGCaN5zm9L042' async></script>-->
</body>

</html>
