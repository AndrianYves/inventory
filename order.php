<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php

if (isset($_POST['submit'])) {
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $result1 = mysqli_query($conn, "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
  $query = mysqli_fetch_assoc($result1);
  $orderNumber = $query['order_id'] + 1;

  $getMenuName = $_POST['menuName'];
  $status = 'Pending';
  $getQuantityMenu = mysqli_real_escape_string($conn, $_POST["qtyMenu"]);

  $getTimestamp = date("Y-m-d H:i:s");

  $sql = mysqli_query($conn,"INSERT INTO `orders`(`order_id`, `qtyMenu`, `menu_id`, `timestamp`, `status`, `adminID`) VALUES ('$orderNumber', '$getQuantityMenu', '$getMenuName', '$getTimestamp', '$status', '$adminID')");
  // $result1 = mysqli_query($conn,"UPDATE inventory SET quantity=quantity + '$quantity' WHERE itemname='$inventory'");

  $number = count($_POST["inventoryID"]);
  for ($i=0; $i < $number; $i++) { 
    if(trim($_POST["inventoryID"][$i] != '')) {
      $newQuantity = mysqli_real_escape_string($conn, ($_POST["orderQuantity"][$i] * $getQuantityMenu));

    $sql1 = mysqli_query($conn, "INSERT INTO ordersitems(orderID, inventoryID, quantity) VALUES('$orderNumber', '".mysqli_real_escape_string($conn, $_POST["inventoryID"][$i])."', '$newQuantity')");   

    $sql2 = mysqli_query($conn,"UPDATE inventory SET quantity=quantity - '$newQuantity' WHERE id='".mysqli_real_escape_string($conn, $_POST["inventoryID"][$i])."'");
    }

  }

}

?>
<?php
if (isset($_POST['updateOrder'])) {
  $delivered = $_POST['delivered'];
  $orderID = $_POST['updateOrderID'];
  $lastUpdatedStatus = date("Y-m-d H:i:s");

  $result1 = mysqli_query($conn,"UPDATE orders SET status='$delivered', lastUpdatedStatus='$lastUpdatedStatus' WHERE order_id='$orderID'");

  $_SESSION['success'] = 'Order  Updated';
}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "order";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Orders</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Orders</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                    <h5><i class='icon fas fa-ban'></i> Error!</h5>
              ".$_SESSION['error']." 
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                  <h5><i class='icon fas fa-check'></i> Success!</h5>
              ".$_SESSION['success']." 
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-3">
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add New Order</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="160">Order Name</th>
                    <th width="120">Quantity Menu</th>
                    <th width="120">Timestamp</th>
                    <th width="50">Status</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllOrders = mysqli_query($conn, "SELECT * FROM `orders` JOIN `menu` ON id=menu_id");
                  while($row = mysqli_fetch_assoc($getAllOrders)) {
                     if ($row['status'] == 'Pending'){
                        $color = 'primary';
                        $orderstatus = 'none';
                      } elseif ($row['status'] == 'Canceled'){
                        $color = 'danger';
                        $orderstatus = 'hidden';
                      } elseif ($row['status'] == 'Returned'){
                        $color = 'warning';
                        $orderstatus = 'hidden';
                      } else {
                        $color = 'success';
                        $orderstatus = 'hidden';
                      }
                  ?>
                    <tr>
                      <td><?php echo ucwords($row['name']); ?></td>
                      <td><?php echo $row['qtyMenu']; ?></td>
                      <td><?php echo date('F-j-Y/ g:i A',strtotime($row['timestamp']));  ?></td>
                      <td class="text-center"><span class="badge bg-<?php echo $color; ?>"><?php echo $row['status']; ?></span></td>
                      <td>
                        <button type="button" class="btn btn-info btn-sm m-0" data-toggle="modal" data-target="#viewOrder<?php echo $row['order_id'] ?>">View Order</button>
                      </td>
                    </tr>

                    <div class="modal fade" id="viewOrder<?php echo $row['order_id'] ?>">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Orders</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">

                            <form action="order.php" method="POST">
                              <input class="form-check-input" type="hidden" name="updateOrderID" id="updateOrderID" value="<?php echo $row['order_id'];?>" style="visibility: hidden;">
                              <div class="card-body">
                                  <dt>Recipe</dt>
                                  <?php 
                                    $getAllmenu = mysqli_query($conn, "SELECT *, ordersitems.quantity as menuQuan from orders join ordersitems on orders.order_id = ordersitems.orderID join inventory on ordersitems.inventoryID = inventory.id join uom on inventory.unitID = uom.id where orders.order_id = '".$row['order_id']."'");
                                    while($row1 = mysqli_fetch_assoc($getAllmenu)) {
     
                                  ?><dl>
                                    <dd><?php echo ucwords($row1['itemname']); ?> <?php echo $row1['menuQuan']; ?> <?php echo $row1['uomname']; ?></dd>
                                  </dl>
                                     <?php
                                        }
                                        ?>
                                  <div class="form-check" style="visibility: <?php echo $orderstatus;?>">
                                  <input class="form-check-input" type="checkbox" value="Delivered" name="delivered">
                                  <label class="form-check-label">Delivered</label>
                                </div>
                              </div>
                              <!-- /.card-body -->
                            
                          </div>
                          <div class="modal-footer justify-content-between" style="visibility: <?php echo $orderstatus;?>">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" name="updateOrder" value = "Update Order">
                          </div>
                          </form>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                  <?php
                  }
                  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col -->
        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

      <div class="modal fade" id="item">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Order</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form action="order.php" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body">
                  <?php $cat = mysqli_query($conn, "SELECT name, id as menuID FROM menu order by name");?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu Name</label>
                    <select class="form-control" name="menuName" id="menuName" size="5">
                      <?php foreach($cat as $acategory): ?>
                      <option value="<?= $acategory['menuID']; ?>"><?= ucfirst($acategory['name']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <label>Recipe</label>
                  
                   <!-- Suggestions will be displayed in below div. -->
                   <div class="form-group" id="display"></div>


                  <div class="form-group">
                    <label for="exampleInputEmail1">Quantity Menu</label>
                    <input type="number" class="form-control" rows="3" name="qtyMenu" id="qtyMenu" required>
                  </div>
                </div>
                <!-- /.card-body -->
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <input type="submit" class="btn btn-primary" name="submit" value = "Add Order">
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

  </div><!-- /.content -->
</div><!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>

</div>
<!-- ./wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">
$(document).ready(function() {
   $("#menuName").change(function() {
       var name = $(this).val();
       if (name == "") {
           $("#display").html("");
       }
       else {
           $.ajax({
               type: "POST",
               url: "ajax.php",
               data: {
                   search: name
               },
               success: function(html) {
                   $("#display").html(html).show();
               }
           });
       }
   });
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
 
  // Initialize select2
  $("#menuName").select2();
});
</script>
<script src="plugins/select2/js/select2.full.min.js"></script>
</body>
</html>
