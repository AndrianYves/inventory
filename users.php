<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<?php
if(isset($_POST['submit'])){ 
  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $firstname = mysqli_real_escape_string($conn, strtolower($_POST["firstname"]));
  $lastname = mysqli_real_escape_string($conn, strtolower($_POST["lastname"]));
  $password = mysqli_real_escape_string($conn, $_POST["password"]);
  $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $userrole = mysqli_real_escape_string($conn, $_POST["role"]);

  if ($password == $cpassword) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = mysqli_query($conn, "INSERT INTO admins(username, password, email, firstname, lastname, role) VALUES('$username', '$hashedPassword', '$email', '$firstname', '$lastname', '$userrole')");   
  
    $_SESSION['success'] = 'User Created';
  } else {
    $_SESSION['error'] = 'Password not matched.';
  }

}
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$current = "users";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php if ($role == 'Super User'): ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#user">Create New User</button>
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
                    <th width="50">Username</th>
                    <th width="120">Full Name</th>
                    <th width="200">Email</th>
                    <th width="100">Role</th>
                    <th width="100">Last Login</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $result3 = mysqli_query($conn, "SELECT * FROM admins");
                  while ($row = mysqli_fetch_array($result3)) {
                        ?>
                  <tr>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo ucwords($row['lastname']);?>, <?php echo ucwords($row['firstname']);?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo ucwords($row['role']);?></td>
                    <td><?php echo date("Y-m-d H:i", strtotime($row['lastlogin']));?></td>
                  </tr>
          
                  <?php   } ?>

                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->

        <div class="modal fade" id="user">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" action="users.php" method="POST">
                <input class="form-check-input" type="hidden" name="adminid" id="adminid" value="<?php echo $user['id'];?>" style="visibility: hidden;">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" placeholder="Username" name="username" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" placeholder="Email" name="email" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Full Name</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" placeholder="First Name" name="firstname" required>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="Last Name" name="lastname" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Confirm Password</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Role</label>
                    <div class="col-sm-9">
                      <select id="two" class="form-control" name="role">
                        <?php $ads = mysqli_query($conn, "SELECT * from admins");?>
                        <?php foreach($ads as $role): ?>
                          <option value="<?= $role['id']; ?>"><?= $role['role']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit">Create User</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

    </div>
    <!-- /.content -->
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

</body>
</html>
