<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
        <?php
        $result3 = mysqli_query($conn, "SELECT count(*) as counted FROM inventory where lowquantity >= quantity or quantity = 0");
         $row1 = mysqli_fetch_assoc($result3);
         $counted = $row1['counted'];
          if ($counted > '0'){
         echo '<span class="badge badge-danger navbar-badge">'.$counted.'</span>';
        }
        ?>     
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php
        echo '<span class="dropdown-header">'.$counted.' Notifications</span>';
        $result4 = mysqli_query($conn, "SELECT * FROM inventory where lowquantity >= quantity or quantity = 0");
        while ($row = mysqli_fetch_array($result4)) {
            if ($row['lowquantity'] >= $row['quantity']){  
              if ($row['quantity'] != 0){
                $status ='warning';
                $statustext ='LOW';
              } else{
                $status ='danger';
                $statustext ='EMPTY';
              }
            }
        ?>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="nav-icon fas fa-th mr-2"></i> <?php echo $row['quantity'];?> <?php echo ucwords($row['itemname']);?>
            <span class="float-right badge bg-<?php echo $status; ?>"><?php echo $statustext; ?></span>
          </a>
          <a href="outofstockprint.php" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
        <?php } ?>


        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php"><i class="nav-icon fas fa-sign-out-alt"></i> Sign-out</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-gray elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="image/logo.png" alt="Fork N' Dagger Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Fork N' Dagger</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?php echo $user['firstname']. ' '.$user['lastname']; ?></a>
        </div>
      </div>
<?php 
switch ($role): ?>
<?php case "Super User": ?>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($current == 'Dashboard') {echo 'active';} ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="inventory.php" class="nav-link <?php if($current == 'inventory') {echo 'active';} ?>" >
              <i class="nav-icon fas fa-th"></i>
              <p>
                Inventory
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="menu.php" class="nav-link <?php if($current == 'menu') {echo 'active';} ?>">
              <i class="nav-icon fas fa-scroll"></i>
              <p>
                Menu
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="order.php" class="nav-link <?php if($current == 'order') {echo 'active';} ?>">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="spoilage.php" class="nav-link <?php if($current == 'spoilage') {echo 'active';} ?>">
              <i class="nav-icon fas fa-fill-drip"></i>
              <p>
                Spoilage
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="returnorders.php" class="nav-link <?php if($current == 'return') {echo 'active';} ?>">
              <i class="nav-icon fas fa-undo"></i>
              <p>
                Return Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="cancelorders.php" class="nav-link <?php if($current == 'cancel') {echo 'active';} ?>">
              <i class="nav-icon fas fa-times"></i>
              <p>
                Cancel Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="reconciliation.php" class="nav-link <?php if($current == 'reconciliation') {echo 'active';} ?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Reconciliation
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="users.php" class="nav-link <?php if($current == 'users') {echo 'active';} ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="settings.php" class="nav-link <?php if($current == 'settings') {echo 'active';} ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->

<?php break; ?>
<?php default: ?>
            <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($current == 'Dashboard') {echo 'active';} ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="order.php" class="nav-link <?php if($current == 'order') {echo 'active';} ?>">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="settings.php" class="nav-link <?php if($current == 'settings') {echo 'active';} ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
<?php endswitch; ?>

    </div>
    <!-- /.sidebar -->
  </aside>