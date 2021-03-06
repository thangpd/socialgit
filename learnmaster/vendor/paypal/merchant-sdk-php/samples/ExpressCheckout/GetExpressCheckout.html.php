<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>PayPal Merchant SDK - GetExpressCheckout</title>
<link rel="stylesheet" type="text/css" href="../Common/sdk.css" />
</head>
<body>
	<div id="wrapper">
		<img src="https://devtools-paypal.com/image/bdg_payments_by_pp_2line.png">
		<div id="header">
			<h3>GetExpressCheckout</h3>
			<div id="apidetails">Used to get checkout details by checkout token</div>
		</div>
		<form method="POST" action="GetExpressCheckout.php">
			<div id="request_form">
				<div class="params">
					<div class="param_name">
						Token(Get Token via <a href="SetExpressCheckout.html.php">SetExpressCheckout</a>)
					</div>
					<div class="param_value">
						<input type="text" name="token" value="" size="50" maxlength="260" />
					</div>
				</div>

				<div class="submit">
					<input type="submit" name="GetExpressCheckoutBtn"
						value="GetExpressCheckout" /> <br />
				</div>
				<a href="../index.php">Home</a>
			</div>
		</form>


	</div>
</body>
</html>
