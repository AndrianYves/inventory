<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "return";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Return Orders</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Return Orders</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="160">Date</th>
                    <th width="120">Order Number</th>
                    <th width="50">Remarks</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllOrders = mysqli_query($conn, "SELECT * FROM `orders` join ledger on orders.order_id = ledger.transactionID where status = 'Returned' and ledger.transaction = 'Returned' group by orders.order_id");
                  while($row = mysqli_fetch_assoc($getAllOrders)) {
                  ?>
                    <tr>
                      <td><?php echo date('F-j-Y/ g:i A',strtotime($row['timestamp']));  ?></td>
                      <td><?php echo $row['order_id']; ?></td>
                      <td><?php echo $row['remarks']; ?></td>
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
                              </div>


                            
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
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>

</div>
<!-- ./wrapper -->
<?php include 'inc/scripts.php'; ?>
</body>
</html>