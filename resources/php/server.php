<?php

  require_once("postgre.class.php");

  $db = new Db("nomenclator.ini");

	//Check sent variables and escape strings.
	isset($_POST['q']) && !empty($_POST['q'])? $query = $_POST['q'] : $query = null;
	isset($_POST['t']) && !empty($_POST['t'])? $tags = $_POST['t'] : $tags = null;
	isset($_POST['a']) && !empty($_POST['a'])? $abc = $_POST['a'] : $abc = null;
  
  //isset($_POST['pag']) && !empty($_POST['pag'])? $query = $_POST['pag'] : $pagination = null;


  // Case 1: nothing set (default) selects all streets starting with A
  if($query==null && $tags==null && $abc==null) {
    echo "Case1\n";
    $db->query("SELECT * FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE 'a%'");

  } 
  // Case 2: query set (basic search) search in table without tag filtering -> extend to optional tag input
  if($query!==null && $tags===null) {
    echo "Case2\n";
    $db->query("SELECT * FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE :query");
    $db->bind(":query", $query.'%');
  }
  // Case 3: abc selected, in that case is mandatory. If "Tots", get all table.
  if($abc!==null) {
    echo "Case3\n";

    if ($abc=='Tots') {
      $db->query("SELECT * FROM etrs89.eixos_viaris_unic WHERE nom_tip_comple IS NOT NULL AND TRIM(nom_tip_comple) <> '' LIMIT 5 offset 0");      
    } else {
      $db->query("SELECT * FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE :abc");
      $db->bind(":abc", $abc.'%');
    }
  }



  $rows = $db->resultSet();
  session_start();
  var_dump("Page: " . $_SESSION['page']);

  var_dump($rows);
  var_dump($db->getLastError());