<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if (isset($_POST['submit'])) {
  $number = count($_POST['itemname']);
  $getTimestamp = date("Y-m-d H:i:s");
  $getRemarks = $_POST['remarks'];
  if ($number > 0) {

    $queryMaxId = mysqli_query($conn, "SELECT COUNT(*) as count FROM `returns`");
    $getMaxId = mysqli_fetch_assoc($queryMaxId);

    for ($i=0; $i < $number; $i++) { 
      if (trim($_POST['itemname'][$i] != '')) {
        $queryItemId = mysqli_query($conn, "SELECT * FROM inventory WHERE `itemname` = '".mysqli_real_escape_string($conn, $_POST["itemname"][$i])."'");
        $getItemId = mysqli_fetch_assoc($queryItemId);

        $insertSpoil = mysqli_query($conn, "INSERT INTO returns(return_id, inventory_id, return_type, return_date) VALUES ('".$getMaxId['count']."', '".$getItemId['id']."', 'spoilage', '$getTimestamp')");
      }
    }

    $insertRemarks = mysqli_query($conn, "UPDATE returns SET `remarks` = '$getRemarks' WHERE `return_id` = '".$getMaxId['count']."'");

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
                  <tr>
                    <th width="120">Spoilage Date</th>
                    <th width="150">Item Name</th>
                    <th width="50">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllSpoilage = mysqli_query($conn, "SELECT * FROM forkndagger.returns
                  JOIN inventory ON inventory_id = inventory.id");
                  while($row = mysqli_fetch_assoc($getAllSpoilage)) {
                  ?>
                    <tr>
                      <td><?php echo date('F-j-Y/ g:i A',strtotime($row['return_date']));  ?></td>
                      <td><?php echo $row['itemname'] ?></td>
                      <td>
                        <button type="button" class="btn btn-info btn-sm m-0 sendOrderId" data-toggle="modal" data-target="#viewSpoilage" id="<?php echo $row['return_id'] ?>">View Spoilage</button>
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
                    <div id="spoilageItem">
                    <?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>
                    <div class="row">
                      <div class="form-group col-xs-6">
                        <label for="exampleInputEmail1">Spoilage Date</label>
                        <input type="date" class="form-control" rows="1" name="qtyMenu[]" id="qtyMenu_1" required>
                      </div>
                      <div class="form-group col-xs-6">
                        <?php $cat = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory join uom on inventory.unitID = uom.id");?>

                        <label for="exampleInputEmail1">Item Name</label>

                        <select class="form-control" name="itemname[]" id="itemname_1">
                        <?php foreach($cat as $category): ?>
                          <option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?></option>
                        <?php endforeach; ?>
                      </select>
                      </div>
                  </div>
                </div>
                  <div class="form-group">
                    <button type="button" name="add" id="add" class="btn btn-success btn-xs">Add Items</button>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3">Remarks</label>
                    <input type="text" class="form-control" rows="2" name="remarks" id="remarks" required>
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

  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>

</div>
<!-- ./wrapper -->
<?php include 'inc/scripts.php'; ?>
<script type="text/javascript">
  $(document).ready(function(){
  var i=1;
  $('#add').click(function(){
    i++;
    $('#spoilageItem').append('<?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>'+
      '<div class="row" id="row'+i+'">'+
        '<div class="form-group col-xs-6">'+
          '<label for="exampleInputEmail1">Spoilage Date</label>'+
          '<input type="date" class="form-control" rows="1" name="qtyMenu[]" id="qtyMenu_'+i+'" required>'+
        '</div>'+
        '<div class="form-group col-xs-6">'+
          '<?php $cat = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory join uom on inventory.unitID = uom.id");?>'+
          '<label for="exampleInputEmail1">Item Name</label>'+
          '<select class="form-control" name="itemname[]" id="itemname_'+i+'">'+
            '<?php foreach($cat as $category): ?>'+
            '<option value="<?= $category['invID']; ?>"><?= ucfirst($category['itemname']); ?></option>'+
            '<?php endforeach; ?>'+
          '</select>'+
        '</div>'+
        '<div class="form-group col-xs-6">'+
          '<a type="button" name="remove" id="'+i+'" class="btn_remove btn btn-danger btn-xs">DELETE</a>'+
        '</div>'+
      '</div>');
  });
  

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('#row'+button_id+'').remove();
  });
  
});
  
</script>
</body>
</html>
