<?php
// Load kMeans class
require_once 'inc/KMeans.php';

// Your existing code...

// Fetching client preferences data
$clientPreferences = [];
foreach ($productNames as $productName) {
    $sql = "SELECT DISTINCT orders.client_name
            FROM `orders`
            INNER JOIN order_item ON orders.order_id = order_item.order_id
            INNER JOIN product ON order_item.product_id = product.product_id
            WHERE product.product_name = ?";
    $stmt = $connect->prepare($sql);

    if (!$stmt) {
        die("Error preparing the statement: " . $connect->error);
    }

    $success = $stmt->bind_param("s", $productName);

    if (!$success) {
        die("Error binding parameters: " . $stmt->error);
    }

    $executeSuccess = $stmt->execute();

    if (!$executeSuccess) {
        die("Error executing the query: " . $stmt->error);
    }

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $clientPreferences[] = [
            'client_name' => $row['client_name'],
            'product_name' => $productName,
        ];
    }

    $stmt->reset();
}

$connect->close();

// Perform k-means clustering
// $kMeans = new kmeans(14); // 
$clusters = $kMeans->cluster($clientPreferences);

// Display the clusters
echo "<pre>";
print_r($clusters);
echo "</pre>";

// Your existing code...
