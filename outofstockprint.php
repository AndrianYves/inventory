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
            <th>Product</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>status</th>
          </tr>
          </thead>
          <tbody>

        <?php
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
        
          <tr>
            <td><?php echo ucwords($row['itemname']);?></td>
            <td><?php echo ucfirst($row['description']);?></td>
            <td><?php echo $row['quantity'];?></td>
            <td><span class="badge bg-<?php echo $status; ?>"><?php echo $statustext; ?></span></td>
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
