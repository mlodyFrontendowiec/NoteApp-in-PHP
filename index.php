<?php

declare(strict_types=1);

namespace App;



require_once("src/Utils/debug.php");
require_once("src/Controller.php");

$configuration = require_once("config/config.php"); // tablica zwracana z tego pliku zostanie przypisana do naszej zmiennej


$request = [
  'get'=>$_GET,
  'post'=>$_POST
];


// $controller = new Controller($request);
// $controller->run(); to jest to samo co poniżej 

// przekazanie konfiguracji przez metodę statyczną
Controller::initConfiguration($configuration);

(new Controller($request))->run();



