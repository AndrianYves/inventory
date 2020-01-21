<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('table.display').DataTable({
  	"order": []
  });
} );
</script>
<script src="plugins/datatables/jquery.dataTables.js"></script>

<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

  <script src="plugins/toastr/toastr.min.js"></script>

  <script type="text/javascript">
    $(function() {  
      <?php
       if(isset($_SESSION['success'])){
        echo "toastr.success('".$_SESSION['success']." ')";
          unset($_SESSION['success']);
        }

      ?>  
    });

  </script>
  <?php
    if(isset($_SESSION['error'])){
      foreach($_SESSION['error'] as $error) {
        echo "<script type='text/javascript'>
                $(function() { 
               toastr.error('".$error."');});</script>";
        }
      unset($_SESSION['error']);
    }
  ?> 