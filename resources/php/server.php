<?php

  require_once("postgre.class.php");

  $db = new Db("web_umat_nomenclator.ini");

  // MODAL (more info)
  isset($_GET['id']) && !empty($_GET['id'])? $codi = $_GET['id'] : $codi = null;

  // If id detected, then we only pass the specific item and exit
  if($codi!==null) {
    $db->query("SELECT * FROM carrerer WHERE codi_car = :codi");
    $db->bind(":codi", $codi);
    $db->close();

    echo json_encode($db->single());
    exit(0);
  }

  // RESULT SET (list of items)
	//Check sent variables and set defaults
	isset($_GET['q']) && !empty($_GET['q'])? $query = $_GET['q'] : $query = null;
	isset($_GET['t']) && !empty($_GET['t'])? $tags = $_GET['t'] : $tags = null;
	isset($_GET['a']) && !empty($_GET['a'])? $abc = $_GET['a'] : $abc = null;

  // If neither a search string or abc letter is set, then show all elements
  ($query == null && $abc == null)? $abc = 'Tots' : null;

  // Get all the tags and build query
  $filter = null;
  if($tags!==null) {
    $tags = explode(',', $tags);
    foreach ($tags as $tag) {
      $value = $_GET[$tag];
      $filter[$tag] = $value;
    }
  }

  // Count all the elements that match the criteria
  $totalItems = fetchData('count', $db, $query, $filter, $abc);
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
  if($totalItems!=0){
    $rows = fetchData('select', $db, $query, $filter, $abc, $limit, $offset);
  } else {
    $rows = [];
  }

  // Seting up associative array with response
  /*
    q => if user entered an string query it's also returned in the response 
    (string || null)
    abc => if user set some alphabetical filter it will be returned too 
    (string || null)
    tag => all selected tags with name of tag (as key) and value. (associtive 
    array, JSON->object || null)
    num => number of results in the resultset (int)
    pag => pagination, current page loaded (int)
    lim => pagination, maximum number of pages (int)
    res => resultset of the fetch data function, returns array of associative 
    array (JSON->object) with all the items found

    Due to the JSON encoding all the associative arrays will become objects once
    decoded in the js
  */
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
  exit(0);

  /**
   * This function recieves the sent parameters, builds the query and it counts 
   * or fetch the data matching the criteria. 
   * @param  string $action count|select
   * @param  object $db Connection object
   * @param  string $query Word to search from the front
   * @param  array[string]string $filter Contains the name and value of the
   * field to filter.
   * @param  string $abc Letter or 'Tots'
   * @param  string [$limit] Total items to show at current page instance
   * @param  string [$offset] Current number of the pagination
   * @return object resultset or single Result of the query execution.
   */
  function fetchData($action, $db, $query, $filter, $abc, $limit='', $offset='') {

    $fields = "*";
    $table = "carrerer";
    $field1 = "nom_normalitzat";

    // Prepare if count or select
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

    // Prepare tag section of the query
    $tagsSQL = '';
    if($filter!==null){
      foreach ($filter as $tag => $value) {
        if($value=='null') {
          $tagsSQL .= "AND $tag is null ";
        } else {
          $tagsSQL .= "AND CAST($tag as VARCHAR) SIMILAR TO '$_GET[$tag]' " ;
        }
      }
    }

    // More especific conditions
    $conditionSQL = "AND actiu = 'S'";

    // Case 1: query set -> search in table 
    if($query!==null && $abc==null) {
      $db->query("$selectSQL WHERE tsv @@ plainto_tsquery(:query) $conditionSQL $tagsSQL $orderSQL $limitSQL");
      $db->bind(":query", $query.'%');
    }
    // Case 2: abc selected, in that case is mandatory. If "Tots", get all table
    if($abc!==null) {
      if ($abc=='Tots') {
        $db->query("$selectSQL WHERE $field1 IS NOT NULL AND TRIM($field1) <> '' $conditionSQL $tagsSQL $orderSQL $limitSQL");
      } else {
        $db->query("$selectSQL WHERE LOWER($field1) LIKE :abc $conditionSQL $tagsSQL $orderSQL $limitSQL");
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