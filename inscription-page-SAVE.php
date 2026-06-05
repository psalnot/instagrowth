<!doctype html>
<html lang="fr">


  <?php include_once("utils/defines.php"); ?>
  <?php $mabo = $_GET['mvalue'];
	if (!isset($mabo))
		$mabo=3;
	
	?>
<head>
    <meta charset="utf-8">
    <title>Page d'inscription au service InstaGrowth</title>
<?php include_once("utils/instagrowth-les-influenceurs-header-meta.php"); ?>    
    
</head>

<body data-smooth-scroll-offset="100">
    
  <div class="content--wrap">
    	
    <div class="main-container">
      
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center">
                          
			  <h1><a href="/index.php" class="text--logo light color--primary"> InstaGrowth </a></h1>
			<!-- main-container start -->
			<!-- ================ -->
			     
                        </div>
                    </div>
                </div>
            </section>
	    
            <section id="shopping">
                <div class="container">
                    <div class="row">
                      <div class="col-sm-6 col-xs-12">
			
			
			<section class="main-container">
			  <center><h3 class="text--logo light color--black">Inscription  Gratuite</center></h3>
			      			  <p class="small type--fineprint" style="margin-top: 10px;"><center>Aucune facturation avant la fin de la p&eacute;riode de test.</center></p>
				
			  <div class="container">
			
			    <div class="row">

			      <!-- main start -->
			      <!-- ================ -->
			      <!--<div class="main col-md-12">-->
				<div class="main2">
			      <fieldset>
				
				
				  <form role="form" class="form-horizontal" method="post" id="cprofilconf" action="/bxoffice-traitement/signup-marque-test-produit-dev-2018.php">
				    <div id="shipping-information" class="space-bottom">
				      <div class="row">
					<!--<div class="col-lg-3">
					  <h3 class="text--logo light color--black">Démarrez votre inscription</h3>
					</div>-->
					
					<div class="col-lg-14 col-lg-offset-1">
					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Email<small class="text-default">*</small></label>
					    <div class="col-md-10">
					      
					      <input type="text" class="form-control" name="inputEmail" id="inputEmail" value="" placeholder="Votre adresse Email" required>
					    </div>
					  </div>
					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Nom<small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputName" id="inputName" value="" placeholder="Votre nom de famille" required>
					    </div>
					  </div>

					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Société<small class="text-default"></small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputCompany" id="inputCompany" value="" placeholder="Nom de votre société (Optionnel)">
					    </div>
					  </div>

					  
					  <div class="form-group">
					    <label for="shippingFirstName" class="col-md-2 control-label">Instagram<small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputInstagram" id="inputInstagram" value="" placeholder="Nom de votre compte Instagram" required>
					    </div>
					  </div>

					  
					  <div class="form-group">
					    <label for="shippingLastName" class="col-md-2 control-label">Tél<small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <input type="text" class="form-control" name="inputPhone" id="inputPhone" value="" placeholder="Votre numéro de téléphone" required>
					    </div>
					  </div>
					  <div class="form-group has-feedback">
					    <label for="shippingTel" class="col-md-2 control-label">Password<small class="text-default">*</small></label>
					    <div class="col-md-10">
					      <input type="password" class="form-control" name="inputPassword" id="inputPassword" value="" placeholder="Choisissez le mot de passe de votre compte" required>
					      <i class="fa fa-phone form-control-feedback"></i>
					    </div>
					  </div>
					  <input type="hidden" class="form-control" name="inputAbo" id="inputAbo" value="<?php echo $mabo; ?>" >
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
					      <button type="submit" value="submit" border="none" class="btn bg--primary-light">Enregistrer</button> 
					      <!--<button type="submit" value="submit" >Soumettre </button>-->
					      
					    </div>
					    <center><small><b> En vous enregistrant, vous acceptez les <a href="<?php echo $CGV_CGU; ?>"> CGV et CGU</a> du site </b></small></center>
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
                                  <h2>Démarrez vos <?php echo $NB_JOURS_TEST;?>  jours d'essai gratuit</h2> <span class="p">Nous accompagnons plus de  50.000 Influenceurs<img class="rating-xs" src="img/4-5-star.png"></span>
				  
				  <br><span class="p"><b>Vous ne serez pas factur&eacute; si vous annulez l'abonnement avant la fin de la p&eacute;riode de test.</b></span>
                                    <hr> </div>
                                <div class="col-lg-12">
                                  <ul class="benefits">
                                    <li> <span class="checkmark bg--green"></span> <span>Instagrowth vous aide à acquérir des followers sur Instagram</span> </li>
                                    <li> <span class="checkmark bg--green"></span> <span>Instagrowth augmente le "reach" de vos publications sur Instagram</span> </li>
				    
                                    <li> <span class="checkmark bg--green"></span> <span>Instagrowth vous met en relation avec un community manager qui vous accompagne</span> </li>
				    <li> <span class="checkmark bg--green"></span> <span>Instagrowth est édité par l'agence d'influence de référence <a href="<?php echo $REF_SITE;?>">Les influenceurs</a></span> </li>
                                  </ul>
                                  <a class="btn btn--primary btn--lg" href="<?php echo $CALENDLY_RDV; ?>"> <span class="btn__text">Contactez Nous</span> </a>
                                    <p class="small type--fineprint" style="margin-top: 10px;">Testez-nous gratuitement pendant <?php echo $NB_JOURS_TEST;?>  jours | Carte bleue requise </p>
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
    <!--<script src='https://cdn.useproof.com/proof.js?acc=FA2xKGldHxQujtbWGCaN5zm9L042' async></script>-->
</body>

</html>
