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
            <th>Date</th>
            <th>Order Number</th>
            <th>Table Number</th>
          </tr>
          </thead>
          <tbody>

        <?php
        $result4 = mysqli_query($conn,  "SELECT * FROM `orders` where timestamp >= CURRENT_DATE() ORDER BY timestamp DESC");
        while ($row = mysqli_fetch_array($result4)) {

        ?>
        
          <tr>
            <td><?php echo date('g:i A',strtotime($row['timestamp']));  ?></td>
            <td><?php echo $row['table_number']; ?></td>
            <td><?php echo $row['order_id']; ?></td>
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
