
<!doctype html>
<html lang="fr">

<?php



include_once("/class/mailgun.class.php");


include_once("class/cleaninput.class.php");
include_once("class/user.class.php");
include_once("class/mysql.class.php");




   include_once("utils/defines.php");
   include_once("class/buildmenu.class.php");
   
include_once("config.php"); 
$mlocation = "location: /" . $SIGNUP_URL;
$mthirdpart = "location: /" . $SIGNUP_THIRD;

$buildmenu = new Mbuildmenu();   
#error_reporting(E_ALL);

// Display errors on the page
#ini_set('display_errors', 1);


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

$user_info['11'] = "cottagedemontchamp";

$urlmail = $muser->geturlmailid($cstring,$cid);
#$htags = $muser->updateInstagrowthHashtags($cstring,$cid, $chashtag1,$chashtag2,$chashtag3,$chashtag4,$chashtag5,$cremoteip,$cremotehost,$cremoteuseragent);
$hcommentaires = $muser->getInstagrowthCommentaires($cstring,$cid);

$mstats = $muser->getInstaGrowthGenStats($cstring, $user_info[11]);

// Call the function
$interactionsData = $muser->getInstaTopInteractions($cstring, $user_info[11]);

$interactionsHashtag = $muser->getInstaTopHashtagsInteractions($cstring, $user_info[11]);

$interactionsFlopData = $muser->getInstaFlopInteractions($cstring, $user_info[11]);


$targets = $muser->getTargets($cstring, $user_info[11]);

$getfirstandlastsession = $muser->getFirstAndLastInstagramSession($cstring, $user_info[11]);

$evolution = $getfirstandlastsession['last']['profile_followers'] - $getfirstandlastsession['first']['profile_followers'];


// Format the dates in PHP (if not null)
if ($getfirstandlastsession['last']['start_time']) {
	$temp_last_time = DateTime::createFromFormat('Y-m-d H:i:s', $getfirstandlastsession['last']['start_time']);
	$getfirstandlastsession['last']['start_time'] = $temp_last_time ? $temp_last_time->format('d/m/Y') : null;
}


#$friendlist = $muser->getInstaGrowthFriends($cstring,$cid);
  
   
?>


  
<head>
    <meta charset="utf-8">
    <title>Statitistiques de  @<?php echo $user_info[11];?> </title>
    <?php include_once("utils/instagrowth-les-influenceurs-header-meta.php"); ?>
	<style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
    
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
			<center><h3 class="text--logo light color--black">Synthèse des stats @<?php echo $user_info[11];?> </h3></center> 
			<b>
				<center> <h3> Statistiques consolidées depuis le <?php echo $mstats['first_start_time'] ?> </h3></center></b>
			  <div class="container">
								<?php 
									
									if ($evolution > 10) {
											echo "<center> Nombre de followers gagnés sur la période: " . $evolution . "</center>";
									}
								?>		
								
			    <div class="row">

			      <!-- main start -->
			      <!-- ================ -->
			      <!--<div class="main col-md-12">-->
				<div class="main2">
				<table>
					<tr>
						
						<th>Total Unfollowed</th>
						<!--<th>Total Interactions</th>-->
						<th>Total Followed</th>
						<th>Total Likes</th>
						<th>Total Watched</th>
					</tr>
					<tr>
						
						<td> <?php echo $mstats['total_unfollowed']  ?></td>
						<!--<td> <?php echo $mstats['total_interactions']  ?> </td>-->
						<td> <?php echo $mstats['total_followed']  ?></td>
						<td> <?php echo $mstats['total_likes']  ?></td>
						<td> <?php echo $mstats['total_watched']  ?></td>
					</tr>
    			</table>
				<br>
				<center><h3> Synthèse de la dernière session</h3></center>

				<br>
				<table>
					<tr>
						
						<th>Date de la dernière session</th>
						<!--<th>Total Interactions</th>-->
						<th>Comptes suivis</th>
						<th>Comptes likes</th>
						<th>Comptes unfollowed</th>
					</tr>
					<tr>
						
						<td> <?php echo $getfirstandlastsession['last']['start_time']  ?></td>
						<td> <?php echo $getfirstandlastsession['last']['total_followed']  ?></td>
						<td> <?php echo $getfirstandlastsession['last']['total_likes']  ?></td>
						<td> <?php echo  $getfirstandlastsession['last']['total_unfollowed'] ?></td>
					</tr>
    			</table>
				<br>
				<b>
				<center><h3> Analyse des follow back par compte instagram et hashtags </h3></center></b>
				</br>
				
				<table>
				<?php if (!is_null($targets) && !empty($targets)) {
					// Display the table header only if $targets is not null and not empty
									echo '<tr>
									<th>Compte ou hashtag instagram</th>
									<th>% followback </th>
									</tr>';
					}
					else {
						echo "<center><b> Analyse follow back non encore réalisée </b></center>";
					}
					?>

					<?php
		$followBackData = []; // Array to hold target and percentage data

		foreach ($targets as $trow) {
			$fcount = $muser->getFollowBackCount($cstring, $trow['target'], $user_info[11]);
			$total  = $muser->getTotalTestsCount($cstring, $trow['target'], $user_info[11]);
			$percentage = $muser->calculateFollowBackPercentages($fcount, $total);
			
			// Store the target and percentage in the array
			$followBackData[$trow['target']] = $percentage;
		}

		// Sort the array by percentage in descending order
		arsort($followBackData);

		// Display the sorted data
		foreach ($followBackData as $target => $percentage) {
    ?>
    <tr>
        <td>
            <?php if (strpos($target, '#') !== 0): ?>
                <a href="https://www.instagram.com/<?php echo htmlspecialchars($target); ?>" target="_blank">
                    <?php echo htmlspecialchars($target); ?>
                </a>
            <?php else: ?>
                <?php echo htmlspecialchars($target); // Just print the name if it starts with '#' ?>
            <?php endif; ?>
        </td>
        <td><?php echo $percentage; ?>%</td>
    </tr>
    <?php
}
?>

				</table>

				<center><h3>Top 5 des comptes cibles générant le plus d'interactions</h3></center>
				<table>
					<tr>
						<th>Compte instagram</th>
						<th>Nombre de comptes suivis</th>
						<th>Nombre de vidéos consultées</th>
					</tr>
					
					<?php foreach ($interactionsData as $row): ?>
						<tr>
							<td><a href="https://www.instagram.com/<?php echo htmlspecialchars($row['target']); ?>" target="_blank"><?php echo htmlspecialchars($row['target']); ?></a></td>
							<td><?php echo htmlspecialchars($row['followed_total']); ?>
							</td>
							<td><?php echo htmlspecialchars($row['watched']); ?></td>
							
						</tr>
            		<?php endforeach; ?>
				</table>
				
				
				

				<br>
				<center><h3>Top 5 des hashtags cibles générant le plus d'interactions</h3></center>
				<table>
					<tr>
						<th>Hashtag</th>
						<th>Nombre de comptes suivis</th>
						<th>Nombre de vidéos consultées</th>
					</tr>
					<?php foreach ($interactionsHashtag as $row): ?>
						<tr>
							
							<td><?php echo htmlspecialchars($row['target']); ?></td>
							<td><?php echo htmlspecialchars($row['followed_total']); ?></td>
							<td><?php echo htmlspecialchars($row['watched']); ?></td>
							
						</tr>
            		<?php endforeach; ?>
				</table>
				<br>
				<center><h3> Top 5 des comptes générant le moins d'interaction </h3></center>
				
				<table>
					<tr>
						<th>Compte instagram</th>
						<th>Nombre de comptes suivis</th>
						<th>Nombre de vidéos consultées</th>
					</tr>
					<?php foreach ($interactionsFlopData as $row): ?>
						<tr>
							<td><a href="https://www.instagram.com/<?php echo htmlspecialchars($row['target']); ?>" target="_blank"><?php echo htmlspecialchars($row['target']); ?></a></td>
							<td><?php echo htmlspecialchars($row['followed_total']); ?>  </td>
							<td><?php echo htmlspecialchars($row['watched']); ?></td>
							
						</tr>
            		<?php endforeach; ?>
				</table>
			      <!--<div class="text-right">
				<a href="shop-cart.html" class="btn btn-group btn-default"><i class="icon-left-open-big"></i> Go Back To Cart</a>
				<a href="shop-checkout-payment.html" class="btn btn-group btn-default">Next Step <i class="icon-right-open-big"></i></a>
			      </div>-->
		              </div>
			      
				
			
			    </div>
			  </div>
			</section>
                        
                        
			</div>
			<?php echo $buildmenu->buildmenuinstagrowth($BACKOFFICE_EDIT_INSTAGRAM,$BACKOFFICE_LOGOUT,$BACKOFFICE_EDIT_ABO,$BACKOFFICE_EDIT_COMPTE,$MAIL_SUPPORT,$MAIL_SUBJECT,$BACKOFFICE_FRONT); ?>
			
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
	<?php
		mysqli_close($cstring); // Close the MySQL connection
	?>
</body>

</html>
