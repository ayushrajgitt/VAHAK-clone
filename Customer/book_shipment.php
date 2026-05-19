<?php

session_start();

// check if user logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();

}

// check correct role
if ($_SESSION['role'] != 'customer') {

    header("Location: ../login.php");
    exit();

}

?>

<?php

include '../Includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $customer_id = $_SESSION['user_id'];

    $source = $_POST['source'];

    $destination = $_POST['destination'];

    $goods_type = $_POST['goods_type'];

    $weight = $_POST['weight'];

    $number_of_trucks = $_POST['number_of_trucks'];

    $truck_id = $_POST['truck_id'];

    $estimated_cost = $_POST['estimated_cost'];

    $insertQuery = "INSERT INTO shipments (

        customer_id,
        truck_id,
        source,
        destination,
        goods_type,
        weight_kg,
        number_of_trucks,
        estimated_cost

    )

    VALUES (

        '$customer_id',
        '$truck_id',
        '$source',
        '$destination',
        '$goods_type',
        '$weight',
        '$number_of_trucks',
        '$estimated_cost'

    )";

    $result = mysqli_query($conn, $insertQuery);

    if ($result) {

        echo "<script>
                alert('Shipment Booked Successfully');
              </script>";

    } else {

        echo "<script>
                alert('Booking Failed');
              </script>";

    }

}

// fetch trucks dynamically
$truckQuery = "SELECT * FROM trucks";
$truckResult = mysqli_query($conn, $truckQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Shipment</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:#f4f6f9;
        }

        .container{
            width:90%;
            max-width:900px;
            margin:40px auto;
            background:white;
            padding:35px;
            border-radius:15px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
        }

        h1{
            margin-bottom:25px;
            color:#1a1a2e;
        }

        .form-group{
            margin-bottom:20px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        }

        input,
        select{
            width:100%;
            padding:14px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
        }

        .truck-card{
            margin-top:25px;
            border:1px solid #ddd;
            padding:20px;
            border-radius:12px;
            text-align:center;
            display:none;
        }

        .truck-card img{
            width:300px;
            max-width:100%;
            border-radius:10px;
            margin-bottom:15px;
        }

        .truck-card h2{
            color:#1a1a2e;
            margin-bottom:10px;
        }

        .truck-card p{
            margin-bottom:8px;
            color:#555;
        }

        button{
            width:100%;
            padding:15px;
            border:none;
            background:#ff6b35;
            color:white;
            font-size:16px;
            font-weight:bold;
            border-radius:10px;
            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#e85a2a;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="dashboard.php" 
    style="
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#ff6b35;
    font-weight:bold;
    ">
    ← Back to Dashboard
    </a>

    <h1>Book Shipment 🚚</h1>
    <form method="POST">

        <div class="form-group">
            <label>Source</label>
            <input type="text" name="source" placeholder="Enter pickup city" required>
        </div>

        <div class="form-group">
            <label>Destination</label>
            <input type="text" name="destination" placeholder="Enter destination city" required>
        </div>

        <div class="form-group">
            <label>Goods Type</label>
            <input type="text" name="goods_type" placeholder="Example: Furniture, Electronics" required>
        </div>

        <div class="form-group">
            <label>Estimated Weight (KG)</label>
            <input type="number" name="weight" id="weightInput" placeholder="Enter estimated load weight" required>
        </div>

        <div class="form-group">
            <label>Number of Trucks Required</label>
            <input
                type="number"
                name="number_of_trucks"
                id="numberOfTrucks"
                value="1"
                min="1"
                required
            >
        </div>

        <div class="form-group">
            <label>Select Truck</label>

            <select id="truckSelect" name="truck_id" required>

                <option value="">Choose Truck</option>

                <?php while($truck = mysqli_fetch_assoc($truckResult)) { ?>

                    <option
                        value="<?php echo $truck['id']; ?>"
                        data-name="<?php echo $truck['truck_name']; ?>"
                        data-capacity="<?php echo $truck['capacity_kg']; ?>"
                        data-price="<?php echo $truck['base_price']; ?>"
                        data-image="<?php echo $truck['truck_image']; ?>"
                    >

                        <?php echo $truck['truck_name']; ?>

                    </option>

                <?php } ?>

            </select>
            <input type="hidden" name="estimated_cost" id="estimatedCostInput">

        </div>

        <div class="truck-card" id="truckCard">

            <img id="truckImage" src="">

            <h2 id="truckName"></h2>

            <p>
                Capacity:
                <span id="truckCapacity"></span> KG
            </p>

            <p>
                Estimated Base Cost:
                ₹<span id="truckPrice"></span>
            </p>

        </div>

        <button type="submit">
            Book Shipment
        </button>

    </form>

</div>

<script>

const truckSelect = document.getElementById('truckSelect');

const truckCard = document.getElementById('truckCard');

const numberOfTrucksInput = document.querySelector(
'input[name="number_of_trucks"]'
);

function updateTruckDetails() {

    const selected = truckSelect.options[truckSelect.selectedIndex];

    const truckName = selected.dataset.name;

    const truckCapacity = selected.dataset.capacity;

    const truckPrice = selected.dataset.price;

    const truckImage = selected.dataset.image;

    const numberOfTrucks = numberOfTrucksInput.value;

    if (!truckName) {

        truckCard.style.display = 'none';

        return;
    }

    // calculate total cost
    const totalCost = parseFloat(truckPrice) * parseInt(numberOfTrucks);

    // show details
    document.getElementById('truckName').innerText = truckName;

    document.getElementById('truckCapacity').innerText =
    truckCapacity;

    document.getElementById('truckPrice').innerText =
    totalCost;

    document.getElementById('truckImage').src =
    '../assets/images/trucks/' + truckImage;

    // store hidden cost
    document.getElementById('estimatedCostInput').value =
    totalCost;

    truckCard.style.display = 'block';
}

// when truck changes
truckSelect.addEventListener(
'change',
updateTruckDetails
);

// when truck quantity changes
numberOfTrucksInput.addEventListener(
'input',
updateTruckDetails
);

</script>

</body>
</html>