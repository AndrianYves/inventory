<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php

if (isset($_POST['submit'])) {
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $result1 = mysqli_query($conn, "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
  $query = mysqli_fetch_assoc($result1);
  $orderNumber = $query['order_id'] + 1;
  $error = false;

  $getTimestamp = date("Y-m-d H:i:s");
  // $result1 = mysqli_query($conn,"UPDATE inventory SET quantity=quantity + '$quantity' WHERE itemname='$inventory'");


  mysqli_autocommit($conn, false);

  $number = count($_POST["menuName"]);
  for ($i=0; $i < $number; $i++) {
    if (mysqli_real_escape_string($conn, $_POST['qtyMenu'][$i]) < 1){
        $error = true;
        $_SESSION['error'] = 'Invalid.';
      }

    $result1 = mysqli_query($conn, "SELECT *, menuitems.quantity as menuQuan, inventory.id as invID FROM inventory join menuitems on inventory.id = menuitems.inventoryID join menu on menuitems.menuID = menu.id where menu.id = '".$_POST['menuName'][$i]."'");

    while ($row = MySQLi_fetch_array($result1)) { 
      $inventoryQTY = $row['menuQuan'];
      $inventoryID = $row['invID'];

      $newQuantity = ($inventoryQTY * mysqli_real_escape_string($conn, $_POST['qtyMenu'][$i]));
      $getItemQty = (-1 * $newQuantity);

      $sql2 = mysqli_query($conn,"UPDATE inventory SET quantity=quantity - '$newQuantity' WHERE id='$inventoryID'");

      $sql1 = mysqli_query($conn, "INSERT INTO ordersitems(orderID, inventoryID, quantity) VALUES('$orderNumber', '$inventoryID', '$newQuantity')");  

      $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, transactionID, timestamp, adminID) VALUES('$inventoryID', '$getItemQty', 'Order', '$orderNumber', '$getTimestamp', '$adminID')");
      if (!$sql2) {
       $error = true;
       $_SESSION['error'] = 'Not enough Recipe.';
      }

     }

    $sql3 = mysqli_query($conn, "INSERT INTO orderlist(orderID, menuID, quantity) VALUES('$orderNumber', '".mysqli_real_escape_string($conn, $_POST["menuName"][$i])."', '".mysqli_real_escape_string($conn, $_POST["qtyMenu"][$i])."')"); 


  }

  $sql3 = mysqli_query($conn,"INSERT INTO `orders`(`order_id`, `timestamp`, `status`, `adminID`) VALUES ('$orderNumber', '$getTimestamp', 'Pending', '$adminID')");
    
    if (!$error) {
      mysqli_commit($conn);
      $_SESSION['success'] = 'Order '.$orderNumber.' Created';
    } else {
      mysqli_rollback($conn);
    }

}

?>
<?php
if (isset($_POST['updateOrder'])) {
  $orderStatus = $_POST['orderStatus'];
  $orderID = $_POST['updateOrderID'];
  $lastUpdatedStatus = date("Y-m-d H:i:s");
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);

  switch ($orderStatus) {
    case "Canceled":
        $queryQuantity = mysqli_query($conn, "SELECT * FROM ordersitems WHERE orderID = '$orderID'");
        $remarkCancel = $_POST['remarkCancel'];
        while ($execQuantity = mysqli_fetch_array($queryQuantity)) {
          $getInvId = $execQuantity['inventoryID'];
          $getQty = $execQuantity['quantity'];

          $updateQty = mysqli_query($conn, "UPDATE `inventory` SET `quantity`=`quantity`+'$getQty' WHERE `id`='$getInvId'");

          $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, transactionID, remarks, timestamp, adminID) VALUES('$getInvId', '$getQty', 'Canceled', '$orderID', '$remarkCancel', '$lastUpdatedStatus', '$adminID')") or die(mysqli_fetch_array($conn));
        }
        break;
    case "Returned":
        $queryQuantity = mysqli_query($conn, "SELECT * FROM ordersitems WHERE orderID = '$orderID'");
        $remarkCancel = $_POST['remarkCancel'];  
        while ($execQuantity = mysqli_fetch_assoc($queryQuantity)) {
          $getInvId = $execQuantity['inventoryID'];
          $getQty = $execQuantity['quantity'];

          $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, transactionID, remarks, timestamp, adminID) VALUES('$getInvId', '$getQty', 'Returned', '$orderID', '$remarkCancel', '$lastUpdatedStatus', '$adminID')") or die(mysqli_fetch_array($conn));
        } 
        break;
    default:

  }

  $result1 = mysqli_query($conn,"UPDATE orders SET status='$orderStatus', lastUpdatedStatus='$lastUpdatedStatus' WHERE order_id='$orderID'");

  $_SESSION['success'] = 'Order '.$orderID.' '.$orderStatus;
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
                    <th width="160">Order Number</th>
                    <th width="120">Timestamp</th>
                    <th width="50">Status</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllOrders = mysqli_query($conn, "SELECT * FROM `orders`");
                  while($row = mysqli_fetch_assoc($getAllOrders)) {

                     if ($row['status'] == 'Pending'){
                        $color = 'primary';
                        $orderstatus = '<div class="btn-group btn-group-toggle" data-toggle="buttons">
                                          <label class="btn btn-success">
                                            <input type="radio" name="orderStatus" id="option2" autocomplete="off" value="Delivered"> Delivered
                                          </label>
                                          <label class="btn btn-warning">
                                            <input type="radio" name="orderStatus" id="option3" autocomplete="off" value="Canceled"> Canceled
                                          </label>
                                        </div><br><br>
                                        <textarea class="form-control" rows="3" placeholder="Enter remarks" name="remarkCancel"></textarea>';
                        $button = '<div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" name="updateOrder" value = "Update Order">
                                  </div>';
                      } elseif ($row['status'] == 'Canceled'){
                        $color = 'danger';
                        $orderstatus = NULL;
                        $button = NULL;
                      } elseif ($row['status'] == 'Returned'){
                        $color = 'warning';
                        $orderstatus = NULL;
                        $button = NULL;
                      } else {
                        $color = 'success';
                        $orderstatus = '<div class="form-group clearfix">
                                          <div class="icheck-primary d-inline">
                                            <input type="radio" id="radioPrimary1" name="orderStatus" value="Returned">
                                            <label for="radioPrimary1">Return
                                            </label>
                                          </div>
                                        </div><textarea class="form-control" rows="3" placeholder="Enter remarks..." name="remarkCancel"></textarea>';
                        $button = '<div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" name="updateOrder" value = "Update Order">
                                  </div>';
                      }
                  ?>
                    <tr>
                      <td><?php echo $row['order_id']; ?></td>

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
                              <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                              <div class="card-body">
                                  <dt>Recipe</dt>
                                  <?php 
                                     $getAllmenu = mysqli_query($conn, "SELECT * from orderlist join menu on menu.id = orderlist.menuID where orderlist.orderID = '".$row['order_id']."'");
                                    while($row1 = mysqli_fetch_assoc($getAllmenu)) {
     
                                  ?>
                                  <dl>
                                    <dd><?php echo ucwords($row1['name']); ?> <?php echo $row1['quantity']; ?></dd>
                                  </dl>
                                     <?php
                                        }
                                        ?>
                                  <?php echo $orderstatus;?>
                              </div>
                              <?php echo $button;?>
                              

                            
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
                
                <table id="dynamic_field" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="200">Menu Name</th>
                    <th width="30">Quantity</th>
  <!--                   <th width="150">Recipe</th> -->
                    <th width="100">Add</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <?php $cat = mysqli_query($conn, "SELECT name, id as menuID FROM menu order by name");?>
                    <td>
                       <select class="form-control" name="menuName[]" id="menuName_1" size="5" required>
                        <?php foreach($cat as $acategory): ?>
                        <option value="<?= $acategory['menuID']; ?>"><?= ucfirst($acategory['name']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control" step=".01" name="qtyMenu[]" id="quantity_1" required>
                    </td>
<!--                     <td><div class="form-group" id="display_1"></div></td> -->
                    <td>
                    <button type="button" name="add" id="add" class="btn btn-success btn-xs">Add Menu</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
<!-- Suggestions will be displayed in below div. -->
        <!--            <div class="form-group" id="display"></div> -->
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
<script>
$(document).ready(function(){
  var i=1;
  $('#add').click(function(){
    i++;
    $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control" name="menuName[]" id="menuName_'+i+'" size="5" required><?php foreach($cat as $acategory): ?><option value="<?= $acategory['menuID']; ?>"><?= ucfirst($acategory['name']); ?></option><?php endforeach; ?></select></td><td><input type="number" class="form-control" step=".01" name="qtyMenu[]" id="quantity_'+i+'" required></td><td><a type="button" name="remove" id="'+i+'" class="btn_remove btn btn-danger btn-xs">DELETE</a></td></tr>');
     $('#menuName_'+i+'').select2({ width: '100%' });
    // $('#menuName_'+i+'').change(function() {
    //    var name = $(this).val();
    //    if (name == "") {
    //        $('#display_'+i+'').html("");
    //    }
    //    else {
    //        $.ajax({
    //            type: "POST",
    //            url: "ajax.php",
    //            data: {
    //                search: name
    //            },
    //            success: function(html) {
    //                $('#display_'+i+'').html(html).show();
    //            }
    //        });
    //    }
    // });

  });
  

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
  });
  
});
</script>
<!-- <script type="text/javascript">
$(document).ready(function() {
   $("#menuName_1").change(function() {
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
</script> -->
<script type="text/javascript">
  $(document).ready(function(){
 
  // Initialize select2
  $("#menuName_1").select2({ width: '100%' });
});
</script>
<script type="text/javascript">(function($) {
  $.fn.uncheckableRadio = function() {
    var $root = this;
    $root.each(function() {
      var $radio = $(this);
      if ($radio.prop('checked')) {
        $radio.data('checked', true);
      } else {
        $radio.data('checked', false);
      }
        
      $radio.click(function() {
        var $this = $(this);
        if ($this.data('checked')) {
          $this.prop('checked', false);
          $this.data('checked', false);
          $this.trigger('change');
        } else {
          $this.data('checked', true);
          $this.closest('form').find('[name="' + $this.prop('name') + '"]').not($this).data('checked', false);
        }
      });
    });
    return $root;
  };
}(jQuery));

$('[type=radio]').uncheckableRadio();
$('button').click(function() {
  $('[value=V2]').prop('checked', true).trigger('change').trigger('click');
});</script>
<script src="plugins/select2/js/select2.full.min.js"></script>
</body>
</html>
