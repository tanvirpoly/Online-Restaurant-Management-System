<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
	header('location:index.php');
}
else{
date_default_timezone_set('Asia/Dhaka');// change according timezone
$currentTime = date( 'd-m-Y h:i:s A', time () );

if(isset($_POST['submit']))
{
	$sql=mysqli_query($con,"SELECT password FROM  admin where password='".md5($_POST['password'])."' && username='".$_SESSION['alogin']."'");
	$num=mysqli_fetch_array($sql);
	if($num>0)
	{
		$con=mysqli_query($con,"update admin set password='".md5($_POST['newpassword'])."', updationDate='$currentTime' where username='".$_SESSION['alogin']."'");
		$_SESSION['msg']="Password Changed Successfully !!";
	}
	else
	{
		$_SESSION['msg']="Old Password not match !!";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Monthly Overview</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<script type="text/javascript">
		function valid()
		{
			if(document.chngpwd.password.value=="")
			{
				alert("Current Password Filed is Empty !!");
				document.chngpwd.password.focus();
				return false;
			}
			else if(document.chngpwd.newpassword.value=="")
			{
				alert("New Password Filed is Empty !!");
				document.chngpwd.newpassword.focus();
				return false;
			}
			else if(document.chngpwd.confirmpassword.value=="")
			{
				alert("Confirm Password Filed is Empty !!");
				document.chngpwd.confirmpassword.focus();
				return false;
			}
			else if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
			{
				alert("Password and Confirm Password Field do not match  !!");
				document.chngpwd.confirmpassword.focus();
				return false;
			}
			return true;
		}
	</script>
</head>
<body>
	<?php include('include/header.php');?>

	<div class="wrapper">
		<div class="container">
			<div class="row">
				<?php include('include/sidebar.php');?>	
				<div class="span9">
					<div class="content">			
						<?php

						$query_re=mysqli_query($con, "SELECT MONTHNAME(orders.orderDate) as Month , SUM(products.productPrice) as total ,SUM(orders.quantity) as item
							FROM orders
							INNER join products
							on orders.productId=products.id
							GROUP BY MONTH(orders.orderDate)");
							?>
							<h3 style="text-align: center; background-color: gray; padding: 2px; color: white;">Monthly Overview</h3>
							<table class="table">
								<thead>
									<tr>
										<th>Month</th>
										<th>Amount</th>
										<th>No of Products</th>
									</tr>
								</thead>
								
								<?php
								while ($res=mysqli_fetch_array($query_re)) 
								{
									?>
									<tr>
										<td><?php echo $res['Month']; ?></td>
										<td><?php echo $res['total']; ?></td>
										<td><?php echo $res['item']; ?></td>
									</tr>
									<?php
								}
								?>
							</table>
						</div>
					</div>
				</div>
			</div><!--/.container -->
		</div><!--/.wrapper-->

		<?php include('include/footer.php');?>

		<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
		<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
	</body>
	<?php } ?>