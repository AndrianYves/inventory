<?php include 'inc/conn.php'; ?>
<?php
if (isset($_POST['search'])) {
  $Name = $_POST['search'];
  $Query = "SELECT *, menuitems.quantity as menuQuan FROM inventory join menuitems on inventory.id = menuitems.inventoryID join uom on inventory.unitID = uom.id join menu on menuitems.menuID = menu.id where menu.id = '$Name'";
  $ExecQuery = MySQLi_query($conn, $Query);
  echo " <table class='table table-bordered table-striped'>
            <thead>
            <tr>
              <th width='120'>Item Name</th>
              <th width='50'>Quantity</th>
              <th width='30'>Unit</th>
            </tr>
            </thead>
            <tbody>
                  ";
  while ($Result = MySQLi_fetch_array($ExecQuery)) { ?>
  <tr>
    <td><input type="hidden" class="form-control" name="inventoryID[]" value="<?php echo $Result['inventoryID']; ?>"><?php echo ucfirst($Result['itemname']); ?></td>
    <td><input type="number" class="form-control" name="orderQuantity[]" value="<?php echo ucfirst($Result['menuQuan']); ?>"></td>
    <td><?php echo strtolower($Result['uomname']); ?></td>    
  </tr>
<?php
  }
}
?>
  </tbody>
</table>