<?php

function getLegalFiles($path) {
  $phpfiles = glob($path . "*.pdf");
  usort($phpfiles, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));

  foreach($phpfiles as $phpfiles) {
    $phpfiles = utf8_encode($phpfiles);
    echo '<a class="btn btn-default" target="_blank" href="'.$phpfiles.'"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> '.basename($phpfiles,".pdf").'</a>';
  }
}