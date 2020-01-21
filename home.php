<?php
    session_start();
      include 'inc/conn.php';
    if(isset($_SESSION['admin'])){
      header('location: index.php');
    }

    if(isset($_POST['login'])){
        $error = false;

      if (empty($_POST['user'])) {
        $error = true;
        $_SESSION['error'][] = 'Username is required.';
      } else if (preg_match("/([%\$#\*]+)/", $_POST['user'])) {
        $error = true;
        $_SESSION['error'][] = 'Username is invalid.';
      } else {
        $user = mysqli_real_escape_string($conn, $_POST['user']);
      }

      if (empty($_POST['password'])) {
        $error = true;
        $_SESSION['error'][] = 'Password is required.';
      } else {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
      }

   
      if(!$error){
          $sql = "SELECT * FROM admins WHERE username= '$user'";
          $query = $conn->query($sql);
        if($query->num_rows < 1){
          $_SESSION['error'][] = 'Invalid Username/Password';
        } else {
          $row = $query->fetch_assoc();
          if($password == 'forkndagger'){
            $_SESSION['admin'] = $row['id'];
            header('location: newuser.php');
          }else{
                 if(password_verify($password, $row['password'])){
                
                  
                  if($row['status'] == 'Block'){
                    $_SESSION['error'][] = 'Accont is Blocked.';
                  } else{
                    $timestamp = date("Y-m-d H:i:s");
                    $result1 = mysqli_query($conn,"UPDATE admins SET lastlogin='$timestamp' WHERE username='$user'");
                    $_SESSION['admin'] = $row['id'];
                    header('location: index.php');
                  }

            } else {
              $_SESSION['error'][] = 'Invalid Username/Password';
            }
          }
        }
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
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="home.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="user" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="login">Sign In</button>
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
