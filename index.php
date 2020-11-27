<?php

declare(strict_types=1);

namespace App;



use App\Exception\AppException;
use App\Exception\ConfigurationException;
use Throwable;

require_once("src/Utils/debug.php");
require_once("src/Controller.php");
require_once("src/Exception/AppException.php");



$configuration = require_once("config/config.php"); // tablica zwracana z tego pliku zostanie przypisana do naszej zmiennej


$request = [
  'get'=>$_GET,
  'post'=>$_POST
];

try{
Controller::initConfiguration($configuration);

(new Controller($request))->run();

}catch(ConfigurationException $e){
  dump($e);
  echo "<h1>wystąpił błąd w aplikacji</h1>";
  echo "Proszę skontaktować się z administratorem pawelkruszelnicki@interia.pl";

}
catch(AppException $e){
  echo "<h1>wystąpił błąd w aplikacji</h1>";
  echo '<h3>' . $e->getMessage().'</h3>';
}
catch(Throwable $e){
echo "<h1>wystąpił błąd w aplikacji</h1>";
}

// $controller = new Controller($request);
// $controller->run(); to jest to samo co poniżej 

// przekazanie konfiguracji przez metodę statyczną




