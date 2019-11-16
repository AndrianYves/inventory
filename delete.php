<?php include 'inc/conn.php'; ?>
<?php
  if(empty($_GET['uomid'])){
    $result1 = mysqli_query($conn,"UPDATE inventory SET categoryID = NULL WHERE categoryID=".$_GET['categoryid']."");
    $result = mysqli_query($conn, "DELETE FROM category WHERE id=".$_GET['categoryid']."");
    $_SESSION['success'] = 'Category Deleted';
  } else{
    $result1 = mysqli_query($conn,"UPDATE inventory SET unitID = NULL WHERE unitID=".$_GET['uomid']."");
    $result = mysqli_query($conn, "DELETE FROM uom WHERE id=".$_GET['uomid']."");
    $_SESSION['success'] = 'Unit of Measurement Deleted';
  }
  header('location: settings.php');
?>