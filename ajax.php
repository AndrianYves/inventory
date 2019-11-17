<?php include 'inc/conn.php'; ?>
<?php
if (isset($_POST['search'])) {
  $Name = $_POST['search'];
  $Query = "SELECT * FROM inventory join menuitems on inventory.id = menuitems.inventoryID join uom on inventory.unitID = uom.id join menu on menuitems.menuID = menu.id where menu.name like '%$Name%'";
  $ExecQuery = MySQLi_query($conn, $Query);
  echo '<ul>';
  while ($Result = MySQLi_fetch_array($ExecQuery)) { ?>
  <li onclick='fill("<?php echo $Result['itemname']; ?>")'>
  <a>
  <?php echo ucfirst($Result['itemname']); ?>  <?php echo ucfirst($Result['quantity']); ?>  <?php echo strtolower($Result['uomname']); ?>
   </li></a>
<?php
  }
}
?>
</ul>