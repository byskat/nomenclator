<?php

  require_once("postgre.class.php");

  $db = new Db("web_umat_nomenclator.ini");

  isset($_GET['id']) && !empty($_GET['id'])? $codi = $_GET['id'] : $codi = null;

  $db->query("SELECT * FROM carrerer_1 WHERE codi_car = :codi");
  $db->bind(":codi", $codi);
  
  echo json_encode($db->single());