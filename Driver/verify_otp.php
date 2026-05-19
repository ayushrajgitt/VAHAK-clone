<?php

session_start();

include '../Includes/db.php';

$shipment_id = $_POST['shipment_id'];

$entered_otp = $_POST['entered_otp'];

// fetch actual OTP

$query = "

SELECT delivery_otp

FROM shipments

WHERE id = '$shipment_id'

";

$result = mysqli_query($conn, $query);

$shipment = mysqli_fetch_assoc($result);

$real_otp = $shipment['delivery_otp'];

// verify OTP

if($entered_otp == $real_otp) {

    $updateQuery = "

    UPDATE shipments

    SET shipment_status = 'completed'

    WHERE id = '$shipment_id'

    ";

    mysqli_query($conn, $updateQuery);

    echo "

    <script>

    alert('Delivery Completed Successfully');

    window.location.href='dashboard.php';

    </script>

    ";

} else {

    echo "

    <script>

    alert('Invalid OTP');

    window.location.href='dashboard.php';

    </script>

    ";

}
?>