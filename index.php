<?php

declare(strict_types=1);

spl_autoload_register(function(string $classNamespace){
  

  $path = str_replace(['\\','App/'],['/',''],$classNamespace);
  $path = "src/$path.php";

  require_once($path);


});





require_once("src/Utils/debug.php");
$configuration = require_once("config/config.php");


use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Controller\AbstractController;
use App\Controller\NoteController;



$request = new Request($_GET,$_POST,$_SERVER);

try{
AbstractController::initConfiguration($configuration);

(new NoteController($request))->run();

}catch(ConfigurationException $e){
  dump($e);
  echo "<h1>wystąpił błąd w aplikacji</h1>";
  echo "Proszę skontaktować się z administratorem pawelkruszelnicki@interia.pl";

}
catch(AppException $e){
  echo "<h1>wystąpił błąd w aplikacji</h1>";
  echo '<h3>' . $e->getMessage().'</h3>';
}
catch(\Throwable $e){
echo "<h1>wystąpił błąd w aplikacji</h1>";
}

// $controller = new Controller($request);
// $controller->run(); to jest to samo co poniżej 

// przekazanie konfiguracji przez metodę statyczną




