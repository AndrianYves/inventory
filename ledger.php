<?php include 'inc/session.php'; ?>
<?php include 'inc/header.php'; ?>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php $current = "inventory";
include 'inc/navbar.php'; 
$previous = "javascript:history.go(-1)";
if(isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
}

  $id = $_GET['id'];

  $result = mysqli_query($conn, "SELECT * FROM inventory where id = '$id'");
  $row1 = mysqli_fetch_assoc($result);


?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php if ($role == 'Super User'): ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?php echo ucwords($row1['itemname']);?> Transactions</h1>
            
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <h4 class="mb-2 mb-sm-0 pt-1"><a class="text-right" href="<?= $previous ?>">Back</a></h4>

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
              
              <div class="card-body">
                  <table class="table table-bordered table-striped display">
                                    <thead>
                                    <tr>
                                    <th width="100">Date</th>
                                    <th width="30">Quantity</th>
                                    <th width="30">Transaction</th>
                                    <th width="100">By</th>
                                    <th width="100">Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result5 = mysqli_query($conn, "SELECT *, ledger.quantity as ledQuan, ledger.timestamp as ledTime FROM ledger join inventory on ledger.inventoryID = inventory.id join uom on inventory.unitID = uom.id join admins on ledger.adminID = admins.id where inventory.id = '". $id."' order by ledger.timestamp DESC");
                                    while ($row1 = mysqli_fetch_array($result5)) {
                                    if ($row1['ledQuan'] < 0){  
                                    $color ='red';
                                    } else {
                                    $color ='green';
                                    }
                                    ?>
                                    <tr>
                                    <td><?php echo date("F d, Y g:i A", strtotime($row1['ledTime']));?></td>
                                    <td class="text-<?php echo $color;?>"><?php echo $row1['ledQuan'];?><?php echo $row1['uomname'];?></td>
                                    <td><?php echo ucfirst($row1['transaction']);?> <?php echo $row1['transactionID'];?></td>
                                    <td><?php echo ucfirst($row1['lastname']);?>, <?php echo ucfirst($row1['firstname']);?></td>
                                    <td><?php echo ucfirst($row1['remarks']);?></td>
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
