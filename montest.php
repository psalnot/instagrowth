<script src="https://js.stripe.com/v3/"></script>


<!-- CSS for each example: -->
<link rel="stylesheet" type="text/css" href="css/test.css" data-rel-css="" />


<form action="/bxoffice-traitement/charge.php" method="post" id="payment-form">
  <div class="form-row">
      <label for="card-element">
            Credit or debit card
	        </label>
		    <div id="card-element">
		          <!-- A Stripe Element will be inserted here. -->
			      </div>

    <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
	  </div>

  <button>Submit Payment</button>
  </form>

<!-- Scripts for each example: -->
  <script src="./moncharge.js" data-rel-js></script>
    