
<?php 
include('connect/connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
$query = "SELECT * FROM test";

$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result)){

    echo $row['name'];

}

?>
  

    <div style='border:1px solid#f00;'>
asdsadsa
    </div>
</body>
</html>