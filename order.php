<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php

if (isset($_POST['submit'])) {
  $getMenuName = $_POST['menuName'];
  $getQuantityMenu = mysqli_real_escape_string($conn, $_POST["qtyMenu"]);

  echo $getMenuName;
  $getTimestamp = date("Y-m-d H:i:s");

  $sql = mysqli_query($conn,"INSERT INTO `orders`(`qtyMenu`, `menu_id`, `timestamp`) VALUES ('$getQuantityMenu', '$getMenuName', '$getTimestamp')");
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
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="160">Order Name</th>
                    <th width="120">Quantity Menu</th>
                    <th width="120">Timestamp</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllOrders = mysqli_query($conn, "SELECT * FROM `orders` JOIN `menu` ON id=menu_id");
                  while($row = mysqli_fetch_assoc($getAllOrders)) {
                  ?>
                    <tr>
                      <td><?php echo $row['name'] ?></td>
                      <td><?php echo $row['qtyMenu'] ?></td>
                      <td><?php echo date('F-j-Y/ g:i A',strtotime($row['timestamp']));  ?></td>
                      <td>
                        <button type="button" class="btn btn-info btn-sm m-0 sendOrderId" data-toggle="modal" data-target="#viewOrder" id="<?php echo $row['order_id'] ?>">View Order</button>
                      </td>
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
        </div><!-- /.row -->

        <div class="modal fade" id="viewOrder">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">View Order</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Order Name</label>
                    <?php
                    $getVar = $variable-1;
                    $queryId = mysqli_query($conn, "SELECT * FROM `orders` JOIN `menu` ON id=menu_id WHERE `order_id`='$getVar'");
                    $row = mysqli_fetch_assoc($queryId);
                    echo $row['name'];
                    ?>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Quantity Menu</label>
                    <?php
                    echo $row['qtyMenu'];
                    ?>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3">Others</label>
                  <table id="dynamic_field" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="120">Item Name</th>
                    <th width="50">Quantity</th>
                    <th width="30">UOM</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    $queryOthers = mysqli_query($conn, "SELECT * FROM orders
                      JOIN menu ON id=menu_id
                      JOIN menuitems ON menuID=id
                      JOIN inventory ON addItemID = inventory.id
                      JOIN uom ON unitID = uom.id");
                      while ($row = mysqli_fetch_array($queryOthers)) {
                    ?>
                    <tr>
                      <td><?php echo $row['itemname']; ?></td>
                      <td><?php echo $row['qtyMenu']; ?></td>
                      <td><?php echo $row['uomname']; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                  </tbody>
                </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

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

              <form action="" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body">
                  <?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu Name</label>
                    
                    <select class="form-control" name="menuName" id="menuName">
                      <option value="none">Select Menu</option>
                      <?php foreach($cat as $category): ?>
                      <option value="<?= $category['menuID']; ?>"><?= ucfirst($category['name']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <label>Recipe</label>
                  <?php foreach($cat as $category): ?>
                    <?php $menu = mysqli_query($conn, "SELECT * FROM inventory join menuitems on inventory.id = menuitems.inventoryID join uom on inventory.unitID = uom.id where menuID = '".$category['menuID']."'");?>
                    <div id="menu<?= $category['menuID']; ?>">
                    <?php foreach($menu as $menuitems): ?>
                    <div class="form-group row">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="<?= ucfirst($menuitems['itemname']); ?>">
                      </div>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="<?= ucfirst($menuitems['quantity']); ?> <?= ucfirst($menuitems['uomname']); ?>">
                      </div>
                    </div>
                      <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Quantity Menu</label>
                    <input type="number" class="form-control" rows="3" name="qtyMenu" id="qtyMenu" required>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3">Others</label>
                  
                  
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
    $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control" name="itemname[]" id="itemname_'+i+'"><?php foreach($cat as $category): ?><option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?>, <?= ucfirst($category['uomname']); ?></option><?php endforeach; ?></select></td><td><input type="number" class="form-control" step=".01" name="quantity[]" id="quantity_'+i+'"></td><td><a type="button" name="remove" id="'+i+'" class="btn_remove btn btn-danger btn-xs">DELETE</a></td></tr>');
  });
  

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
  });
  
});

</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#sendOrderId').click(function() {
      $.post('order.php', {variable: this.id});
    });
  });
</script>
<?php foreach($cat as $category): ?>
<script type="text/javascript">
$(document).ready(function() {
 
  $('#menuName').change(function() {
    if( $(this).val() == 'none') {
      $("#menu<?= $category['id']; ?>").hide();
    } else{
      $("#menu<?= $category['id']; ?>").show();
    }
  });
 
});
</script>
<?php endforeach; ?>
</body>
</html>
