<?php
  try {
      $conn = new PDO('mysql:host=localhost;dbname=galer','root', '');
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e){
      exit($e->getMessage());
  }
?>