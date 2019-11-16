<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if (isset($_POST['submit'])) {
  $categoryname = $_POST['categoryname'];
  $catid = $_POST['catid'];

  $result1 = mysqli_query($conn,"UPDATE category SET categoryname='$categoryname' WHERE id='$catid'");

  $_SESSION['success'] = 'Category Updated';
}
?>
<?php
if (isset($_POST['submit1'])) {
  $uom = $_POST['uom'];
  $id = $_POST['uomid'];

  $result1 = mysqli_query($conn,"UPDATE uom SET uomname='$uom' WHERE id='$id'");

  $_SESSION['success'] = 'Unit of Measurement Updated';
}
?>
<?php
if (isset($_POST['submit2'])) {
  $currentpassword = mysqli_real_escape_string($conn, $_POST["currentpassword"]);
  $result2 = mysqli_query($conn, "SELECT * FROM admins WHERE username = '".$user['username']."'");

  $row = mysqli_fetch_assoc($result2);

      if(password_verify($currentpassword, $row['password'])){
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

        if ($password == $cpassword) {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $result1 = mysqli_query($conn,"UPDATE admins SET password='$hashedPassword' WHERE username='".$user['username']."'");   
        
          $_SESSION['success'] = 'Password Changed';
        } else {
          $_SESSION['error'] = 'New Password not matched.';
        }

      } else {
        $_SESSION['error'] = 'Invalid current password.';
      }
}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "settings";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Settings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item active">Settings</li>
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
          <div class="col-4">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><h3 class="card-title">Category</h3></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-head-fixed">
                  <thead>
                  <tr>
                    <th width="170">Category Name</th>
                    <th width="30">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT * FROM category");
                  while ($row = mysqli_fetch_array($result3)) {
                        ?>
                  <tr>
                    <td><?php echo $row['categoryname'];?></td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a data-toggle='modal' data-target='#deletecat<?php echo $row['id']; ?>' href='#deletecat?id=<?php echo $row['id']; ?>' class="btn btn-danger"><i class="fas fa-trash"></i></a>
                        <a data-toggle='modal' data-target='#editcat<?php echo $row['id']; ?>' href='#editcat?id=<?php echo $row['id']; ?>' class="btn btn-info"><i class="fas fa-edit"></i></a>
                      </div>
                    </td>
                  </tr>
                  <div class="modal fade" id="editcat<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Category</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form role="form" action="settings.php" method="POST">               
                           <div class="card-body">
                              <div class="form-group">
                                <input class="form-check-input" type="hidden" name="catid" id="catid" value="<?php echo $row['id'];?>" style="visibility: hidden;">
                                <label for="exampleInputEmail1">Category Name</label>
                                <input type="text" class="form-control" name="categoryname" value="<?php echo $row['categoryname']; ?>">
                              </div>
                            </div>
                            <!-- /.card-body -->
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="submit">Update Category</button>
                        </div>
                        </form>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->

                  <div class="modal fade" id="deletecat<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Are you sure to Delete this Category?</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">              
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <a href='delete.php?categoryid=<?php echo $row['id']; ?>' class="btn btn-primary">Confirm</a>
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
            </div>
            <!-- /.card -->
          </div><!-- /.col -->

          <div class="col-3">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Unit of Measurement</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-head-fixed">
                  <thead>
                  <tr>
                    <th width="170">Unit Name</th>
                    <th width="30">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT * FROM uom");
                  while ($row = mysqli_fetch_array($result3)) {
                        ?>
                  <tr>
                    <td><?php echo $row['uomname'];?></td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a data-toggle='modal' data-target='#deleteuom<?php echo $row['id']; ?>' href='#deleteuom?id=<?php echo $row['id']; ?>' class="btn btn-danger"><i class="fas fa-trash"></i></a>
                        <a data-toggle='modal' data-target='#edituom<?php echo $row['id']; ?>' href='#edituom?id=<?php echo $row['id']; ?>' class="btn btn-info"><i class="fas fa-edit"></i></a>
                      </div>
                    </td>
                  </tr>
                  <div class="modal fade" id="edituom<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Unit of Measurement</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form role="form" action="settings.php" method="POST">               
                          <div class="card-body">
                            <div class="form-group">
                              <input class="form-check-input" type="hidden" name="uomid" id="uomid" value="<?php echo $row['id'];?>" style="visibility: hidden;">
                                <label for="exampleInputEmail1">Measurement Name</label>
                                <input type="text" class="form-control" name="uom" value="<?php echo $row['uomname']; ?>">
                            </div>
                          </div>
                            <!-- /.card-body -->
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit1" class="btn btn-primary" name="submit1">Update Category</button>
                        </div>
                        </form>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->

                  <div class="modal fade" id="deleteuom<?php echo $row['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Are you sure to Delete this Category?</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">              
                          
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <a href='delete.php?uomid=<?php echo $row['id']; ?>' class="btn btn-primary">Confirm</a>
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
            </div>
            <!-- /.card -->

          </div><!-- /.col -->


            <div class="col-5">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Update Password</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="height: 300px;">
                <form role="form" action="settings.php" method="POST">
                <div class="form-group row">
                  <label for="currentpassword" class="col-sm-5 col-form-label">Current Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" placeholder="Current Password" name="currentpassword" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="newpassword" class="col-sm-5 col-form-label">New Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" placeholder="New Password" name="password" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="cpassword" class="col-sm-5 col-form-label">Confirm Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword" required>
                  </div>
                </div>



                <button type="submit" class="btn btn-primary" name="submit2">Change Password</button>
              </form>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->

             </div><!-- /.col -->


        </div><!-- /.row -->

        <?php else: ?>
          <div class="row">
            <div class="col-5">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Update Password</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="height: 300px;">
                <form role="form" action="settings.php" method="POST">
                <div class="form-group row">
                  <label for="currentpassword" class="col-sm-5 col-form-label">Current Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" placeholder="Current Password" name="currentpassword" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="newpassword" class="col-sm-5 col-form-label">New Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" placeholder="New Password" name="password" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="cpassword" class="col-sm-5 col-form-label">Confirm Password</label>
                  <div class="col-sm-7">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword" required>
                  </div>
                </div>



                <button type="submit" class="btn btn-primary" name="submit2">Change Password</button>
              </form>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->

             </div><!-- /.col -->


        </div><!-- /.row -->


        <?php endif ?>

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
<script type="text/javascript">
$(document).ready(function() {
  $('table.display').DataTable();
} );
</script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

</body>
</html>
