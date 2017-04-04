<?php

  require_once("postgre.class.php");

  $db = new Db("web_umat_nomenclator.ini");

	//Check sent variables and escape strings.
	isset($_GET['q']) && !empty($_GET['q'])? $query = $_GET['q'] : $query = null;
	isset($_GET['t']) && !empty($_GET['t'])? $tags = $_GET['t'] : $tags = null;
	isset($_GET['a']) && !empty($_GET['a'])? $abc = $_GET['a'] : $abc = null;

  $totalItems = countData($db, $query, $tags, $abc);

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


  function countData($db, $query, $tags, $abc) {

    $table = "carrerer_1";
    $field1 = "nom_normalitzat";

    // Find out how many items are in the table
    if($query==null && $abc==null) {
      if($tags!==null) {
        $db->query("SELECT COUNT(*) FROM $table WHERE LOWER($field1) LIKE 'a%' AND tsv_tags @@ plainto_tsquery(:tags)");
        $db->bind(":tags", $tags);
      } else {
        $db->query("SELECT COUNT(*) FROM $table WHERE LOWER($field1) LIKE 'a%'");
      }
    } 
    if($query!==null && $abc==null) {
      if($tags!==null) {
        $db->query("SELECT COUNT(*) FROM $table WHERE tsv @@ plainto_tsquery(:query) AND tsv_tags @@ plainto_tsquery(:tags)");
        $db->bind(":query", $query);
        $db->bind(":tags", $tags);
      } else {
        $db->query("SELECT COUNT(*) FROM $table WHERE tsv @@ plainto_tsquery(:query)");
          //LOWER($field1) LIKE LOWER(:query)
        $db->bind(":query", $query.'%');
      }
    }
    if($abc!==null) {
      if($tags!==null) {
        if ($abc=='Tots') {
          $db->query("SELECT COUNT(*) FROM $table WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' AND tsv_tags @@ plainto_tsquery(:tags)");
        } else {
          $db->query("SELECT COUNT(*) FROM $table WHERE LOWER($field1) LIKE :abc AND tsv_tags @@ plainto_tsquery(:tags)");
          $db->bind(":abc", $abc.'%');
        }
        $db->bind(":tags", $tags);
      } else {
        if ($abc=='Tots') {
          $db->query("SELECT COUNT(*) FROM $table WHERE $field1 IS NOT NULL AND TRIM($field1) <> ''");
        } else {
          $db->query("SELECT COUNT(*) FROM $table WHERE LOWER($field1) LIKE :abc");
          $db->bind(":abc", $abc.'%');
        }
      }
    }

    $totalItems = $db->single();
    return intval($totalItems['count']);
  }


  function fetchData($db, $query, $tags, $abc, $limit, $offset) {
    $fields = "*";

    $table = "carrerer_1";
    $field1 = "nom_normalitzat";

    // Case 1: nothing set (default) selects all streets starting with A
    if($query==null && $abc==null) {
      if($tags!==null) {
        $db->query("SELECT $fields FROM $table WHERE LOWER($field1) LIKE 'a%' AND tsv_tags @@ plainto_tsquery(:tags) ORDER BY $field1 LIMIT :limit OFFSET :offset");
        $db->bind(":tags", $tags);
      } else {
        $db->query("SELECT $fields FROM $table WHERE LOWER($field1) LIKE 'a%' ORDER BY $field1 LIMIT :limit OFFSET :offset");
      }
    } 
    // Case 2: query set (basic search) search in table without tag filtering -> extend to optional tag input
    if($query!==null && $abc==null) {
      if($tags!==null) {
        $db->query("SELECT $fields FROM $table WHERE tsv @@ plainto_tsquery(:query) AND tsv_tags @@ plainto_tsquery(:tags) ORDER BY $field1 LIMIT :limit OFFSET :offset");
        $db->bind(":tags", $tags);
      } else {
        $db->query("SELECT $fields FROM $table WHERE tsv @@ plainto_tsquery(:query) ORDER BY $field1 LIMIT :limit OFFSET :offset");
      }
      $db->bind(":query", $query.'%');
    }
    // Case 3: abc selected, in that case is mandatory. If "Tots", get all table.
    if($abc!==null) {

      if($tags!==null){
        if ($abc=='Tots') {
          $db->query("SELECT $fields FROM $table WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' AND tsv_tags @@ plainto_tsquery(:tags) ORDER BY $field1 LIMIT :limit OFFSET :offset");
        } else {
          $db->query("SELECT $fields FROM $table WHERE LOWER($field1) LIKE :abc AND tsv_tags @@ plainto_tsquery(:tags) ORDER BY $field1 LIMIT :limit OFFSET :offset");
          $db->bind(":abc", $abc.'%');
        }
        $db->bind(":tags", $tags);
      } else {
        if ($abc=='Tots') {
          $db->query("SELECT $fields FROM $table WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' ORDER BY $field1 LIMIT :limit OFFSET :offset");
        } else {
          $db->query("SELECT $fields FROM $table WHERE LOWER($field1) LIKE :abc ORDER BY $field1 LIMIT :limit OFFSET :offset");
          $db->bind(":abc", $abc.'%');
        }
      }
    }

    // Bind the query params
    $db->bind(':limit', $limit);
    $db->bind(':offset', $offset);
    
    return $db->resultSet();
  }