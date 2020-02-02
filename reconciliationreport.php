<?php include 'inc/conn.php'; ?>
<?php include 'inc/header.php'; ?>
<body>
<div class="wrapper">

  <!-- Main content -->
  <section class="invoice">

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
                    <th width="120">Item Name</th>
                    <th width="200">Counted</th>
                    <th width="100">Remarks</th>
          </tr>
          </thead>
          <tbody>

        <?php
          $date = $_GET['id'];
  
         $result4 = mysqli_query($conn, "SELECT * FROM reconciliation join inventory on reconciliation.inventoryID = inventory.id join uom on inventory.unitID = uom.id where reconciliation.date = '$date'");
            while ($row1 = mysqli_fetch_array($result4)) { 
        ?>
        
                  <tr>
                    <td><?php echo ucwords($row1['itemname']);?></td>
                    <td><?php echo $row1['current'];?> <?php echo $row1['uomname'];?></td>
                    <td><?php echo ucfirst($row1['remarks']);?></td>
                  </tr>
        <?php } ?>




         
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->

<script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>
</body>
</html>
