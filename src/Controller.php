<?php 
declare(strict_types=1);

namespace App;
require_once("src/Databese.php");
require_once("src/View.php");

//F3EDnz0Dm8oCnDQa - hasło do bazy danych
class Controller{

    private static array $configuration = []; 

    private array $request;
    private View $view;

    public static function initConfiguration(array $configuration):void{

      self::$configuration = $configuration;
    }
    
    const DEFAULT_ACTION = 'list';

    public function __construct(array $request)
    {    
        $db = new Database(self::$configuration['db']);
        
        $this->request = $request;
        $this->view = new View();

    }


    public function run():void{  

        $viewParams = [];
        
        switch($this->action()){
            case "create":
            $page = "create";
            $created = false;

            $data = $this->getRequestPost(); // zwraca nam zawartość formularza, $data to jest inna zmienna niż ta w this->action()
          
            if (!empty($data) ) { // jeżeli są dane w tablicy superglobalnej post 
              $created = true;
              $viewParams = [
              "title"=>$data['title'], // ustalamy co się kryje w tablicy asocjacyjne superglobalnej na title
              "description"=> $data['description'] // ustalamy co się kryje w tablicy asocjacyjne superglobalnej na description
            ];
            }
            
          
            $viewParams['created'] = $created; // ustawiamy, że notatka została utworzona
              break;
            case "show":
              $page = "show";
              $viewParams = [
              "title"=>"Moja notatka",
              "description"=> 'Opis'
            ];
              break;
            default: // domyślnie ta strona się wyświetli
              $page = 'list';
              $viewParams['resultList'] = "wyświetlamy notatki";
              break;
            
          
          }
          
          $this->view->render($page,$viewParams); // wywołujemy na obiekcie powstałym z klasy View, metodę redner i przekazujemy rodzaj strony i tablicę z danymi notatki
          
    
    }

    private function action():string{
        $data = $this->getRequestGet(); // zwraca nam tablicę superglobalną GET
        return $data['action'] ?? self::DEFAULT_ACTION; // określa nam co kryje się pod action

        
    }
    private function getRequestGet():array{


        return $this->request['get'] ?? []; 
    }

    private function getRequestPost():array{


        return $this->request['post'] ?? []; // zwraca na zawartość tablicy superglobalnej POST
    }

    
}


?>