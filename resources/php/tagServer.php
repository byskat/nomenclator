<?php

	require_once("postgre.class.php");

  $db = new Db("web_umat_nomenclator.ini");

	$tagList = fetchTags($db);
  $tags = [];

  foreach ($tagList as $item) {
    $splitItem = explode(" ",$item['nom_tag']);
    if($splitItem[0]=='.'){
      $item['nom_tag'] = ucfirst(strtolower($splitItem[1]));
    } else {
      $item['nom_tag'] = $splitItem[0];
    }

    array_push($tags, $item);
  }

  echo json_encode($tags);

	function fetchTags($db) {

    $db->query("
        SELECT DISTINCT ON (codi_tipus_via)
               codi_tipus_via, nom_composat AS nom_tag
        FROM   carrerer_1
        ORDER  BY codi_tipus_via, nom_tag DESC, codi_tipus_via;");
    
    return $db->resultSet();
  }