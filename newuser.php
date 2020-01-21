<?php
  include 'inc/conn.php';
    session_start();
    if(empty(isset($_SESSION['admin']))){
      header('location: index.php');
    }

if(isset($_POST['submit'])){ 
    $error = false;
    $password = $cpassword = "";
  if (empty($_POST['password'])) {
    $error = true;
    $_SESSION['error'][] = 'Password is required.';
  } else {
      $password = mysqli_real_escape_string($conn, $_POST["password"]);;
  }

  if (empty($_POST['confirmpassword'])) {
    $error = true;
    $_SESSION['error'][] = 'Retype Password is required.';
  } else {
      $cpassword = mysqli_real_escape_string($conn, $_POST["confirmpassword"]);;
  }


  if ($password == $cpassword) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql ="UPDATE admins SET password = '$hashedPassword' WHERE id = '".$_SESSION['admin']."'";
  } else {
     $error = true;
    $_SESSION['error'][] = 'Password not matched.';
  }

  if(!$error){
    mysqli_query($conn, $sql);
   $_SESSION['success'] = 'User Login';
   header('location: index.php');
   }

}

?>
<?php include 'inc/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Fork N' Dagger</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg"></p>

      <form action="newuser.php" method="post">
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="New Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="confirmpassword" placeholder="Retype password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="submit">Sign In</button>
          </div>
          <!-- /.col -->

        </div>


      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<?php include 'inc/scripts.php'; ?>

<script type="text/javascript">
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  });
</script>

</body>
</html>
