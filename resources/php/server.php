<?php

  require_once("postgre.class.php");

  $db = new Db("web_umat_nomenclator.ini");

	//Check sent variables and set defaults
	isset($_GET['q']) && !empty($_GET['q'])? $query = $_GET['q'] : $query = null;
	isset($_GET['t']) && !empty($_GET['t'])? $tags = $_GET['t'] : $tags = null;
	isset($_GET['a']) && !empty($_GET['a'])? $abc = $_GET['a'] : $abc = null;

  // If neither a search string or abc letter is set, then show all elements
  ($query == null && $abc == null)? $abc = 'Tots' : null;

  $tagsQuery = '';
  $filter = null;

  
  if($tags!==null) {
    $tags = explode(',', $tags);
    foreach ($tags as $tag) {
      $valor = $_GET[$tag];
      $filter[$tag] = $valor;
    }
    
    foreach ($tags as $tag) {
      $valor = $_GET[$tag];
      if($valor=='null') {
        $tagsQuery .= "AND $tag is null ";
      } else {
        $tagsQuery .= "AND $tag LIKE '$_GET[$tag]' " ;
      }
    }
  }
  
  // Count all the elements that match the criteria
  $totalItems = fetchData('count', $db, $query, $tagsQuery, $abc);
  $totalItems = intval($totalItems['count']);

  // How many items to list per page
  $limit = 18;

  // How many pages will there be
  $pages = ceil($totalItems / $limit);

  // Get current page
  $page = min($pages, filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT, array(
    'options' => array(
      'default'  => 1,
      'min_range' => 1,
    ),
  )));

  // Calculate the offset for the query
  $offset = ($page - 1) * $limit;

  // If we have items in the db we make a new query with limit & offset
  ($totalItems!=0)? $rows = fetchData('select', $db, $query, $tagsQuery, $abc, $limit, $offset) : "";

  if(!isset($rows)) {
    $rows = [];
  }

  // Seting up associative array with response
  $response = [
    "q"   => $query,
    "abc" => $abc,
    "tag" => $filter,
    "num" => $totalItems,
    "pag" => $page,
    "lim" => $pages,
    "res" => $rows
  ];

  $db->close();
  
  // Json encode and echo
  echo json_encode($response);

  function fetchData($action, $db, $query, $tagsSQL, $abc, $limit='', $offset='') {

    $fields = "*";
    $table = "carrerer_1";
    $field1 = "nom_normalitzat";

    //Prepare if count or select
    switch ($action) {
      case 'count':
        $selectSQL = "SELECT COUNT($fields) FROM $table";
        $orderSQL = "";
        $limitSQL = "";
        break;
      case 'select':
        $selectSQL = "SELECT $fields FROM $table";
        $orderSQL = "ORDER BY $field1";
        $limitSQL = "LIMIT :limit OFFSET :offset";
        break;
      default:
        return 0;
    }

    // Case 1: nothing set (default) selects all streets
    if($query==null && $abc==null) {
      $db->query("$selectSQL WHERE $field1 IS NOT NULL $tagsSQL $orderSQL $limitSQL");
    } 
    // Case 2: query set -> search in table 
    if($query!==null && $abc==null) {
      $db->query("$selectSQL WHERE tsv @@ plainto_tsquery(:query) $tagsSQL $orderSQL $limitSQL");
      $db->bind(":query", $query.'%');
    }
    // Case 3: abc selected, in that case is mandatory. If "Tots", get all table.
    if($abc!==null) {
      if ($abc=='Tots') {
        $db->query("$selectSQL WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' $tagsSQL $orderSQL $limitSQL");
      } else {
        $db->query("$selectSQL WHERE LOWER($field1) LIKE :abc $tagsSQL $orderSQL $limitSQL");
        $db->bind(":abc", $abc.'%');
      }
    }

    // Bind the query params if needed
    if ($action == 'count') {
      return $db->single();
    } else {
      $db->bind(':limit', $limit);
      $db->bind(':offset', $offset);
      return $db->resultSet();
    }
  }

  /* TagList indicates the field of the tag selected, but the value of it (the 
  actual valor of filtering) is inside $_GET with the key of tag type.

  This function, recieves the tagType string (concat array) and returns
  */
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