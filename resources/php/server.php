<?php

require_once("custom.class.php");

$db = new Db();

isset($_POST['t'])? $tags = $db -> quote($_POST['t']) : $tags = null;
isset($_POST['q'])? $query = $db -> quote($_POST['q']) : $query = null;
isset($_POST['a'])? $abc = $db -> quote($_POST['a']) : $abc = null;

if($tags===null && $query===null && $abc===null) {
  $rows = $db -> select("SELECT * FROM `carrers` WHERE `nom` LIKE 'a%'");
} 
if($tags!==null && $query===null) {
  $rows = $db -> select("SELECT * FROM `carrers` WHERE `nom` LIKE %".$tags."% OR `via` LIKE %".$tags."% OR concat(`via` , ' ', `nom`) LIKE %".$tags."%");
}

var_dump($rows);