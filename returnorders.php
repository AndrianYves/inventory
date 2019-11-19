<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if (isset($_POST['submit'])) {
  $getMenuName = $_POST['return_name'];
  $getQuantity = $_POST['return_qty'];
  $timestamp = date("Y-m-d H:i:s");
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $getRemarks = $_POST['remarks'];

  $insertReturn = mysqli_query($conn, "INSERT INTO returns(menu_id, return_type, return_date, return_qty, remarks, timestamp, adminID) VALUES ('$getMenuName','return','$timestamp', '$getQuantity','returned', '$timestamp', '$adminID')");


   $queryQuantity = mysqli_query($conn, "SELECT * FROM menuitems JOIN menu ON menuID = menu.id WHERE menuID = '$getMenuName'");

  while ($execQuantity = mysqli_fetch_assoc($queryQuantity)) {
    $getInvId = $execQuantity['inventoryID'];
    $getQty = $execQuantity['quantity'];

    $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, remarks, timestamp, adminID) VALUES('$getInvId', '$getQty', 'Return', '$getRemarks', '$timestamp', '$adminID')") or die(mysql_error($conn));
  } 

}
?>
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
              <div class="card-header">
                <div class="row">
                  <div class="col-3">
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add new return</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="120">Menu name</th>
                    <th width="150">Quantity</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllReturn = mysqli_query($conn, "SELECT * FROM returns
                  JOIN menu ON returns.menu_id=menu.id
                  where return_type = 'return'");
                  while($row = mysqli_fetch_assoc($getAllReturn)) {
                  ?>
                    <tr align="center">
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['return_qty']; ?></td>
                    </tr>
                    
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

  <div class="modal fade" id="item">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add return order</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form action="returnorders.php" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body returnOrder">
                  <?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>
                  <div class="row">
                  <div class="form-group col-xs-6">
                    <label for="exampleInputEmail1">Menu Name</label>
                    <select class="form-control" name="return_name" id="return_name">
                      <option value="none">Select Menu</option>
                      <?php foreach($cat as $category): ?>
                      <option value="<?= $category['menuID']; ?>"><?= ucfirst($category['name']); ?></option>
                      <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="form-group col-xs-6">
                    <label for="exampleInputEmail1">Quantity</label>
                    <input type="number" class="form-control" rows="3" name="return_qty" id="return_qty" required>
                  </div>   
                     <div class="form-group">
                    <label for="inputEmail3">Remarks</label>
                    <input type="text" class="form-control" rows="2" name="remarks" id="remarks" required>
                </div>       
                  </div> 
                </div>
                <!-- /.card-body -->
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <input type="submit" class="btn btn-primary" name="submit" value = "Add return order">
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>

</div>
<!-- ./wrapper -->
<?php include 'inc/scripts.php'; ?>
</body>
</html>