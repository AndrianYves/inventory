<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php
$title = "Home";
$current = "Dashboard";
include 'inc/navbar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <?php
                $sql = "SELECT * FROM inventory";
                $query = mysqli_query($conn, $sql);
                
                echo "<h3>".mysqli_num_rows($query)."</h3>";
                ?>

                <p>Inventory</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-th"></i>
              </div>
              <a href="inventory.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <?php
                $sql = "SELECT * FROM menu";
                $query = mysqli_query($conn, $sql);
                
                echo "<h3>".mysqli_num_rows($query)."</h3>";
                ?>

                <p>Total Menu</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-scroll"></i>
              </div>
              <a href="menu.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                $sql = "SELECT * FROM orders";
                $query = mysqli_query($conn, $sql);
                
                echo "<h3>".mysqli_num_rows($query)."</h3>";
                ?>

                <p>Total Orders</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-receipt"></i>
              </div>
              <a href="order.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                $sql = "SELECT * FROM spoilage";
                $query = mysqli_query($conn, $sql);
                
                echo "<h3>".mysqli_num_rows($query)."</h3>";
                ?>

                <p>Spoilage</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-fill-drip"></i>
              </div>
              <a href="spoilage.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>
        <!-- /.row -->

<div class="row">
          <div class="col-lg-7 col-6">
<div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Top 10 selling Menu</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Menu Name</th>
                      <th>Description</th>
                      <th>Total Orders</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                 
                    $sales = mysqli_query($conn, "SELECT *, count(orderlist.menuID) as sales FROM orderlist join menu on menu.id = orderlist.menuID group by menu.id limit 10");
                     while($row1 = mysqli_fetch_assoc($sales)) {
                  ?>

                    <tr>
                      <td><?php echo $row1['name']; ?></td>
                      <td><?php echo $row1['description']; ?></td>
                      <td><?php echo $row1['sales']; ?></td>
                    </tr>
                  <?php }?>
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
            </div>
          </div>



        </div>
        <!-- /.row -->


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

</body>
</html>
