<?php

  // Connection DB
  $pdo = new PDO('mysql:dbname=sa_choco_pro;host=127.0.0.1;charset=utf8', 'root', ''); //passw: '220693'
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("set names utf8");


  $db = $pdo;#func.php

?>
