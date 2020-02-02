<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>

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
            <h1 class="m-0 text-dark">Orders History</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Orders History</li>
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
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="120">Serve</th>
                    <th width="160">Order Number</th>
                    <th width="160">Table Number</th>
                    <th width="50">Status</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllOrders = mysqli_query($conn, "SELECT * FROM `orders` where timestamp <= CURRENT_DATE() ORDER BY timestamp DESC");
                  while($row = mysqli_fetch_assoc($getAllOrders)) {

                     if ($row['status'] == 'Pending'){
                        $getmenustatus = mysqli_query($conn, "SELECT count(*) as orderstatus from orderlist where orderlist.orderID = '".$row['order_id']."' and status is null");
                        $row9 = mysqli_fetch_assoc($getmenustatus);
                        if($row9['orderstatus'] > 0){
                       
                        } else {

                        }
                          $color = 'primary';
                      } else {
                        $color = 'success';
                      }
                  ?>
                    <tr>
                      <td><?php echo $row['table_number']; ?></td>
                      <td><?php echo $row['order_id']; ?></td>
                      <td><?php echo date('g:i A',strtotime($row['timestamp']));  ?></td>
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
             <a href="orderhistoryprint.php" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
          </div><!-- /.col -->
        </div><!-- /.row -->

      </div><!-- /.container-fluid -->



  </div><!-- /.content -->
</div><!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>

</div>
<!-- ./wrapper -->
<?php include 'inc/scripts.php'; ?>
<script src="plugins/select2/js/select2.full.min.js"></script>
</body>
</html>
