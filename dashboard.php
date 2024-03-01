<?php require_once 'includes/header.php'; ?>

<?php

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
	$totalRevenue += $orderResult['paid'];
}
$countLowStock = 0;
$lowStockSql = "SELECT * FROM product WHERE quantity <= 10 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$userwisesql = "SELECT users.username , SUM(orders.grand_total) as totalorder FROM orders INNER JOIN users ON orders.user_id = users.user_id WHERE orders.order_status = 1 GROUP BY orders.user_id";
$userwiseQuery = $connect->query($userwisesql);
$userwieseOrder = $userwiseQuery->num_rows;

$connect->close();

?>


<style type="text/css">
	.ui-datepicker-calendar {
		display: none;
	}
</style>

<!-- fullCalendar 2.2.5-->
<link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.print.css" media="print">


<div class="row">
	<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
		<div class="col-md-4">

		</div>

		<div class="col-md-4">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a href="product.php" style="text-decoration:none;color:black;">
						Low Stock
						<span class="badge pull pull-right"><?php echo $countLowStock; ?></span>
					</a>

				</div> <!--/panel-hdeaing-->
			</div> <!--/panel-->
		</div> <!--/col-md-4-->


	<?php } ?>
	<div class="col-md-4">
		<div class="panel panel-info">
			<div class="panel-heading">
				<a href="orders.php?o=manord" style="text-decoration:none;color:black;">
					Total Orders
					<span class="badge pull pull-right"><?php echo $countOrder; ?></span>
				</a>

			</div> <!--/panel-hdeaing-->
		</div> <!--/panel-->
	</div> <!--/col-md-4-->



	<div class="col-md-4">
		<div class="card">
			<div class="cardHeader">
				<h1><?php echo date('d'); ?></h1>
			</div>

			<div class="cardContainer">
				<p><?php echo date('l') . ' ' . date('d') . ', ' . date('Y'); ?></p>
			</div>
		</div>
		<br />

		<div class="card">
			<div class="cardHeader" style="background-color:#245580;">
				<h1><?php if ($totalRevenue) {
						echo 'Rs. ' . $totalRevenue;
					} else {
						echo 'Rs. 0';
					} ?></h1>
			</div>

			<div class="cardContainer">
				<p> Total Revenue</p>
			</div>
		</div>
		<br>

		<div class="card">
			<div class="cardHeader" style="background-color:#245580;">
				<h1><?php echo $countProduct; ?>
			</div>

			<div class="cardContainer">
				<p> Total Products</p>
			</div>
		</div>

	</div>

	<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading"> <i class="glyphicon glyphicon-calendar"></i> User Wise Order</div>
				<div class="panel-body">
					<table class="table table-hover table-striped table-bordered" id="productTable">
						<thead>
							<tr>
								<th style="width:40%;">Name</th>
								<th style="width:20%;">Orders (Rs.)</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($orderResult = $userwiseQuery->fetch_assoc()) { ?>
								<tr>
									<td><?php echo $orderResult['username'] ?></td>
									<td><?php echo $orderResult['totalorder'] ?></td>

								</tr>

							<?php } ?>
						</tbody>
					</table>
					<!--<div id="calendar"></div>-->
				</div>

				<div class="col-md-2">

				</div>
				<div class="col-md-5">
					<h3>Number of Orders</h3>
					<canvas id="salesPieChart" width="400" height="400"></canvas>
					<script type="text/javascript">
						// Function to create pie chart
						function createPieChart(chartData, canvasId) {
							var ctx = document.getElementById(canvasId).getContext('2d');
							var chart = new Chart(ctx, {
								type: 'pie',
								data: {
									labels: chartData.labels,
									datasets: [{
										data: chartData.data,
										backgroundColor: chartData.backgroundColor
									}]
								}
							});
						}

						// Call the function to create the pie chart
						createPieChart(<?php echo $chartDataJson; ?>, 'salesPieChart');
					</script>
				</div>


			</div>

		</div>
	<?php  } ?>

	<?php
	// Sample user-wise sales data
	$userwiseData = [
		['Name' => 'admin', 'Orders' => 89301.66],
		['Name' => 'staff', 'Orders' => 13022],
		['Name' => 'staff2', 'Orders' => 16725.96],
	];

	// Prepare data for the pie chart
	$labels = [];
	$data = [];
	$backgroundColor = [];

	foreach ($userwiseData as $userData) {
		$labels[] = $userData['Name'];
		$data[] = $userData['Orders'];
		// You can customize colors as needed
		$backgroundColor[] = 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 0.7)';
	}

	$chartData = [
		'labels' => $labels,
		'data' => $data,
		'backgroundColor' => $backgroundColor,
	];

	// Convert PHP data to JSON for JavaScript consumption
	$chartDataJson = json_encode($chartData);
	?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<title>Pie Chart Example</title>
	</head>

	<body>
		<div class="col-md-5">
			<canvas id="salesPieChart" width="400" height="400"></canvas>

			<script type="text/javascript">
				// Function to create pie chart
				function createPieChart(chartData, canvasId) {
					var ctx = document.getElementById(canvasId).getContext('2d');
					var chart = new Chart(ctx, {
						type: 'pie',
						data: {
							labels: chartData.labels,
							datasets: [{
								data: chartData.data,
								backgroundColor: chartData.backgroundColor
							}]
						}
					});
				}

				// Call the function to create the pie chart
				createPieChart(<?php echo $chartDataJson; ?>, 'salesPieChart');
			</script>
		</div>
	</body>

	</html>



</div> <!--/row-->

<!-- fullCalendar 2.2.5 -->
<script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>


<script type="text/javascript">
	$(function() {
		// top bar active
		$('#navDashboard').addClass('active');

		//Date for the calendar events (dummy data)
		var date = new Date();
		var d = date.getDate(),
			m = date.getMonth(),
			y = date.getFullYear();

		$('#calendar').fullCalendar({
			header: {
				left: '',
				center: 'title'
			},
			buttonText: {
				today: 'today',
				month: 'month'
			}
		});


	});
</script>







</div>












<?php require_once 'includes/footer.php'; ?>