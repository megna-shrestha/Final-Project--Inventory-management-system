<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>
            <li class="active">Customer Preferences</li>
        </ol>
        <!-- productname = Ultraboost -->
        <div class="panel panel-default">
            <div class="panel-heading">

                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Products Preferences :</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">

                </div> <!-- /div-action -->

                <table class="table table-hover table-striped table-bordered " id="managePreferTable">
                    <thead>
                        <tr>


                            <th>Client Name</th>
                            <th>Client Contact</th>
                            <th>Product name</th>

                        </tr>
                    </thead>
                </table>

                <!-- /table -->


                <?php
                require_once 'php_action/db_connect.php';
                require_once 'includes/header.php';

                $productNames = [
                    'Ultraboost 21 Primeblue Shoes', 'Wyze Cam v3 1080p HD Indoor/Outdoor Video Camera', 'Garment-Dyed Canvas Denim Jacket',
                    'VansXPark Project Classic Slip-On', 'TechFit Fitted Tee', 'Love Unites Tank Top (Gender Neutral)', 'R.T.V. Hoodie', 'Adicolor Classics 3-Stripes Shorts',
                    'TP-Link AC1750 Smart WiFi Router (Archer A7)', 'Wyze Cam 1080p HD Indoor WiFi Smart Home Camera with Night Vision', 'Fire TV Stick 4K streaming device with Alexa Voice Remote', 'Apple MagSafe Charger', 'Sample Product', 'Nokia 2.3 smartphone'
                ]; // Define the product name

                // Use prepared statement to avoid SQL injection
                $sql = "SELECT orders.order_id, orders.order_date, orders.client_name, orders.client_contact
        FROM `orders`
        INNER JOIN order_item ON orders.order_id = order_item.order_id
        INNER JOIN product ON order_item.product_id = product.product_id
        WHERE product.product_name = ?";

                // Prepare the statement
                $stmt = $connect->prepare($sql);

                // Check if the statement was prepared successfully
                if (!$stmt) {
                    die("Error preparing the statement: " . $connect->error);
                }
                foreach ($productNames as $productName) {
                    // Bind the product name parameter
                    $success = $stmt->bind_param("s", $productName);

                    // Check if the bind was successful
                    if (!$success) {
                        die("Error binding parameters: " . $stmt->error);
                    }

                    // Execute the query
                    $executeSuccess = $stmt->execute();

                    // Check if the execution was successful
                    if (!$executeSuccess) {
                        die("Error executing the query: " . $stmt->error);
                    }

                    // Get the result set
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo '<table class="table table-hover table-striped table-bordered" id="managePreferTable">';



                        // Add table headers

                        while ($row = $result->fetch_assoc()) {
                            // Display each client name and client contact in separate columns
                            echo '<table ><tr> ';
                            echo "<td ><ul>" . $row['client_name'] . "</td>";
                            echo "<td ><ul><ul><ul><ul><ul><ul><ul><ul> " . $row['client_contact'] . "</td>";
                            echo "<td ><ul><ul><ul><ul><ul><ul><ul><ul> '$productName'.</td>";


                            echo "</tr>";
                            echo "  </table>";
                        }

                        // Close the table
                        echo "</table>";
                    } else {
                        echo "<tr><td colspan='3' style='text-align: left;'><strong>No clients found for product '$productName'.</strong></td></tr>";
                    }


                    $stmt->reset();
                }
                // Close the statement and connection
                $stmt->close();
                $connect->close();
                ?>


            </div>
        </div>
    </div>

</div>