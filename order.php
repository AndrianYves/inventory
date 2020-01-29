<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php

if (isset($_POST['submit'])) {
  mysqli_autocommit($conn, false);
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $result1 = mysqli_query($conn, "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
  $query = mysqli_fetch_assoc($result1);
  $orderNumber = $query['order_id'] + 1;
  $error = false;
  $tablenumber = mysqli_real_escape_string($conn, $_POST["tablenumber"]);

  $getTimestamp = date("Y-m-d H:i");
  // $result1 = mysqli_query($conn,"UPDATE inventory SET quantity=quantity + '$quantity' WHERE itemname='$inventory'");

  $number = count($_POST["menuName"]);
  for ($i=0; $i < $number; $i++) {
    if (mysqli_real_escape_string($conn, $_POST['qtyMenu'][$i]) < 1){
        $error = true;
        $_SESSION['error'][] = 'Invalid.';
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
       $_SESSION['error'][] = 'Not enough Recipe.';
      }

     }

    $sql4 = mysqli_query($conn, "INSERT INTO orderlist(orderID, menuID, quantity, total) VALUES('$orderNumber', '".mysqli_real_escape_string($conn, $_POST["menuName"][$i])."', '".mysqli_real_escape_string($conn, $_POST["qtyMenu"][$i])."', '".mysqli_real_escape_string($conn, $_POST["qtyMenu"][$i])."')"); 


  }

  $sql3 = mysqli_query($conn,"INSERT INTO `orders`(`order_id`, `table_number`, `timestamp`, `status`, `adminID`) VALUES ('$orderNumber', '$tablenumber', '$getTimestamp', 'Pending', '$adminID')");

  $sql5 = mysqli_query($conn,"UPDATE tables SET status = 'Occupied' WHERE tablenumber = '$tablenumber'");

    
    if (!$error) {
      mysqli_commit($conn);
      $_SESSION['success'] = 'Order '.$orderNumber.' Created';
    } else {
      mysqli_rollback($conn);
    }

}

?>
<?php
  if(isset($_POST['delivered'])){ 
    $orderID = $_POST['orderID'];
    $menuID = $_POST['menuID'];
    $quantitystatus = $_POST['quantitystatus'];

    $sql = mysqli_query($conn,"UPDATE orderlist SET `total`=total-'$quantitystatus', `delivered`=delivered+'$quantitystatus' WHERE orderID = '$orderID' and menuID = '$menuID' ");
    
    $sql2 = mysqli_query($conn, "SELECT * from orderlist where orderlist.orderID = '$orderID'");
    $row = mysqli_fetch_assoc($sql2);
     if($row['total'] == 0){
       $sql = mysqli_query($conn,"UPDATE orderlist SET status = 'Delivered' WHERE orderID = '$orderID' and menuID = '$menuID' ");
     }
   

    $_SESSION['success'] = 'Order item Delivered.';
  }

  if(isset($_POST['canceled'])){ 
    mysqli_autocommit($conn, false);
    $error = false;


    $getTimestamp = date("Y-m-d H:i");
    $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
    $orderID = $_POST['orderID'];
    $menuID = $_POST['menuID'];
    $quantitystatus = $_POST['quantitystatus'];

    if(empty($_POST['remarkCancel'])){
      $error = true;
      $_SESSION['error'][] = 'Remarks required.';
    } else {
       $remarks = $_POST['remarkCancel'];
    }
   

    $sql = mysqli_query($conn,"UPDATE orderlist SET `total`=total-'$quantitystatus', `canceled_returned_order`=canceled_returned_order+'$quantitystatus' WHERE orderID = '$orderID' and menuID = '$menuID' ");

    $sql3 = mysqli_query($conn, "INSERT INTO orderscancel(orderID, menuID, quantity, remarks, timestamp) VALUES('$orderID', '$menuID', '$quantitystatus', '$remarks', '$getTimestamp')") or die(mysqli_error($conn));

    
    $sql2 = mysqli_query($conn, "SELECT * from orderlist where orderlist.orderID = '$orderID'");
    $row = mysqli_fetch_assoc($sql2);
     if($row['total'] == 0 && $row['delivered'] == 0){
       $sql = mysqli_query($conn,"UPDATE orderlist SET status = 'Canceled' WHERE orderID = '$orderID' and menuID = '$menuID' ");
     }
   
  $queryQuantity = mysqli_query($conn, "SELECT * FROM ordersitems WHERE orderID = '$orderID'");
  $remarkCancel = 'Canceled';
  while ($execQuantity = mysqli_fetch_array($queryQuantity)) {
    $getInvId = $execQuantity['inventoryID'];
    $getQty = (($execQuantity['quantity'] / $row['quantity']) * $quantitystatus);

    $updateQty = mysqli_query($conn, "UPDATE `inventory` SET `quantity`=`quantity`+'$getQty' WHERE `id`='$getInvId'");

    $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, transactionID, timestamp, remarks, adminID) VALUES('$getInvId', '$getQty', 'Canceled', '$orderID', '$getTimestamp', '$remarkCancel', '$adminID')") or die(mysqli_error($conn));
  }

   
    if (!$error) {
      mysqli_commit($conn);
      $_SESSION['success'] = 'Order Canceled.';
    } else {
      mysqli_rollback($conn);
    }

  }

  if(isset($_POST['returned1'])){ 
    mysqli_autocommit($conn, false);
    $error = false;

    $getTimestamp = date("Y-m-d H:i");
    $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
    $orderID = $_POST['orderID'];
    $menuID = $_POST['menuID'];
    $quantitystatus = $_POST['quantitystatus'];

    if(empty($_POST['remarkCancel'])){
      $error = true;
          $_SESSION['error'][] = 'Remarks required.';
    } else {
       $remarks = $_POST['remarkCancel'];
    }
   
    $sql = mysqli_query($conn,"UPDATE orderlist SET `total`=total-'$quantitystatus', `canceled_returned_order`=canceled_returned_order+'$quantitystatus' WHERE orderID = '$orderID' and menuID = '$menuID' ");

    $sql3 = mysqli_query($conn, "INSERT INTO ordersreturn(orderID, menuID, quantity, remarks, timestamp) VALUES('$orderID', '$menuID', '$quantitystatus', '$remarks', '$getTimestamp')");

    $sql2 = mysqli_query($conn, "SELECT * from orderlist where orderlist.orderID = '$orderID'");
    $row = mysqli_fetch_assoc($sql2);
    if($row['delivered'] == 0){
       $sql = mysqli_query($conn,"UPDATE orderlist SET status = 'Returned' WHERE orderID = '$orderID' and menuID = '$menuID' ");
     }

    $queryQuantity = mysqli_query($conn, "SELECT * FROM ordersitems WHERE orderID = '$orderID'");
    $remarkCancel = 'Returned';  
    while ($execQuantity = mysqli_fetch_assoc($queryQuantity)) {
      $getInvId = $execQuantity['inventoryID'];
      $getQty = (($execQuantity['quantity'] / $row['quantity']) * $quantitystatus);

      $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, transactionID, timestamp, remarks, adminID) VALUES('$getInvId', '$getQty', 'Returned', '$orderID', '$getTimestamp', '$remarkCancel', '$adminID')") or die(mysqli_error($conn));
    } 

    if (!$error) {
      mysqli_commit($conn);
      $_SESSION['success'] = 'Order returned.';
    } else {
      mysqli_rollback($conn);
    }
  }

  if(isset($_POST['returned'])){ 
     mysqli_autocommit($conn, false);
    $error = false;
    $getTimestamp = date("Y-m-d H:i");
    $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
    $orderID = $_POST['orderID'];
    $menuID = $_POST['menuID'];
    $quantitystatus = $_POST['quantitystatus'];

    if(empty($_POST['remarkCancel'])){
      $_SESSION['error'][] = 'Remarks required.';
    } else {
       $remarks = $_POST['remarkCancel'];
    }

    $sql3 = mysqli_query($conn, "INSERT INTO ordersreturn(orderID, menuID, quantity, remarks, timestamp) VALUES('$orderID', '$menuID', '$quantitystatus', '$remarks', '$getTimestamp')");

    $sql = mysqli_query($conn,"UPDATE orderlist SET `delivered`=delivered-'$quantitystatus', `canceled_returned_order`=canceled_returned_order+'$quantitystatus' WHERE orderID = '$orderID' and menuID = '$menuID' ");
    
    $sql2 = mysqli_query($conn, "SELECT * from orderlist where orderlist.orderID = '$orderID'");
    $row = mysqli_fetch_assoc($sql2);
    if($row['delivered'] == 0){
       $sql = mysqli_query($conn,"UPDATE orderlist SET status = 'Returned' WHERE orderID = '$orderID' and menuID = '$menuID' ");
     }

    $queryQuantity = mysqli_query($conn, "SELECT * FROM ordersitems WHERE orderID = '$orderID'");
    $remarkCancel = 'Returned';  
    while ($execQuantity = mysqli_fetch_assoc($queryQuantity)) {
      $getInvId = $execQuantity['inventoryID'];
      $getQty = (($execQuantity['quantity'] / $row['quantity']) * $quantitystatus);

      $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, transactionID, timestamp, remarks, adminID) VALUES('$getInvId', '$getQty', 'Returned', '$orderID', '$getTimestamp', '$remarkCancel', '$adminID')") or die(mysqli_error($conn));
    } 

    if (!$error) {
      mysqli_commit($conn);
      $_SESSION['success'] = 'Order returned.';
    } else {
      mysqli_rollback($conn);
    }
  }

  if(isset($_POST['completeorder'])){ 
    $orderID = $_POST['orderID'];
    $tablenumber = $_POST['tablenumber'];
    $result1 = mysqli_query($conn,"UPDATE orders SET status='Completed' WHERE order_id='$orderID'");
    $result2 = mysqli_query($conn,"UPDATE tables SET status='Vacant' WHERE tablenumber='$tablenumber'");

    $_SESSION['success'] = 'Order Complete.';
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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-3">
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add New Order</button>
                  </div>
                   <div class="col-3 float-right">
                    <a href="orderhistory.php" class="btn btn-block btn-info">View Order History</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="160">Table Number</th>
                    <th width="160">Order Number</th>
                    <th width="120">Serve</th>
                    <th width="50">Status</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllOrders = mysqli_query($conn, "SELECT * FROM `orders` where timestamp >= CURRENT_DATE() ORDER BY timestamp DESC");
                  while($row = mysqli_fetch_assoc($getAllOrders)) {

                     if ($row['status'] == 'Pending'){
                        $getmenustatus = mysqli_query($conn, "SELECT count(*) as orderstatus from orderlist where orderlist.orderID = '".$row['order_id']."' and status is null");
                        $row9 = mysqli_fetch_assoc($getmenustatus);
                        if($row9['orderstatus'] > 0){
                          $button = NULL;
                        } else {
                          $button = '<form action="order.php" method="POST"><input class="form-check-input" type="hidden" name="tablenumber" id="tablenumber" value="'.$row['table_number'].'"><input class="form-check-input" type="hidden" name="orderID" id="orderID" value="'.$row['order_id'].'"><button type="submit" class="btn btn-primary" name="completeorder">Complete Order</button></form>';
                        }
                          $color = 'primary';
                      } else {
                        $button = NULL;
                        $color = 'success';
                      }
                  ?>
                    <tr>
                      <td><?php echo $row['table_number']; ?></td>
                      <td><?php echo $row['order_id']; ?></td>
                      <td><?php echo date('g:i A',strtotime($row['timestamp']));  ?> <?php echo ((strtotime($today) - strtotime($row['timestamp'])) / 60);  ?> minutes ago</td>
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

                           
                              <input class="form-check-input" type="hidden" name="updateOrderID" id="updateOrderID" value="<?php echo $row['order_id'];?>" style="visibility: hidden;">
                              <input class="form-check-input" type="hidden" name="tablenumber" id="tablenumber" value="<?php echo $row['table_number'];?>" style="visibility: hidden;">

                              
                              <div class="card-body">
                                  <dt>Orders</dt>
                                  <?php 
                                     $getAllmenu = mysqli_query($conn, "SELECT * from orderlist join menu on menu.id = orderlist.menuID where orderlist.orderID = '".$row['order_id']."'");
                                    while($row1 = mysqli_fetch_assoc($getAllmenu)) {
                                     $orderliststatus = $row1['status'];

                      if (empty($orderliststatus)){
                        $currentorderquantity = $row1['total'];
                        $orderliststatus = 'Pending';
                        $color = 'primary';
                        $orderstatus = '<div class="form-group row">
                                        <label for="tablenumber" class="col-sm-4 col-form-label">Quantity</label>
                                        <div class="col-sm-8">
                                        <input class="form-control" type="number" name="quantitystatus" id="quantitystatus" value="'.$row1['total'].'" min="1" max="'.$row1['total'].'">
                                        </div>
                                        </div>
                                        <div class="btn-group btn-group-toggle">
                                            <button class="btn btn-sm btn-success" type="submit" name="delivered" id="delivered"> Delivered</button>
                                            <button class="btn btn-sm btn-danger" type="submit" name="canceled" id="canceled"> Canceled</button>
                                             <button class="btn btn-sm btn-info" type="submit" name="returned1"> Returned</button>
                                        </div>';
                        $remarksstatus = '<textarea class="form-control" rows="3" placeholder="Enter remarks" name="remarkCancel"></textarea>';
                      } elseif ($orderliststatus == 'Canceled'){
                        $currentorderquantity = $row1['delivered'];
                        $color = 'danger';
                        $orderstatus = $row1['canceled_returned_order'];
                        $remarksstatus =NULL;
                      } elseif ($orderliststatus == 'Returned'){
                        $currentorderquantity = $row1['delivered'];
                        $color = 'warning';
                        $orderstatus = $row1['canceled_returned_order'];
                        $remarksstatus = NULL;
                      } else {
                        $color = 'success';
                       
                        if ($row['status'] == 'Completed'){
                           $currentorderquantity = $row1['delivered'];
                          $orderstatus = NULL;
                          $remarksstatus = NULL;
                        } else {
                          $currentorderquantity = $row1['delivered'];
                          $orderstatus = '<div class="form-group row">
                                      <label for="tablenumber" class="col-sm-4 col-form-label">Quantity</label>
                                      <div class="col-sm-8">
                                      <input class="form-check-input" type="number" name="quantitystatus" id="quantitystatus" value="'.$row1['delivered'].'" min="1" max="'.$row1['delivered'].'">
                                      </div>
                                      </div><div class="form-group clearfix">
                                        <button class="btn btn-sm btn-info d-inline" type="submit" name="returned"> Returned</button>
                                      </div>';
                          $remarksstatus = '<textarea class="form-control" rows="3" placeholder="Enter remarks" name="remarkCancel"></textarea>';
                        }
                      }
     
                                  ?>
                                   <form action="order.php" method="POST"><input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                                  <dl>
                                    <dd><?php echo ucwords($row1['name']); ?> <?php echo $currentorderquantity; ?> <span class="badge bg-<?php echo $color; ?>"><?php echo $orderliststatus; ?></span> <?php echo $orderstatus;?> <input class="form-check-input" type="hidden" name="orderID" id="orderID" value="<?php echo $row1['orderID'];?>">
                                  <input class="form-check-input" type="hidden" name="menuID" id="menuID" value="<?php echo $row1['menuID'];?>"></dd>
                                  </dl>
                                             <?php echo $remarksstatus;?>

                                   </form>
                            
                                     <?php
                                        }
                                        ?>
                              
                              </div>
                              <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?php echo $button;?>
                                  </div>
                            
                          </div>

                         
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
      
                  <div class="form-group row">
                    <label for="tablenumber" class="col-sm-4 col-form-label">Table Number</label>
                    <div class="col-sm-8">
                      <?php $table = mysqli_query($conn, "SELECT * from tables where status = 'Vacant'");?>
                      <select class="form-control" name="tablenumber" id="tablenumber" required>
                        <?php foreach($table as $vacant): ?>
                        <option value="<?= $vacant['tablenumber']; ?>"><?= ucfirst($vacant['tablenumber']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
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
              <a data-toggle='modal' data-target='#Addorder' href='#Addorder' class="btn btn-primary">Add Order</a>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

                        <div class="modal fade" id="Addorder">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Are you sure?</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">              
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="submit">Confirm</button>
                           </form>
                        </div>
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
