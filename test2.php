<!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
</head>
<body>
<script>  
  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      var accessToken = response.authResponse.accessToken;
          testAPI(response);  
        } else {                                 // Not logged into your webpage or we are unable to tell.
          document.getElementById('status').innerHTML = 'Please log ' +
            'into this webpage.';
        }
  }

  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '3050783755157257',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v11.0'           // Use this Graph API version for this call.
    });

    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };
 
  function testAPI(mresponse) {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
  
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      alert(JSON.stringify(response,null, 4));
      console.log('Successful login for: ' + response.name);
        console.log(response);
          document.getElementById('status').innerHTML =
      'Thanks for logging in, ' + response.name + '!';

    });

    var info = "";
    function retrievefb(id) {
    info = id.data[0].id;

    document.getElementById('news').innerHTML = ' Test ' + 'toto ' + info ;
    }
    // Retrieve instagram account main informations ( Followers / Biography / Url / media count / profile picture )
    FB.api("/me/accounts", function(instaresponse) {
      //alert(JSON.stringify(instaresponse,null, 4));

      // Post parameters
      var xhr = new XMLHttpRequest();
      var url_post = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-main-info.php";
      var cid_value  =  2748;
      
      var accountName =  instaresponse.data[0].name;
      var pageId = instaresponse.data[0].id;
      var accessToken = instaresponse.data[0].access_token;
      document.getElementById('instagram').innerHTML =
      ' Instagram name ' + pageId + '      ' + instaresponse.data[0].access_token + '!';
      //var requestUrl = "/" + pageId + "?fields=instagram_business_account";
      var requestUrl = "/" + pageId + "?fields=connected_instagram_account";
      FB.api(requestUrl, function(businessresponse) {
        var instaId = businessresponse.connected_instagram_account.id;
        // retrieve account informations
        requestAccountInfo = "/" + instaId + "?fields=business_discovery.username(" +  accountName + "){followers_count,media_count,biography,ig_id,follows_count,website,profile_picture_url}";
        FB.api(requestAccountInfo, function(accountInfo) {
          xhr.open("POST", url_post,true);
          xhr.setRequestHeader("Accept", "application/json");
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);
          }};
  
        var data  =  JSON.stringify({"cid": cid_value, "followers_count": accountInfo.business_discovery.followers_count, "media_count": accountInfo.business_discovery.media_count, "biography": accountInfo.business_discovery.biography, "follows_count": accountInfo.business_discovery.follows_count, "website": accountInfo.business_discovery.website, "profile_picture_url": accountInfo.business_discovery.profile_picture_url});
        xhr.send(data);
			  document.getElementById('info').innerHTML = " account info " +  accountInfo.business_discovery.biography;
		  })
		// Retrieve gender age / country / city  informations
		requestGenderLocalisation = "/" + instaId + "/insights?metric=audience_gender_age,audience_country,audience_city,audience_locale&period=lifetime";
		FB.api(requestGenderLocalisation, function(insightsGender) {
      var url_post_gender = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-gender-info.php";
			var result = {}
      var first = 0;
      result['cid'] = cid_value;
      // Store gender informations
      for ( const [key, value] of Object.entries(insightsGender.data[0].values[0].value)){
        result[key] = value;
        if (first) {
        result_temp += "," +  "\'"  + key + "\'" + ":" +   value ;
        }
        else {
          first = 1;
          result_temp = "\'" + key + "\'" + ":" + value ;
          
        }
      }  
          xhr.open("POST", url_post_gender,true);
          xhr.setRequestHeader("Accept", "application/json");
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);
          }};
      
      var data  =  JSON.stringify(result);
      xhr.send(data);
      document.getElementById('gender').innerHTML = " Gender ++  " + result_temp;
      //Store  audience  per country
      var url_post_country = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-country.php";
      var result_country = {};
      result_country["cid"] = cid_value;
      var result_temp = "";
      first= 0;
      for ( const [key, value] of Object.entries(insightsGender.data[1].values[0].value)){
        result_country[key] = value;
        if (first) {
        result_temp += "," +  "\'"  + key + "\'" + ":" +   value ;
        }
        else {
          first = 1;
          result_temp = "\'" + key + "\'" + ":" + value ;
          
        }
      }
      xhr.open("POST", url_post_country,true);
          xhr.setRequestHeader("Accept", "application/json");
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);
          }};
      
      var data  =  JSON.stringify(result_country);
      xhr.send(data);  
      document.getElementById('country').innerHTML = " Country ++  "  + result_temp + " " + insightsGender.data[1].values[0].value["FR"] ;
      //Audience per town
      var url_post_town = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-town.php";
      var result_town = {};
      result_town["cid"] = cid_value;
      var result_temp = "";
      first= 0;
      for ( const [key, value] of Object.entries(insightsGender.data[2].values[0].value)){
        result_town[key] = value;
        if (first) {
        result_temp += "," +  "\'"  + key + "\'" + ":" +   value ;
        }
        else {
          first = 1;
          result_temp = "\'" + key + "\'" + ":" + value ;
          
        }
      }
      xhr.open("POST", url_post_town,true);
          xhr.setRequestHeader("Accept", "application/json");
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);
          }};
      var data  =  JSON.stringify(result_town);
      xhr.send(data);  
      document.getElementById('town').innerHTML = " Town ++  "  + result_temp  ;
		});
		//Retrieve instagram accounts metrics per day
    var url_post_metrics_day = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-metrics-day.php";
    var url_post_reach_day = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-reach-day.php";
    var url_post_email_day = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-email-day.php";
		var url_post_impressions_day = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-impressions-day.php";
    requestAccountMetrics = "/" + instaId + "/insights?metric=profile_views,reach,email_contacts,impressions&period=day";
    alert(" instaid " + instaId);
    
		FB.api(requestAccountMetrics, function(insightsMetricsDay) {
      var result_metrics_day_1 = {};
      var result_metrics_day_2 = {};
      result_metrics_day_1["cid"] = cid_value;
      result_metrics_day_2["cid"] = cid_value;
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[0].values[0])){
        result_metrics_day_1[key] = value;
      }
      xhr.open("POST", url_post_metrics_day,true);
          xhr.setRequestHeader("Accept", "application/json");
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);
          }};
      var data  =  JSON.stringify(result_metrics_day_1);
      xhr.send(data);
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[0].values[1])){
        result_metrics_day_2[key] = value;
      }
      var xhr2 = new XMLHttpRequest();
      xhr2.open("POST", url_post_metrics_day,true);
          xhr2.setRequestHeader("Accept", "application/json");
          xhr2.setRequestHeader("Content-Type", "application/json");
          xhr2.onreadystatechange = function () {
          if (xhr2.readyState === 4) {
            console.log(xhr2.status);
            console.log(xhr2.responseText);
          }};
      var data2  =  JSON.stringify(result_metrics_day_2);
      xhr2.send(data2);
      var result_reach_day_1 = {};
      var result_reach_day_2 = {};
      result_reach_day_1["cid"] = cid_value;
      result_reach_day_2["cid"] = cid_value;
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[1].values[0])){
        result_reach_day_1[key] = value;
      }
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[1].values[1])){
        result_reach_day_2[key] = value;
      }
      var xhr3 = new XMLHttpRequest();

      xhr3.open("POST", url_post_reach_day,false);
      xhr3.setRequestHeader("Accept", "application/json");
      xhr3.setRequestHeader("Content-Type", "application/json");
      xhr3.onreadystatechange = function () {
          if (xhr3.readyState === 4) {
            console.log(xhr3.status);
            console.log(xhr3.responseText);
          }
      };
      var data3  =  JSON.stringify(result_reach_day_1);
      xhr3.send(data3);
       xhr3.open("POST", url_post_reach_day,true);
          xhr3.setRequestHeader("Accept", "application/json");
          xhr3.setRequestHeader("Content-Type", "application/json");
          xhr3.onreadystatechange = function () {
          if (xhr3.readyState === 4) {
            console.log(xhr3.status);
            console.log(xhr3.responseText);
          }};
      var data4  =  JSON.stringify(result_reach_day_2);
      xhr3.send(data4);
      var result_mail_day_1 = {};
      var result_mail_day_2 = {};
      result_mail_day_1["cid"] = cid_value;
      result_mail_day_2["cid"] = cid_value;
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[2].values[0])){
        result_mail_day_1[key] = value;
      }
      var xhr4 = new XMLHttpRequest();
      xhr4.open("POST", url_post_email_day,false);
      xhr4.setRequestHeader("Accept", "application/json");
      xhr4.setRequestHeader("Content-Type", "application/json");
      xhr4.onreadystatechange = function () {
          if (xhr4.readyState === 4) {
            console.log(xhr4.status);
            console.log(xhr4.responseText);
          }
      };
      var data4  =  JSON.stringify(result_mail_day_1);
      xhr4.send(data4);
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[2].values[1])){
        result_mail_day_2[key] = value;
      }
      xhr4.open("POST", url_post_email_day,false);
      xhr4.setRequestHeader("Accept", "application/json");
      xhr4.setRequestHeader("Content-Type", "application/json");
      xhr4.onreadystatechange = function () {
          if (xhr4.readyState === 4) {
            console.log(xhr4.status);
            console.log(xhr4.responseText);
          }
      };
      var data5  =  JSON.stringify(result_mail_day_2);
      xhr4.send(data5);
      var result_impressions_day_1 = {};
      var result_impressions_day_2 = {};
      result_impressions_day_1["cid"] = cid_value;
      result_impressions_day_2["cid"] = cid_value;
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[3].values[0])){
        result_impressions_day_1[key] = value;
      }
      var xhr5 = new XMLHttpRequest();
      xhr5.open("POST", url_post_impressions_day,true);
      xhr5.setRequestHeader("Accept", "application/json");
      xhr5.setRequestHeader("Content-Type", "application/json");
      xhr5.onreadystatechange = function () {
          if (xhr5.readyState === 4) {
            console.log(xhr5.status);
            console.log(xhr5.responseText);
          }
      };
      var data6  =  JSON.stringify(result_impressions_day_1);
      xhr5.send(data6);
      for ( const [key, value] of Object.entries(insightsMetricsDay.data[3].values[1])){
        result_impressions_day_2[key] = value;
      }
      xhr5.open("POST", url_post_impressions_day,true);
      xhr5.setRequestHeader("Accept", "application/json");
      xhr5.setRequestHeader("Content-Type", "application/json");
      xhr5.onreadystatechange = function () {
          if (xhr5.readyState === 4) {
            console.log(xhr5.status);
            console.log(xhr5.responseText);
          }
      };
      var data7  =  JSON.stringify(result_impressions_day_2);
      xhr5.send(data7);
			document.getElementById('reach').innerHTML = " Reach ++ " + insightsMetricsDay.data[3].values[0].value +  "  ### " + insightsMetricsDay.data[1].values[0].value + " ###" +  requestAccountMetrics;
      //requestAccountMetrics;
    });
		//Retrieve instagram insights 28 days
		requestUrl  =  "/" + instaId  + "/insights?metric=impressions,reach&period=days_28";
		FB.api(requestUrl, function(insightsresponse) {
      var url_post_impressions_days_28 = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-impressions-day_28.php";
      var url_post_reach_days_28 = "https://instagrowth.lesinfluenceurs.net/webservices/instagram-insert-reach-day_28.php";
      var result_impressions_days28_1 = {};
      var result_impressions_days28_2 = {};
      result_impressions_days28_1["cid"] = cid_value;
      result_impressions_days28_2["cid"] = cid_value;
      for ( const [key, value] of Object.entries(insightsresponse.data[0].values[0])){
        result_impressions_days28_1[key] = value;
      }
      var xhr7 = new XMLHttpRequest();
      xhr7.open("POST", url_post_impressions_days_28,true);
      xhr7.setRequestHeader("Accept", "application/json");
      xhr7.setRequestHeader("Content-Type", "application/json");
      xhr7.onreadystatechange = function () {
          if (xhr7.readyState === 4) {
            console.log(xhr7.status);
            console.log(xhr7.responseText);
          }
      };
      var data1  =  JSON.stringify(result_impressions_days28_1);
      xhr7.send(data1);
      for ( const [key, value] of Object.entries(insightsresponse.data[0].values[1])){
        result_impressions_days28_2[key] = value;
      }
      xhr7.open("POST", url_post_impressions_days_28,true);
      xhr7.setRequestHeader("Accept", "application/json");
      xhr7.setRequestHeader("Content-Type", "application/json");
      xhr7.onreadystatechange = function () {
          if (xhr7.readyState === 4) {
            console.log(xhr7.status);
            console.log(xhr7.responseText);
          }
      };
      var data2  =  JSON.stringify(result_impressions_days28_2);
      xhr7.send(data2);

      var result_reach_days28_1 = {};
      var result_reach_days28_2 = {};
      result_reach_days28_1["cid"] = cid_value;
      result_reach_days28_2["cid"] = cid_value;
      for ( const [key, value] of Object.entries(insightsresponse.data[1].values[0])){
        result_reach_days28_1[key] = value;
      }
      var xhr8 = new XMLHttpRequest();
      xhr8.open("POST", url_post_reach_days_28,true);
      xhr8.setRequestHeader("Accept", "application/json");
      xhr8.setRequestHeader("Content-Type", "application/json");
      xhr8.onreadystatechange = function () {
          if (xhr8.readyState === 4) {
            console.log(xhr8.status);
            console.log(xhr8.responseText);
          }
      };
      var data2  =  JSON.stringify(result_reach_days28_1);
      xhr8.send(data2);
      for ( const [key, value] of Object.entries(insightsresponse.data[1].values[1])){
        result_reach_days28_2[key] = value;
      }
       xhr8.open("POST", url_post_reach_days_28,true);
       xhr8.setRequestHeader("Accept", "application/json");
       xhr8.setRequestHeader("Content-Type", "application/json");
       xhr8.onreadystatechange = function () {
          if (xhr8.readyState === 4) {
             console.log(xhr8.status);
             console.log(xhr8.responseText);
           }
       };
       var data3  =  JSON.stringify(result_reach_days28_2);
      xhr8.send(data3);
			document.getElementById('business').innerHTML =
			'Business ' + businessresponse.connected_instagram_account.id + ' ### ' + insightsresponse.data[0].values[1].value;
  
		});
	});
  
	retrievefb(instaresponse);
  });

  
  alert(" fin " + pageId);
  
  }

</script>

<!-- The JS SDK Login Button -->

<fb:login-button scope="instagram_basic,instagram_content_publish,instagram_manage_insights,pages_show_list" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>


<div id="reach">
</div>


<div id="gender2">
</div>


<div id="country">
</div>

<div id="town">
</div>

<div id="gender">
</div>


<div id="metrics">
</div>


<div id="instagram">
</div>


<div id="info">
</div>


<div id="news">
</div>


<div id="business">
</div>



<!-- Load the JS SDK asynchronously -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

</body>
</html>
