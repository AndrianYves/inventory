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
                    <th width="50">ID</th>
                    <th width="120">Item Name</th>
                    <th width="200">Description</th>
                    <th width="100">Category</th>
                    <th width="50">Quantity</th>
                    <th width="30">Unit</th>
                    <th width="30">Status</th>
          </tr>
          </thead>
          <tbody>

        <?php
        $result4 = mysqli_query($conn, "SELECT *, inventory.id as 'invID' FROM inventory left join category on inventory.categoryID = category.id left join uom on inventory.unitID = uom.id order by inventory.id ASC");
        while ($row = mysqli_fetch_array($result4)) {
                      if ($row['lowquantity'] >= $row['quantity']){  
                        if ($row['quantity'] != 0){
                          $status ='warning';
                          $statustext ='Low';
                        } else{
                          $status ='danger';
                          $statustext ='Empty';
                        }
                      } else {
                        $status ='success';
                        $statustext ='Normal';
                      }
        ?>
        
                  <tr>
                    <td><?php echo $row['invID'];?></td>
                    <td><?php echo ucwords($row['itemname']);?></td>
                    <td><?php echo ucfirst($row['description']);?></td>
                    <td><?php echo ucwords($row['categoryname']);?></td>
                    <td><?php echo $row['quantity'];?></td>
                    <td><?php echo strtolower($row['uomname']);?></td>
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
