<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php

if(isset($_POST['submit'])){ 
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $timestamp = date("Y-m-d H:i:s");
  $itemname = mysqli_real_escape_string($conn, strtolower($_POST["itemname"]));
  $description = mysqli_real_escape_string($conn, strtolower($_POST["description"]));
  $category = mysqli_real_escape_string($conn, $_POST["category"]);
  $unit = mysqli_real_escape_string($conn, $_POST["unit"]);
  $low = mysqli_real_escape_string($conn, $_POST["low"]);
  $quantity = 0;

  if ($category == 'New'){
    $newCat = mysqli_real_escape_string($conn, strtolower($_POST["newCat"]));
    $result = mysqli_query($conn, "INSERT INTO category(categoryname) VALUES('$newCat')");

    $result2 = mysqli_query($conn, "SELECT * FROM category where `categoryname` = '$newCat'");
    $row = mysqli_fetch_assoc($result2);
    $catID = $row['id'];
  } else {
    $catID = $category;
  }

  if ($unit == 'New'){
    $newUnit = mysqli_real_escape_string($conn, strtolower($_POST["newUnit"]));
    $result = mysqli_query($conn, "INSERT INTO uom(uomname) VALUES('$newUnit')");

    $result2 = mysqli_query($conn, "SELECT * FROM uom where `uomname` = '$newUnit'");
    $row = mysqli_fetch_assoc($result2);
    $unitID = $row['id'];
  } else {
    $unitID = $unit;
  }

  $sql = mysqli_query($conn, "INSERT INTO inventory(itemname, description, quantity, lowquantity, categoryID, unitID, timestamp, adminID) VALUES('$itemname', '$description', '$quantity', '$low', '$catID', '$unitID', '$timestamp', '$adminID')");   
  
  $_SESSION['success'] = 'Item Added';
}
?>
<?php
if (isset($_POST['submitQuantity'])) {
  $inventory = $_POST['inventory'];
  $quantity = $_POST['quantity'];
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $remarks = mysqli_real_escape_string($conn, $_POST["remarks"]);
  $timestamp = date("Y-m-d H:i:s");

  $result1 = mysqli_query($conn,"UPDATE inventory SET quantity=quantity + '$quantity' WHERE id='$inventory'");

  if ($quantity < 0){
    $_SESSION['success'] = 'Quantity Subtracted'; 
  } else {
    $_SESSION['success'] = 'Quantity Added';
  }

  $sql = mysqli_query($conn, "INSERT INTO ledger(inventoryID, quantity, transaction, remarks, timestamp, adminID) VALUES('$inventory', '$quantity', 'Inventory', '$remarks', '$timestamp', '$adminID')"); 
}
?>
<?php
if (isset($_POST['submit1'])) {
  $edititemname = $_POST['edititemname'];
  $editdescription = $_POST['editdescription'];
  $itemid = $_POST['itemid'];
  $editcategory = mysqli_real_escape_string($conn, $_POST["editcategory"]);
  $editunit = mysqli_real_escape_string($conn, $_POST["editunit"]);
  $editlowquantity = mysqli_real_escape_string($conn, $_POST["editlowquantity"]);

  if ($editcategory == 'New'){
    $editnewCat = mysqli_real_escape_string($conn, strtolower($_POST["editnewCat"]));
    $result = mysqli_query($conn, "INSERT INTO category(categoryname) VALUES('$editnewCat')");

    $result2 = mysqli_query($conn, "SELECT * FROM category where `categoryname` = '$editnewCat'");
    $row = mysqli_fetch_assoc($result2);
    $catID = $row['id'];
  } else {
    $catID = $editcategory;
  }

  if ($editunit == 'New'){
    $editnewUnit = mysqli_real_escape_string($conn, strtolower($_POST["editnewUnit"]));
    $result = mysqli_query($conn, "INSERT INTO uom(uomname) VALUES('$editnewUnit')");

    $result2 = mysqli_query($conn, "SELECT * FROM uom where `uomname` = '$editnewUnit'");
    $row = mysqli_fetch_assoc($result2);
    $unitID = $row['id'];
  } else {
    $unitID = $editunit;
  }

  $result1 = mysqli_query($conn,"UPDATE inventory SET itemname='$edititemname', description='$editdescription', lowquantity='$editlowquantity', categoryID='$catID', unitID='$unitID' WHERE id='$itemid'");

  $_SESSION['success'] = 'Inventory Updated';
}
?>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "inventory";
include 'inc/navbar.php'; ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php if ($role == 'Super User'): ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Inventory</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Inventory</li>
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add New Item</button>
                  </div>
                  <div class="col-3">
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#quantity">Add or Subtract Quantity</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="50">ID</th>
                    <th width="120">Item Name</th>
                    <th width="200">Description</th>
                    <th width="100">Category</th>
                    <th width="50">Quantity</th>
                    <th width="30">Unit</th>
                    <th width="30">Status</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory left join category on inventory.categoryID = category.id left join uom on inventory.unitID = uom.id");
                  while ($row = mysqli_fetch_array($result3)) {
                      if ($row['lowquantity'] >= $row['quantity']){  
                        if ($row['quantity'] != 0){
                          $status ='warning';
                          $statustext ='Low';
                        } else{
                          $status ='danger';
                          $statustext ='Empty';
                        }
                      } else {
                        $status ='success';
                        $statustext ='Normal';
                      }
                      
                
                        ?>
                  <tr>
                    <td><?php echo $row['invID'];?></td>
                    <td><?php echo ucwords($row['itemname']);?></td>
                    <td><?php echo ucfirst($row['description']);?></td>
                    <td><?php echo ucwords($row['categoryname']);?></td>
                    <td><?php echo $row['quantity'];?></td>
                    <td><?php echo strtolower($row['uomname']);?></td>
                    <td><span class="badge bg-<?php echo $status; ?>"><?php echo $statustext; ?></span></td>
                    <td>
                        <a data-toggle='modal' data-target='#view<?php echo $row['invID']; ?>' class="btn btn-info"><i class="fas fa-edit"></i></a>
                    </td>
                  </tr>
                  <div class="modal fade" id="view<?php echo $row['invID']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Edit Inventory</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form class="form-horizontal" action="inventory.php" method="POST">
                            <div class="card-body">
                              <div class="form-group row">
                                <input class="form-check-input" type="hidden" name="itemid" id="itemid" value="<?php echo $row['invID'];?>" style="visibility: hidden;">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Item Name</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" value="<?php echo $row['itemname']; ?>" name="edititemname">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Description</label>
                                <div class="col-sm-8">
                                  <textarea class="form-control" rows="3" name="editdescription"><?php echo $row['description']; ?></textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Category</label>
                                <div class="col-sm-5">
                                  <select id="one<?php echo $row['invID']; ?>" class="form-control" name="editcategory">
                                    <option value="New">Create Category</option>
                                    <?php $cat = mysqli_query($conn, "SELECT * from category");?>
                                    <?php foreach($cat as $category): ?>
                                      <option value="<?= $category['id']; ?>"><?= ucfirst($category['categoryname']); ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                                <div class="col-sm-3">
                                  <input type="text" class="form-control" id="editcat<?php echo $row['invID']; ?>" name="editnewCat" value="<?php echo $row['categoryname']; ?>">
                                </div>
                              </div>

                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="submit1">Update Inventory</button>
                        </div>
                        </form>
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
              <!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Ledger</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="100">Date</th>
                    <th width="150">Item Name</th>
                    <th width="30">Quantity</th>
                    <th width="30">Transaction</th>
                    <th width="100">By</th>
                    <th width="100">Remarks</th>
                  </tr>
                  </thead>
                       <?php
                  $result3 = mysqli_query($conn, "SELECT *, ledger.quantity as ledQuan, ledger.timestamp as ledTime FROM ledger join inventory on ledger.inventoryID = inventory.id join uom on inventory.unitID = uom.id join admins on ledger.adminID = admins.id");
                  while ($row = mysqli_fetch_array($result3)) {
                      if ($row['ledQuan'] < 0){  
                        $color ='red';
                      } else {
                        $color ='green';
                      }
                      
                        ?>
                  <tr>
                    <td><?php echo date("F d, Y H:i", strtotime($row['ledTime']));?></td>
                    <td><?php echo ucwords($row['itemname']);?></td>
                    <td class="text-<?php echo $color;?>"><?php echo $row['ledQuan'];?><?php echo $row['uomname'];?></td>
                    <td><?php echo ucfirst($row['transaction']);?> <?php echo $row['transactionID'];?></td>
                    <td><?php echo ucfirst($row['lastname']);?>, <?php echo ucfirst($row['firstname']);?></td>
                    <td><?php echo ucfirst($row['remarks']);?></td>
                  </tr>
                  <?php   } ?>

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
              <h4 class="modal-title">Add Item</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" action="inventory.php" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Item Name</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" placeholder="Name" name="itemname" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Description</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" rows="3" placeholder="Enter Description..." name="description" required></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Category</label>
                    <div class="col-sm-5">
                      <select id="three" class="form-control" name="category">
                        <option value="New">Create Category</option>
                        <?php $cat = mysqli_query($conn, "SELECT * from category");?>
                        <?php foreach($cat as $category): ?>
                          <option value="<?= $category['id']; ?>"><?= ucfirst($category['categoryname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="inputCategory" name="newCat" placeholder="Category">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Unit of Measurement</label>
                    <div class="col-sm-5">
                      <select id="four" class="form-control" name="unit">
                        <option value="New">Create Unit</option>
                        <?php $uom = mysqli_query($conn, "SELECT * from uom");?>
                        <?php foreach($uom as $unit): ?>
                          <option value="<?= $unit['id']; ?>"><?= strtoupper($unit['uomname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="inputUnit" name="newUnit" placeholder="Unit">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Set Low Stock Notifications</label>
                    <div class="col-sm-8">
                      <input  type="text" class="form-control" name="low">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit">Add Item</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <div class="modal fade" id="quantity">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Quantity</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <form class="form-horizontal" action="inventory.php" method="POST">
                <div class="card-body">
                  <div class="form-group row">
                    <h5 class="text-danger">Note: Input negative number to subtract quantity.</h5>
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Item Name</label>
                    <div class="col-sm-9">
                      <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                      <select class="form-control" name="inventory">
                        <?php $inventory = mysqli_query($conn, "SELECT * from inventory");?>
                        <?php foreach($inventory as $inv): ?>
                          <option value="<?= $inv['id']; ?>"><?= ucfirst($inv['itemname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Quantity</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" step="0.01" placeholder="Quantity" name="quantity">
                    </div>
                  </div>
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Remarks</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" rows="3" placeholder="Enter remarks..." name="remarks"></textarea>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submitQuantity">Add Quantity</button>
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
<?php else: ?>
  <?php include 'forbidden.php'; ?>
<?php endif ?>

  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>

</div>
<!-- ./wrapper -->

<!-- page script -->
<?php include 'inc/scripts.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
  $('#three').change(function() {
    if( $(this).val() == 'New') {
      $('#inputCategory').prop( "disabled", false );
    } else {       
      $('#inputCategory').prop( "disabled", true );
    }
  });

  $('#four').change(function() {
    if( $(this).val() == 'New') {
      $('#inputUnit').prop( "disabled", false );
    } else {       
      $('#inputUnit').prop( "disabled", true );
    }
  });

});
</script>
<?php
$result4 = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory left join category on inventory.categoryID = category.id left join uom on inventory.unitID = uom.id");
while ($row = mysqli_fetch_array($result4)) {
      ?>
<script type="text/javascript">
$(document).ready(function() {
  $('#one<?php echo $row['invID']; ?>').change(function() {
    if( $(this).val() == 'New') {
      $('#editcat<?php echo $row['invID']; ?>').prop( "disabled", false );
    } else {       
      $('#editcat<?php echo $row['invID']; ?>').prop( "disabled", true );
    }
  });

  $('#two<?php echo $row['invID']; ?>').change(function() {
    if( $(this).val() == 'New') {
      $('#edituom<?php echo $row['invID']; ?>').prop( "disabled", false );
    } else {       
      $('#edituom<?php echo $row['invID']; ?>').prop( "disabled", true );
    }
  });

});
</script>
<?php } ?>
</body>
</html>
