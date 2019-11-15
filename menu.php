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

        $sql = "INSERT INTO menuitems(menuID, inventoryID, quantity) VALUES('$menuID', '".mysqli_real_escape_string($conn, $_POST["itemname"][$i])."', '".mysqli_real_escape_string($conn, $_POST["quantity"][$i])."')";
        mysqli_query($conn, $sql);
      }
    }
  }
    
    $_SESSION['success'] = 'Item Added';
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add Menu</button>
                  </div>
                  <div class="col-3">
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="50">ID</th>
                    <th width="120">Menu Name</th>
                    <th width="200">Description</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT * FROM menu");
                  while ($row = mysqli_fetch_array($result3)) {
                        ?>
                  <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo ucwords($row['name']);?></td>
                    <td><?php echo ucfirst($row['description']);?></td>
                    <td>
                      <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target='#edit<?php echo $row['id']; ?>'>View</button>
                    </td>
                  </tr>
          

                   <div class="modal fade" id="edit<?php echo $row['id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Recipes</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" action="menu.php" method="POST">               
               <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu Name</label>
                    <input type="text" class="form-control" name="menuName" value="<?php echo $row['name']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <input class="form-control" rows="3" value="<?php echo $row['description']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3">Recipe</label>

                    <?php $cat = mysqli_query($conn, "SELECT * FROM inventory join menuitems on inventory.id = menuitems.inventoryID where menuID = '".$row['id']."'");?>
                          <?php foreach($cat as $category): ?>
                            <h5><?= ucfirst($category['itemname']); ?></h5>
                            <h5><?= ucfirst($category['quantity']); ?></h5>
                    <?php endforeach; ?>
                 
                  </div>
                </div>
                <!-- /.card-body -->
              
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
                    <td>
                      <?php $cat = mysqli_query($conn, "SELECT * FROM inventory");?>
                      <select class="form-control" name="itemname[]" id="itemname_1">
                        <?php foreach($cat as $category): ?>
                          <option value="<?= $category['id']; ?>"><?= ucfirst($category['itemname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <input type="number" class="form-control" name="quantity[]" id="quantity_1">
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
              <button type="submit" class="btn btn-primary" name="submit">Add Menu</button>
            </div>
            </form>
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
<script type="text/javascript">
$(document).ready(function() {
  $('table.display').DataTable();
} );
</script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>

<script>
$(document).ready(function(){
  var i=1;
  $('#add').click(function(){
    i++;
    $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control" name="itemname[]" id="itemname_'+i+'"><?php foreach($cat as $category): ?><option value="<?= $category['id']; ?>"><?= ucfirst($category['itemname']); ?></option><?php endforeach; ?></select></td><td><input type="number" class="form-control" name="quantity[]" id="quantity_'+i+'"></td><td><a type="button" name="remove" id="'+i+'" class="btn_remove btn btn-danger btn-xs">DELETE</a></td></tr>');
  });
  

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
  });
  
});

</script>
</body>
</html>
