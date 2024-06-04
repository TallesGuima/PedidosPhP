<?php session_start(); ?>
<!DOCTYPE html>
<html>
      <?php 
      session_unset(); 
      session_destroy(); 
      header("Refresh: 0; URL=index.php");?>
</html>
        
      