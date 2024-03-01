<?php


require_once 'db_connect.php';
require_once 'core.php';


$sql = "SELECT order.order_id, order.order_date, order.client_name, order.client_contact
        FROM `order`
        INNER JOIN order_item ON order.order_id = order_item.order_id
        INNER JOIN product ON order_item.product_id = product.product_id
        WHERE product.product_name = 'Ultraboost 21 Primeblue Shoes'";

$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

    // $row = $result->fetch_array();
    $active = "";

    while ($row = $result->fetch_array()) {
        $orderId = $row[0];
        // active 

        $orderdate = $row[1];
        $clientname = $row[2];
        $clientContact = $row[3];


        $output['data'][] = array(
            $row[0],
            // product name
            $row[1],
            // rate
            $row[2],
            // quantity 
            $row[3],
            // brand

        );
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output);
