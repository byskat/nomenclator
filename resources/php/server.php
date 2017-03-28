<?php

  require_once("postgre.class.php");

  $db = new Db("nomenclator.ini");

	//Check sent variables and escape strings.
	isset($_GET['q']) && !empty($_GET['q'])? $query = $_GET['q'] : $query = null;
	isset($_GET['t']) && !empty($_GET['t'])? $tags = $_GET['t'] : $tags = null;
	isset($_GET['a']) && !empty($_GET['a'])? $abc = $_GET['a'] : $abc = null;
  
  // Find out how many items are in the table
  if($query==null && $tags==null && $abc==null) {
    $db->query("SELECT COUNT(*) FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE 'a%'");
  } 
  if($query!==null && $tags==null && $abc==null) {
    $db->query("SELECT COUNT(*) FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE LOWER(:query)");
    $db->bind(":query", $query.'%');
  }
  if($abc!==null) {
    if ($abc=='Tots') {
      $db->query("SELECT COUNT(*) FROM etrs89.eixos_viaris_unic WHERE nom_tip_comple IS NOT NULL AND TRIM(nom_tip_comple) <> ''");
    } else {
      $db->query("SELECT COUNT(*) FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE :abc");
      $db->bind(":abc", $abc.'%');
    }
  }

  $totalItems = $db->single();
  $totalItems = $totalItems['count'];

  // How many items to list per page
  $limit = 18;

  // How many pages will there be
  $pages = ceil($totalItems / $limit);

  // What page are we currently on?
  $page = min($pages, filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT, array(
    'options' => array(
      'default'  => 1,
      'min_range' => 1,
    ),
  )));

  // Calculate the offset for the query
  $offset = ($page - 1) * $limit;

  // If we have items in the db we make a new query with limit & offset
  ($totalItems!=0)? $rows = fetchData($db, $query, $tags, $abc, $limit, $offset) : ""; 

  //Preparing response
  if(isset($_GET['a']) && !empty($_GET['a'])) {
    $q = $abc;
  } else if(isset($_GET['q']) && !empty($_GET['q'])) {
    $q = $query;
  } else {
    $q = 'a';
  }

  if(!isset($rows)) {
    $rows = [];
  }

  // Seting up associative array with response
  $response = [
    "q"   => $q,
    "tag" => $tags,
    "num" => $totalItems,
    "pag" => $page,
    "lim" => $pages,
    "res" => $rows
  ];

  // Json encode and echo
  echo json_encode($response);

  function fetchData($db, $query, $tags, $abc, $limit, $offset) {
    $fields = "nom_tip_comple, objectid_1, _date_modified";

    // Case 1: nothing set (default) selects all streets starting with A
    if($query==null && $tags==null && $abc==null) {
      $db->query("SELECT $fields FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE 'a%' ORDER BY nom_tip_comple LIMIT :limit OFFSET :offset");
    } 
    // Case 2: query set (basic search) search in table without tag filtering -> extend to optional tag input
    if($query!==null && $tags==null && $abc==null) {
      $db->query("SELECT $fields FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE LOWER(:query) ORDER BY nom_tip_comple LIMIT :limit OFFSET :offset");
      $db->bind(":query", $query.'%');
    }
    // Case 3: abc selected, in that case is mandatory. If "Tots", get all table.
    if($abc!==null) {
      if ($abc=='Tots') {
        $db->query("SELECT $fields FROM etrs89.eixos_viaris_unic WHERE nom_tip_comple IS NOT NULL AND TRIM(nom_tip_comple) <> '' ORDER BY nom_tip_comple LIMIT :limit OFFSET :offset");
      } else {
        $db->query("SELECT $fields FROM etrs89.eixos_viaris_unic WHERE LOWER(nom_tip_comple) LIKE :abc ORDER BY nom_tip_comple LIMIT :limit OFFSET :offset");
        $db->bind(":abc", $abc.'%');
      }
    }

    // Bind the query params
    $db->bind(':limit', $limit);
    $db->bind(':offset', $offset);
    $rows = $db->resultSet();
    
    return $rows;
  }

  //if($db->getLastError()) var_dump($db->getLastError());