<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">


        <!-- productname = Ultraboost -->
        <div class="panel panel-default">
            <div class="panel-heading">

                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Wyze Cam v3 1080p HD Indoor/Outdoor Video Camera</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">

                </div> <!-- /div-action -->

                <table class="table table-hover table-striped table-bordered" id="manageProductTable">
                    <thead>
                        <tr>


                            <th>Client Name</th>
                            <th>Client Contact</th>

                        </tr>
                    </thead>
                </table>


                <!-- /table -->


                <?php
                require_once 'php_action/db_connect.php';
                require_once 'includes/header.php';


                // Check if the connection is successful
                if ($connect->connect_error) {
                    die("Connection failed: " . $connect->connect_error);
                }
                $productNames = ['Wyze Cam v3 1080p HD Indoor/Outdoor Video Camera'];

                foreach ($productNames as $productName) {
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
                        echo "<table>";
                        // Add table headers

                        while ($row = $result->fetch_assoc()) {
                            // Display each client name and client contact in separate columns
                            echo "<tr>";
                            echo "<td>" . $row['client_name'] . "</td>";
                            echo "<td>" . $row['client_contact'] . "</td>";
                            echo "</tr>";
                        }

                        // Close the table
                        echo "</table>";
                    } else {
                        echo "No clients found for product '$productName'.";
                    }

                    // Close the statement
                    $stmt->close();
                }

                // Close the connection
                $connect->close();
                ?>