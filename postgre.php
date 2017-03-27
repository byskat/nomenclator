<?php

  require_once("resources/php/postgre.class.php");

  $db = new Db("nomenclator.ini");

  // Find out how many items are in the table
  $db->query("SELECT COUNT(*) FROM etrs89.eixos_viaris_unic WHERE nom_tip_comple IS NOT NULL AND TRIM(nom_tip_comple) <> ''");
  $total = $db->single();
  $total = $total['count'];

  // How many items to list per page
  $limit = 20;

  // How many pages will there be
  $pages = ceil($total / $limit);

  // What page are we currently on?
  $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    'options' => array(
      'default'  => 1,
      'min_range' => 1,
    ),
  )));

  // Calculate the offset for the query
  $offset = ($page - 1) * $limit;

  // Some information to display to the user
  $start = $offset + 1;
  $end = min(($offset + $limit), $total);

  // The "back" link
  $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

  // The "forward" link
  $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

  // Display the paging information
  echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';

  // Prepare the paged query
  $db->query("SELECT * FROM etrs89.eixos_viaris_unic WHERE nom_tip_comple IS NOT NULL AND TRIM(nom_tip_comple) <> '' ORDER BY nom_tip_comple LIMIT :limit OFFSET :offset");

  // Bind the query params
  $db->bind(':limit', $limit);
  $db->bind(':offset', $offset);
  $db->execute();

  // Do we have any results?
  if ($db->rowCount() > 0) {
    // Define how we want to fetch the results
    $res = $db->resultset();

    // Display the results
    foreach ($res as $row) {
      echo '<p>', $row['nom_tip_comple'], '</p>';
    }

  } else {
    echo '<p>No results could be displayed.</p>';
 }

var_dump($db->getLastError());