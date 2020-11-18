<?php

declare(strict_types=1);

namespace App;

require_once("src/Utils/debug.php");
require_once("src/View.php");

const DEFAULT_ACTION = 'list';

$action = $_GET['action'] ?? DEFAULT_ACTION;

$view = new View();

$viewParams = [];
if ($action === 'create') {
  $page = 'create';
  $viewParams['resultCrate'] = "udało się";
} else {
  $page = 'list';
  $viewParams['resultList'] = "wyświetlamy notatki";
}

$view->render($page, $viewParams);
