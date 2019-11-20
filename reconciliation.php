<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if(isset($_POST['submit'])){ 
    $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
    $timestamp = date("Y-m-d H:i:s");
    $date = date("Y-m-d");

    $number = count($_POST["inventoryID"]);
    for($i=0; $i<$number; $i++) {
      $sql = mysqli_query($conn, "INSERT INTO reconciliation(inventoryID, current, remarks, date, timestamp, adminID) VALUES('".mysqli_real_escape_string($conn, $_POST["inventoryID"][$i])."', '".mysqli_real_escape_string($conn, $_POST["quantity"][$i])."', '".mysqli_real_escape_string($conn, $_POST["remarks"][$i])."', '$date', '$timestamp', '$adminID')");  
        mysqli_query($conn, $sql);
    }
    
    $_SESSION['success'] = 'Item Added';
}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "reconciliation";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reconciliation</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Reconciliation</li>
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
        <?php if ($role == 'Super User'): ?>
        <div class="row">
          <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Today</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">History</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                    <form role="form" action="reconciliation.php" method="POST">
                    <table class="table table-bordered table-striped display">
                    <thead>
                    <tr>
                      <th width="120">Item Name</th>
                      <th width="20">Inventory Quantity</th>
                      <th width="100">Current Quantity</th>
                      <th width="100">Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $result3 = mysqli_query($conn, "SELECT * FROM inventory join uom on inventory.unitID = uom.id");
                    while ($row = mysqli_fetch_array($result3)) {
                          ?>
                    <tr>
                      <td><input type="hidden" name="inventoryID[]" value="<?php echo $row['id'];?>"><?php echo ucwords($row['itemname']);?></td>
                      <td><?php echo $row['quantity'];?> <?php echo $row['uomname'];?></td>
                      <td><input type="number" step="0.01" class="form-control" name="quantity[]"></td>
                      <td><textarea class="form-control" rows="2" placeholder="Enter Remarks..." name="remarks[]"></textarea></td>
                    </tr>
          
                    <?php   } ?>

                    </tbody>
                  </table>
                  <div class="col-12 text-right">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#recon">Save</button>
                  </div>

 
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">

                     <table class="table table-bordered table-striped display">
                    <thead>
                    <tr>
                      <th width="120">Date</th>
                      <th width="120">Last Updated</th>
                      <th width="120">Updated By</th>
                      <th width="100">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $result3 = mysqli_query($conn, "SELECT *, reconciliation.id as reconID FROM reconciliation join admins on reconciliation.adminID = admins.id group by date order by date");
                    while ($row = mysqli_fetch_array($result3)) {
                          ?>
                    <tr>
                      <td><?php echo date("F d, Y", strtotime($row['date']));?></td>
                      <td><?php echo date("F d, Y H:i", strtotime($row['timestamp']));?></td>
                      <td><?php echo ucfirst($row['lastname']);?>, <?php echo ucfirst($row['firstname']);?></td>
                      <td><a data-toggle="modal" data-target='#view<?php echo $row['reconID']; ?>' class="btn btn-info btn-sm m-0">View</a></td>
                  </tr>
                  <div class="modal fade" id="view<?php echo $row['reconID']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title"><?php echo date("F d, Y", strtotime($row['date']));?></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
        

                              <dl>
                                <?php
                                  $result4 = mysqli_query($conn, "SELECT * FROM reconciliation join inventory on reconciliation.inventoryID = inventory.id join uom on inventory.unitID = uom.id where reconciliation.date = '".$row['date']."'");
                                  while ($row1 = mysqli_fetch_array($result4)) { ?>
                                <dt><?php echo ucwords($row1['itemname']);?></dt>
                                <dd>Counted: <?php echo $row1['current'];?> <?php echo $row1['uomname'];?> - <?php echo ucfirst($row1['remarks']);?></dd>
                                  <?php   } ?>
                              </dl>
                            </div>
                            <!-- /.card-body -->
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
          
                    <?php   } ?>

                    </tbody>
                  </table>
                  </div>

                </div>
              </div>
              <!-- /.card -->
            </div>
          </div><!-- /.col -->
        </div>
        <!-- /.row -->


      </div><!-- /.container-fluid -->


        <div class="modal fade" id="recon">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Are you sure to Save?</h4>
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
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

        <?php else: ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <?php echo "<h3>" . date("F d, Y") . "</h3>"; ?>
              </div>
              <!-- /.card-header -->
              <form role="form" action="reconciliation.php" method="POST">
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="120">Item Name</th>
                    <th width="20">Inventory Quantity</th>
                    <th width="100">Current Quantity</th>
                    <th width="100">Remarks</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT * FROM inventory join uom on inventory.unitID = uom.id");
                  while ($row = mysqli_fetch_array($result3)) {
                        ?>
                  <tr>
                    <td><input type="hidden" name="inventoryID[]" value="<?php echo $row['id'];?>"><?php echo ucwords($row['itemname']);?></td>
                    <td><?php echo $row['quantity'];?> <?php echo $row['uomname'];?></td>
                    <td><input type="number" step="0.01" class="form-control" name="quantity[]"></td>
                    <td><textarea class="form-control" rows="2" placeholder="Enter Remarks..." name="remarks[]"></textarea></td>
                  </tr>
        
                  <?php   } ?>

                  </tbody>
                </table>
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#recon">Save</button>
                </div>
              </div>
              <!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col -->
        </div>
        <!-- /.row -->


      </div><!-- /.container-fluid -->


        <div class="modal fade" id="recon">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Are you sure to Save?</h4>
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
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

        <?php endif ?>
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