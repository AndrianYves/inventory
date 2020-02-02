<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if(isset($_POST['submit'])){ 
  $number = count($_POST["itemname"]);
  if($number > 0) {
    $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
    $menuName = mysqli_real_escape_string($conn, strtolower($_POST["menuName"]));
    $description = mysqli_real_escape_string($conn, strtolower($_POST["description"]));
    $timestamp = date("Y-m-d H:i:s");

    $sql = mysqli_query($conn, "INSERT INTO menu(name, description, timestamp, adminID) VALUES('$menuName', '$description', '$timestamp', '$adminID')");   

    $result2 = mysqli_query($conn, "SELECT * FROM menu where `name` = '$menuName' and `description` = '$description'");
    $row = mysqli_fetch_assoc($result2);
    $menuID = $row['id'];

    for($i=0; $i<$number; $i++) {
      if(trim($_POST["itemname"][$i] != '')) {

        $sql = "INSERT INTO menuitems(menuID, inventoryID, quantity, timestamp, adminID) VALUES('$menuID', '".mysqli_real_escape_string($conn, $_POST["itemname"][$i])."', '".mysqli_real_escape_string($conn, $_POST["quantity"][$i])."', '$timestamp', '$adminID')";
        mysqli_query($conn, $sql);
      }
    }
  }
    
    $_SESSION['success'] = 'Item Added';
}
?>
<?php
if (isset($_POST['editmenu'])) {
  $adminID = mysqli_real_escape_string($conn, $_POST["adminid"]);
  $editmenuname = $_POST['editmenuname'];
  $editmenudesription = $_POST['editmenudesription'];
  $editmenuid = $_POST['editmenuid'];
  $timestamp = date("Y-m-d H:i:s");

  $number = count($_POST["recipequantity"]);
  for ($i=0; $i < $number; $i++) {
    if(trim($_POST["recipequantity"][$i] != '')) {

      $sql = "REPLACE INTO menuitems VALUES('$editmenuid', '".mysqli_real_escape_string($conn, $_POST["recipename"][$i])."', '".mysqli_real_escape_string($conn, $_POST["recipequantity"][$i])."', '$timestamp', '$adminID')";

    // $sql = "INSERT INTO menuitems(menuID, inventoryID, quantity, timestamp, adminID) VALUES('$editmenuid', '".mysqli_real_escape_string($conn, $_POST["recipename"][$i])."', '".mysqli_real_escape_string($conn, $_POST["recipequantity"][$i])."', '$timestamp', '$adminID')";
    }
  }


  $result1 = mysqli_query($conn,"UPDATE menu SET name='$editmenuname', description='$editmenudesription' WHERE id='$editmenuid'");


  $_SESSION['success'] = 'Menu Updated';
}
?>

<?php
if (isset($_POST['deletemenu'])) {
   $deletemenuid = $_POST['deletemenuid'];

  $result = mysqli_query($conn, "DELETE FROM menu WHERE id='$deletemenuid'");
  $result1 = mysqli_query($conn, "DELETE FROM menuitems WHERE menuid='$deletemenuid'");

  $_SESSION['success'] = 'Menu Deleted';
}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "menu";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
            <?php if ($role == 'Super User'): ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Menu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Menu</li>
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add Menu</button>
                  </div>
                  <div class="col-3">
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="120">Menu Name</th>
                    <th width="220">Description</th>
                    <th width="40">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT * FROM menu");
                  while ($row = mysqli_fetch_array($result3)) {
                        ?>
                  <tr>
                    <td><?php echo ucwords($row['name']);?></td>
                    <td><?php echo ucfirst($row['description']);?></td>
                    <td align="center">
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target='#view<?php echo $row['id']; ?>'><i class="fas fa-eye"></i></button>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target='#delete<?php echo $row['id']; ?>'><i class="fas fa-trash"></i></button>
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target='#edit<?php echo $row['id']; ?>'><i class="fas fa-pencil-alt"></i></button>
                    </td>
                  </tr>

                  <div class="modal fade" id="edit<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Edit menu</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">              
                           <div class="card-body">
                              <div class="form-group">
                                <form role="form" action="menu.php" method="POST">
                                  <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                                <label for="exampleInputEmail1">Menu Name</label>
                                <input type="text" name="editmenuname" class="form-control" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="editmenuid" class="form-control" value="<?php echo $row['id']; ?>">
                              </div>
                              <div class="form-group">
                                <label for="exampleInputEmail1">Description</label>
                                <input type="text" name="editmenudesription" class="form-control" value="<?php echo $row['description']; ?>">
                              </div>
                              <label for="inputEmail3" class="col-sm-4 col-form-label">Recipe</label>
                              <?php $cat = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory join menuitems on inventory.id = menuitems.inventoryID join uom on inventory.unitID = uom.id where menuID = '".$row['id']."'");?>
                              
                              <?php $recipes = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory join uom on inventory.unitID = uom.id");?>

                              <div id="dynamic_edit">
                                <div class="row">
                                  <div class="col-sm-4">
                                    <?php foreach($cat as $category): ?>
                                    <select class="form-control" name="recipename[]" id="recipename_1">
                                      <option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?>, <?= ucfirst($category['uomname']); ?></option>
                                      <?php foreach($recipes as $addrecipes): ?>
                                        <option value="<?= $addrecipes['invID']; ?>"><?= ucfirst($addrecipes['itemname']); ?>, <?= ucfirst($addrecipes['uomname']); ?></option>
                                      <?php endforeach; ?>
                                       </select>
                                     <?php endforeach; ?>
                                  </div>
                                  <div class="col-sm-4">
                                    <?php foreach($cat as $catquan): ?>
                                    <input type="text" name="recipequantity[]" id="recipequantity_1" class="form-control" value="<?= ucfirst($catquan['quantity']); ?>">
                                    <?php endforeach; ?>
                                   </div>
                                  <div class="col-sm-4">
                                    <button type="button" name="addupdate" id="addupdate" class="btn btn-success btn-xs">Add Recipe</button>
                                  </div>
                                </div>
                              </div>
                                

                            </div>
                            <!-- /.card-body -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger mr-auto" data-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-success" id="editmenu" name="editmenu">Update</button>
                            </div>
                            </form>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->

                  <div class="modal fade" id="delete<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Are you sure?</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">              
                           <div class="card-body">
                              <div class="form-group">
                                <form role="form" action="menu.php" method="POST">
                                <label for="exampleInputEmail1">Are you sure you want to delete this? Deleting this menu will be permanent.</label>
                                <input type="hidden" name="deletemenuid" class="form-control" value="<?php echo $row['id']; ?>">
                              </div>
                              <div class="form-group">
                                <?php
                                $getMenuName = mysqli_query($conn, "SELECT name FROM menu WHERE id = ".$row['id']."");
                                $fetchRow = mysqli_fetch_assoc($getMenuName);
                                ?>
                                <label for="exampleInputEmail1">Menu name: <?php echo $fetchRow['name']; ?></label>
                              </div>
                            </div>
                            <!-- /.card-body -->

                          <div class="modal-footer" align="center">
                            <button type="button" class="btn btn-success mr-auto" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-success" id="deletemenu" name="deletemenu">Yes</button>
                          </form>
                          </div>
                          <!-- /.modal-footer -->
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->

                  <div class="modal fade" id="view<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Recipes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">              
                           <div class="card-body">
                              <div class="form-group">
                                <label for="exampleInputEmail1">Menu Name</label>
                                <p><?php echo $row['name']; ?></p>
                              </div>
                              <div class="form-group">
                                <label for="exampleInputEmail1">Description</label>
                                <p><?php echo $row['description']; ?></p>
                              </div>
                              <label for="inputEmail3" class="col-sm-4 col-form-label">Recipe</label>
                              <?php $cat = mysqli_query($conn, "SELECT * FROM inventory join menuitems on inventory.id = menuitems.inventoryID join uom on inventory.unitID = uom.id where menuID = '".$row['id']."'");?>
                              <?php foreach($cat as $category): ?>
                              <div class="row">
                                <div class="col-sm-4">
                                  <p><?= ucfirst($category['itemname']); ?></p>
                                </div>
                                <div class="col-sm-4">
                                  <p><?= ucfirst($category['quantity']); ?> <?= ucfirst($category['uomname']); ?></p>
                                </div>
                              </div>
                                <?php endforeach; ?>

                            </div>
                            <!-- /.card-body -->
                          
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
              <!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col -->
        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

      <div class="modal fade" id="item">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Menu</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" action="menu.php" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu Name</label>
                    <input type="text" class="form-control" name="menuName" placeholder="Enter Menu">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <textarea class="form-control" rows="3" placeholder="Enter Description..." name="description" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3">Recipe</label>
                  
                  <table id="dynamic_field" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="120">Item Name</th>
                    <th width="50">Quantity</th>
                    <th width="30">Add</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <?php $cat = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory join uom on inventory.unitID = uom.id");?>
                    <td>
                      <select class="form-control" name="itemname[]" id="itemname_1">
                        <?php foreach($cat as $category): ?>
                          <option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?>, <?= ucfirst($category['uomname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control" step=".01" name="quantity[]" id="quantity_1">
                    </td>
                    <td>
                    <button type="button" name="add" id="add" class="btn btn-success btn-xs">Add Recipe</button>
                    </td>
                  </tr>

                  </tbody>
                </table>
                </div>
                </div>
                <!-- /.card-body -->
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <a data-toggle='modal' data-target='#addmenu' href='#addmenu' class="btn btn-primary">Add Menu</a>
            </div>
           
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

                  <div class="modal fade" id="addmenu">
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



</div>
  <!-- /.content-wrapper -->
    <?php else: ?>
    <?php include 'forbidden.php'; ?>
  <?php endif ?>
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
    $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control" name="itemname[]" id="itemname_'+i+'"><?php foreach($cat as $category): ?><option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?>, <?= ucfirst($category['uomname']); ?></option><?php endforeach; ?></select></td><td><input type="number" class="form-control" step=".01" name="quantity[]" id="quantity_'+i+'"></td><td><a type="button" name="remove" id="'+i+'" class="btn_remove btn btn-danger btn-xs">DELETE</a></td></tr>');
  });
  

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
  });
  
});

</script>
<script>
$(document).ready(function(){
  var i=1;
  $('#addupdate').click(function(){
    i++;
    $('#dynamic_edit').append('<div id="row'+i+'"><div class="row"><div class="col-sm-4"><select class="form-control" name="recipename[]" id="recipename_'+i+'""><?php foreach($recipes as $addrecipes): ?><option value="<?= $addrecipes['invID']; ?>"><?= ucfirst($addrecipes['itemname']); ?>, <?= ucfirst($addrecipes['uomname']); ?></option><?php endforeach; ?></select></div><div class="col-sm-4"><input type="text" name="recipequantity[]" id="recipequantity_'+i+'" class="form-control"></div><div class="col-sm-4"><a type="button" name="remove" id="'+i+'" class="btn_remove btn btn-danger btn-xs">DELETE</a></div></div></div>');
  });
  

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
  });
  
});

</script>
</body>
</html>
