<?php 
declare(strict_types=1);

namespace App;

require_once("src/Exception/ConfigurationException.php");

use App\Exception\ConfigurationException;

require_once("src/Database.php");
require_once("src/View.php");

//F3EDnz0Dm8oCnDQa - hasło do bazy danych
class Controller{

    private static array $configuration = []; 

    private Database $database;
    private array $request;
    private View $view;


    public static function initConfiguration(array $configuration):void{

      self::$configuration = $configuration;
    }
    
    const DEFAULT_ACTION = 'list';

    public function __construct(array $request)
    {    if(empty(self::$configuration['db'])){
      throw new ConfigurationException("Configuration Error");

    }
        $this->database = new Database(self::$configuration['db']);
        
        $this->request = $request;
        $this->view = new View();

    }



    public function run():void{  

        $viewParams = [];
        
        switch($this->action()){
            case "create":
            $page = "create";
          
            

            $data = $this->getRequestPost(); // zwraca nam zawartość formularza, $data to jest inna zmienna niż ta w this->action()
          
            if (!empty($data) ) { // jeżeli są dane w tablicy superglobalnej post 

              $noteData = [
                'title'=>$data['title'],
                'description'=>$data['description'],                
              ];

              $this->database->createNote($noteData);

              header("Location: /?before=created");
            }
            
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

              $data = $this->getRequestGet();
              dump($data);
              $viewParams['before'] = $data['before']?? null;
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