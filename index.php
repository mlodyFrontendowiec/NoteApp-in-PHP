<?php

declare(strict_types=1);

namespace App;

require_once("src/Utils/debug.php");
require_once("src/View.php");

const DEFAULT_ACTION = 'list';

$action = $_GET['action'] ?? DEFAULT_ACTION;

$view = new View();

$viewParams = [];

switch($action){
  case "create":
    $page = 'create';
  $created = false;

  if (!empty($_POST) ) {
    $created = true;
    $viewParams = [
    "title"=>$_POST['title'],
    "description"=> $_POST['description']
  ];
  }
  

  $viewParams['created'] = $created;
    break;
  case "show":
    $page = "show";
    $viewParams = [
    "title"=>"Moja notatka",
    "description"=> 'Opis'
  ];
    break;
  default:
    $page = 'list';
    $viewParams['resultList'] = "wyświetlamy notatki";
    break;
  

}


$view->render($page, $viewParams);
