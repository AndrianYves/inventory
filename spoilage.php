<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if (isset($_POST['submit'])) {
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $getSpoilageDate = $_POST['spoilage_date'];
  $getItem = $_POST['item_name'];
  $getItemQty = (-1 * $_POST['spoilage_qty']);
  $getRemarks = $_POST['remarks'];
  $timestamp = date("Y-m-d H:i:s");
  $error = false;

  $updateQty = mysqli_query($conn, "UPDATE `inventory` SET `quantity`=`quantity` + '$getItemQty' WHERE `id`='$getItem'") or ($_SESSION['error'] = 'Quantity is below zero.' and $error = true);

  if(!$error){
    $insertReturn = mysqli_query($conn, "INSERT INTO spoilage(inventoryID, quantity, remarks, spoilagedate, timestamp, adminID) VALUES ('$getItem', '$getItemQty', '$getRemarks', '$getSpoilageDate', '$timestamp', '$adminID')") or die(mysqli_error($conn));

    $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, remarks, timestamp, adminID) VALUES('$getItem', '$getItemQty', 'Spoilage', '$getRemarks', '$timestamp', '$adminID')"); 

    $_SESSION['success'] = 'Spoilage added';

  }

  
}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "spoilage";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Spoilage</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Spoilage</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#spoilage">Add New Spoilage</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr align="center">
                    <th width="120">Spoilage Date</th>
                    <th width="150">Item Name</th>
                    <th width="100">Quantity</th>
                    <th width="150">Remarks</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllSpoilage = mysqli_query($conn, "SELECT *, spoilage.quantity as spoqty FROM spoilage join inventory on inventory.id = spoilage.inventoryID");
                  while($row = mysqli_fetch_assoc($getAllSpoilage)) {
                  ?>
                    <tr>
                      <td><?php echo date('F-j-Y/ g:i A',strtotime($row['spoilagedate']));  ?></td>
                      <td><?php echo ucwords($row['itemname']); ?></td>
                      <td><?php echo (-1 * $row['spoqty']); ?></td>
                      <td><?php echo ucfirst($row['remarks']); ?></td>
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

  <div class="modal fade" id="spoilage">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Spoilage</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form action="spoilage.php" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                  <div class="card-body">
                    <?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>
                    <div class="row">
                      <div class="form-group col-xs-6">
                        <label for="exampleInputEmail1">Spoilage Date</label>
                        <input type="date" class="form-control" rows="1" name="spoilage_date" id="spoilage_date" required>
                      </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-xs-6">
                        <?php $cat = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory join uom on inventory.unitID = uom.id");?>

                        <label for="exampleInputEmail1">Item Name</label>
                        <select class="form-control" name="item_name" id="item_name">
                        <?php foreach($cat as $category): ?>
                          <option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      </div>
                      <div class="form-group col-xs-6">
                        <label for="exampleInputEmail1">Quantity</label>
                        <input type="text" class="form-control" rows="1" name="spoilage_qty" id="spoilage_qty" required>
                      </div>
                  </div>
                  <div class="row">
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
              <input type="submit" class="btn btn-primary" name="submit" value = "Save">
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
