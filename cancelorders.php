<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if (isset($_POST['submit'])) {
  
}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "cancel";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Cancel Orders</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item active">Cancel Orders</li>
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#item">Add new cancel order</button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped display">
                  <thead>
                  <tr>
                    <th width="120">Menu name</th>
                    <th width="150">Menu quantity</th>
                    <th width="50">Remarks</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $getAllCancel = mysqli_query($conn, "SELECT * FROM forkndagger.returns
                  JOIN inventory ON inventory_id = inventory.id
                  WHERE return_type = 'cancel'");
                  while($row = mysqli_fetch_assoc($getAllCancel)) {
                  ?>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
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
              <form action="" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body cancelOrder">
                  <?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>
                  <div class="row">
                  <div class="form-group col-xs-6">
                    <label for="exampleInputEmail1">Menu Name</label>
                    <select class="form-control" name="menuName[]" id="menuName_1">
                      <option value="none">Select Menu</option>
                      <?php foreach($cat as $category): ?>
                      <option value="<?= $category['menuID']; ?>"><?= ucfirst($category['name']); ?></option>
                      <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="form-group col-xs-6">
                    <label for="exampleInputEmail1">Quantity</label>
                    <input type="number" class="form-control" rows="3" name="qty[]" id="qty_1" required>
                  </div>          
                  </div>
                  <button type="button" name="add" id="add" class="btn btn-success btn-xs" style="height: 30%; width: 15%;">+</button>
                </div>
                <div class="form-group">
                    <label for="inputEmail3">Remarks</label>
                    <input type="text" class="form-control" rows="2" name="remarks" id="remarks" required>
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

<script type="text/javascript">
  $(document).ready(function(){
  var i=1;
  $('#add').click(function(){
    i++;
    $('.cancelOrder').append('<div class="card-body">'+
      '<?php $cat = mysqli_query($conn, "SELECT *, id as menuID FROM menu");?>'+
      '<div class="row" id="row'+i+'">'+
        '<div class="form-group col-xs-6">'+
          '<label for="exampleInputEmail1">Menu Name</label>'+
          '<select class="form-control" name="menuName[]" id="menuName_'+i+'">'+
            '<option value="none">Select Menu</option>'+
            '<?php foreach($cat as $category): ?>'+
            '<option value="<?= $category['menuID']; ?>"><?= ucfirst($category['name']); ?></option>'+
          '<?php endforeach; ?>'+
        '</select>'+
      '</div>'+
      '<div class="form-group col-xs-6">'+
      '<label for="exampleInputEmail1">Quantity</label>'+
      '<input type="number" class="form-control" rows="3" name="qty[]" id="qty_'+i+'" required>'+
    '</div>'+
  '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="height: 30%; width: 15%;">-</button>'+
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
