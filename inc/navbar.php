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
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php"><i class="nav-icon fas fa-sign-out-alt"></i> Sign-out</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
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
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
<?php endswitch; ?>

    </div>
    <!-- /.sidebar -->
  </aside>