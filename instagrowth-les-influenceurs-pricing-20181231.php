<!doctype html>
<html lang="fr">
  
  <?php include_once("utils/defines.php"); ?>
  <?php include_once("class/mysql.class.php"); ?>
     <?php include_once("class/user.class.php"); ?>
   <?php include_once("class/paiement.class.php"); ?>

<head>
    <meta charset="utf-8">
    <title>Tarification de la solution Instagrowth by Les influenceurs </title>
    <?php include_once("utils/instagrowth-les-influenceurs-header-meta.php"); ?>
    
</head>

<body data-smooth-scroll-offset="100">
    
    <div class="content--wrap">
        <div class="nav-container">
            <div>
                <div class="bar bar--sm visible-xs">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-3 col-sm-2"> <a href="/index.php" class="text--logo light color--primary"> InstaGrowth </a> </div>
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
                                <div class="bar__module"> <a href="/index.php" class="text--logo v2 light xl color--primary"> InstaGrowth </a> </div>
                            </div>
			    <!-- Header menu start -->
			    <?php include_once("utils/instagrowth-les-influenceurs-header-menu.php"); ?>
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
                            <h1>Tarification simple</h1>
                            <p class="lead">Choisissez votre formule !<br> Vous pouvez résilier votre abonnement à tout moment. </p>
                            <a class="btn btn--lg btn--primary type--uppercase" href="<?php echo $SIGNUP_URL; ?>"> <span class="btn__text btntxt-fix">
                                    Testez nous gratuitement <span class="emoji">👈</span> </span>
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
                                    <div class="tab__title"> <span class="h4 text--logo light">Mensuel</span> </div>
                                    <div class="tab__content mt--1">
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border">
                                                <div class="mb--1">
                                                    <h1 class="text--logo light"><?php echo $tarif_info_name[0]; ?></h1>
                                                </div>
                                                <div class="price"><span class="h2"><?php echo $tarif_info_ht[0]; ?> &euro; HT<sup class="p">/Mois</sup></span> </div>
                                                <div class="pricing-description"><span class="p">Solution idéale pour les influenceurs en devenir ou TPE cherchant à acquérir rapidement des followers.</span></div>
                                                <hr>
                                                <ul class="perks">




						  
                                                    <li class="a">Community manager dédié</li>
                                                    <li class="a">Hashtags ciblés</li>
                                                    <li class="a">Cible des utilisateurs similaires</li>
                                                    <li class="a">Croissance organique du compte</li>
                                                    <li class="b">Consultation des stories Instagram</li>
                                                    <li class="b">Ciblage de zones géographiques</li>
                                                    <li class="b">Exclusion de mots clés et comptes</li>
						    <li class="b">Plateforme de paiement</li>
						    <li class="b">Mise en relation influenceurs</li>
						    <li class="b">Support téléphonique</li>
                                                    <li class="a">Support par mail</li>
						  

						  
                                                  
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="<?php echo $SIGNUP_URL . "?mvalue=1"; ?>"> <span class="btn__text">
                                    Abonnement
                                </span> </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border"> <span class="label bg--primary-light">Populaire</span>
                                                <div class="mb--1">
                                                    <h1 class="text--logo light"><?php echo $tarif_info_name[1]; ?></h1>
                                                </div>
                                                <div class="price"><span class="h2"><?php echo $tarif_info_ht[1]; ?> &euro; HT<sup class="p">/Mois</sup></span> </div>
                                                <div class="pricing-description"><span class="p">Solution idéale pour les restaurateurs, hôteliers, propriétairs de lieux souhaitant acquérir des "followers" localisés tout en bénéficiant d'une croissance organique importante.</div>
                                                <hr>
                                                <ul class="perks">
						  
                                                    <li class="a">Community manager dédié</li>
                                                    <li class="a">Hashtags ciblés</li>
                                                    <li class="a">Cible des utilisateurs similaires</li>
                                                    <li class="a">Croissance organique du compte</li>
                                                    <li class="a">Consultation des stories Instagram</li>
                                                    <li class="a">Ciblage des zones géographiques</li>
                                                    <li class="a">Exclusion de mots clés et comptes</li>
						    <li class="b">Plateforme de paiement</li>
						    <li class="b">Mise en relation influenceurs</li>
						    <li class="a">Support téléphonique</li>
                                                    <li class="a">Support par mail</li>
						    
                                                </ul>
                                                <a class="btn btn--lg btn--primary type--uppercase" href="<?php echo $SIGNUP_URL . "?mvalue=2"; ?>"> <span class="btn__text">
						    Abonnement
                                </span> </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="pricing pricing-1 boxed boxed--lg boxed--border">
                                                <div class="mb--1">
                                                    <h1 class="text--logo light">Premium</h1>
                                                </div>
                                                <div class="price"><span class="h2">Sur devis<sup class="p"></sup></span> </div>
                                                <div class="pricing-description"><span class="p">Solution idéale pour les professionnels souhaitant acquérir des followers et <b>monétiser leur audience</b>. Votre community manager vous met en relation avec des influenceurs, l'agence met à votre disposition une plateforme de paiement sur mesure et organise des événements dans votre établissement. Une partie de la rémunération de l'agence se fait en <b>fonction du chiffre d'affaires généré</b>.</div>
                                                <hr>
                                                <ul class="perks">

						  
                                                    <li class="a">Community manager dédié</li>
                                                    <li class="a">Hashtags ciblés</li>
                                                    <li class="a">Cible des utilisateurs similaires</li>
                                                    <li class="a">Croissance organique du compte</li>
                                                    <li class="a">Consultation des stories Instagram</li>
                                                    <li class="a">Ciblage des zones géographiques</li>
                                                    <li class="a">Exclusion de mots clés et comptes</li>
						    
						    <li class="a">Plateforme de paiement</li>
						    <li class="a">Mise en relation influenceurs</li>
						    <li class="a">Support téléphonique</li>
                                                    <li class="a">Support par mail</li>
						  
                                                </ul>
                                                <a class="btn btn--lg rounded btn--primary type--uppercase" href="<?php echo $CALENDLY_RDV; ?>"> <span class="btn__text">
						    Nous contacter
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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="">
                                <div class="alert__body container text-center"> <span class="p lead">Tous les tarifs sont Hors Taxes.</span> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
           
            <section class="space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert bg--secondary">
                                <div class="alert__body container text-center"> <span class="lead">Vous êtes une agence de communication et cherchez un plan spécifique ? <a href="<?php echo $CALENDLY_RDV; ?>">Contactez nous</a> nous serons en mesure de vous proposer une solution adaptée à vos objectifs.</span> </div>
                            </div>
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
                        <div class="col-sm-6 col-xs-12"> <span class="color--primary small">Vous vous posez encor des questions sur Instagrowth ?</span>
                          <h1>Questions fréquentes</h1>
			  <h5>Qui édite la solution Instagrowth  ?</h4>
<p> La solution Instagrowth est éditée par l'agence Les influenceurs, l'agence d'influence de référence sur le marché parisien.</p>
<p> L'agence via sa marketplace référence aujourd'hui plus de 50 000 influenceurs dont une trentaine de "million influenceurs". En souscrivant à notre offre, vous bénéficiez de l'expertise de l'agence.</p>

<h5>Comment fonctionne Instagrowth ?</h4>
<pp>Notre solution permet d'automatiser les taches répétitives sur Instagram comme Follow/unfollow, répondre aux commentaires, "liker des publications". Grâce à ces actions, nous attirons massivement l'attention d'utilisateurs aimant votre thématique.
Votre contenu étant pertinent pour eux, ils s'abonnent volontairement et s'engagent sur vos publications.
</p>
<p>
			<h5>Pourquoi demander un numéro de carte bleue à l'inscription ?</h5>
			<p> Cela permet d'éviter les inscriptions multiples, aucun prélèvement n'est réalisé avant la fin de la période d'essai que vous pouvez stopper à tout moment.</p>
                                <h5>A quelle vitesse je vais acquérir de nouveaux followers ?</h5>
<p>La plupart des clients de la solution Instagrowth voient leur nombre de followers augmenter dès les premiers jours voir première semaine.</p>
                                <h5>Quelle est votre politique de résiliation ?</h5>
				<p>Le "free trial" est 100% sans risque, si vous décidez de vous abonnez vous pourrez résilier votre inscription à tout moment dès la fin de votre période d'abonnement.</p>
</div>
				
                        <div class="col-sm-5 col-sm-offset-1 col-xs-12 mt--xs--2">
                            <div class="pricing pricing-2 boxed boxed--border boxed--lg row no-gutters">
                                <div class="col-lg-12">
                                    <h2>Démarrez vos <?php echo $NB_JOURS_TEST;?>  jours d'essais gratuit</h2> <span class="p">Nous accompagnons plus de  50.000 Influenceurs<img class="rating-xs" src="img/4-5-star.png"></span>
                                    <hr> </div>
                                <div class="col-lg-12">
                                    <ul class="benefits">
                                        <li> <span class="checkmark bg--green"></span> <span>Instagrowth vous aide à acquérir des followers</span> </li>
                                        <li> <span class="checkmark bg--green"></span> <span>Instagrowth augmente le "reach" de vos publications</span> </li>
                                        <li> <span class="checkmark bg--green"></span> <span>Instagrowth vous met en relation avec un community manager</span> </li>
                                        
                                    </ul>
                                    <a class="btn btn--primary btn--lg" href="<?php echo  $SIGNUP_URL; ?>"> <span class="btn__text">Inscription</span> </a>
                                    <p class="small type--fineprint" style="margin-top: 10px;">100% sans risque - Testez notre solution pendant <?php echo $NB_JOURS_TEST;?>  jours</p>
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
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="cta">
                                <h1>Testez Instagrowth gratuitement pendant <?php echo $NB_JOURS_TEST;?>  jours</h1>
                                <p class="lead"> Testez notre solution gratuitement pendant <?php echo $NB_JOURS_TEST;?>  jours. Vous allez aimer travailler avec Instagrowth !</p>
                                <a class="btn btn--lg btn-fix btn--primary type--uppercase" href="<?php echo $SIGNUP_URL; ?>"> <span class="btn__text btntxt-fix">
                                    Démarrez votre essai gratuit <span class="emoji">👈</span> </span>
                                </a>
                                <p class="type--fineprint mt--1">Testez notre solution pendant <?php echo $NB_JOURS_TEST;?>  jours.</p>
                            </div>
                        </div>
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

    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/upleap.min.js"></script>

</body>

</html>
