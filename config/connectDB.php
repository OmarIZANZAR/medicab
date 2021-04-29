<?php
  // Create connection:
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>
    
<?php if($conn->connect_error): ?>
  <div class="alert alert-dismissible alert-danger" role="alert">
    <?php echo "Connection failed: " . $conn->connect_error; ?>
  </div>
<?php endif; ?>
