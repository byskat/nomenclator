<?php

  require_once("postgre.class.php");

  $db = new Db("web_umat_nomenclator.ini");

	//Check sent variables and escape strings.
	isset($_GET['q']) && !empty($_GET['q'])? $query = $_GET['q'] : $query = null;
	isset($_GET['t']) && !empty($_GET['t'])? $tags = $_GET['t'] : $tags = null;
	isset($_GET['a']) && !empty($_GET['a'])? $abc = $_GET['a'] : $abc = null;

  $tagsQuery = "";
  if($tags!==null) {
    $res = prepareTags($tags);
    $tags = ($res[0]);
    $tagsQuery = $res[1];
  }

  $totalItems = countData($db, $query, $tagsQuery, $abc);

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
  ($totalItems!=0)? $rows = fetchData($db, $query, $tagsQuery, $abc, $limit, $offset) : ""; 

  //Preparing response
  if(isset($_GET['a']) && !empty($_GET['a'])) {
    $q = $abc;
  } else if(isset($_GET['q']) && !empty($_GET['q'])) {
    $q = $query;
  } else {
    $q = 'Tots';
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

  $db->close();
  
  // Json encode and echo
  echo json_encode($response);

  function countData($db, $query, $tagsQuery, $abc) {

    $table = "carrerer_1";
    $field1 = "nom_normalitzat";

    // Find out how many items are in the table
    if($query==null && $abc==null) {
      $db->query("SELECT COUNT(*) FROM $table WHERE $field1 IS NOT NULL $tagsQuery");
    } 
    if($query!==null && $abc==null) {
      $db->query("SELECT COUNT(*) FROM $table WHERE tsv @@ plainto_tsquery(:query) $tagsQuery");
      $db->bind(":query", $query);
    }
    if($abc!==null) {
      if ($abc=='Tots') {
        $db->query("SELECT COUNT(*) FROM $table WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' $tagsQuery");
      } else {
        $db->query("SELECT COUNT(*) FROM $table WHERE LOWER($field1) LIKE :abc $tagsQuery");
        $db->bind(":abc", $abc.'%');
      }
    }
    $totalItems = $db->single();
    return intval($totalItems['count']);
  }

  function fetchData($db, $query, $tagsQuery, $abc, $limit, $offset) {
    $fields = "*";
    $table = "carrerer_1";
    $field1 = "nom_normalitzat";

    // Case 1: nothing set (default) selects all streets starting with A
    if($query==null && $abc==null) {
      $db->query("SELECT $fields FROM $table WHERE $field1 IS NOT NULL $tagsQuery ORDER BY $field1 LIMIT :limit OFFSET :offset");
    } 
    // Case 2: query set (basic search) search in table without tag filtering -> extend to optional tag input
    if($query!==null && $abc==null) {

      $db->query("SELECT $fields FROM $table WHERE tsv @@ plainto_tsquery(:query) $tagsQuery ORDER BY $field1 LIMIT :limit OFFSET :offset");
      $db->bind(":query", $query.'%');
    }
    // Case 3: abc selected, in that case is mandatory. If "Tots", get all table.
    if($abc!==null) {
      if ($abc=='Tots') {
        $db->query("SELECT $fields FROM $table WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' $tagsQuery ORDER BY $field1 LIMIT :limit OFFSET :offset");
      } else {
        $db->query("SELECT $fields FROM $table WHERE LOWER($field1) LIKE :abc $tagsQuery ORDER BY $field1 LIMIT :limit OFFSET :offset");
        $db->bind(":abc", $abc.'%');
      }
    }

    // Bind the query params
    $db->bind(':limit', $limit);
    $db->bind(':offset', $offset);
    return $db->resultSet();
  }

  function prepareTags($tagList) {

    $tagField = explode(",",$tagList); 
    $tagArray = [];
    $tagQuery = '';

    foreach ($tagField as &$field) {
      $valor = $_GET[$field];
      $tagArray[$field] = $valor;

      if($valor=='null') {
        $tagQuery .= "AND $field is null ";
      } else {
        $tagQuery .= "AND $field LIKE '$_GET[$field]' ";
      }
    }
    return [$tagArray,$tagQuery];
  }