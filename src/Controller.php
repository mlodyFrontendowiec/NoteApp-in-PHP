<?php 
declare(strict_types=1);

namespace App;

require_once("src/Exception/ConfigurationException.php");

use App\Request;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;

require_once("src/Database.php");
require_once("src/View.php");

//F3EDnz0Dm8oCnDQa - hasło do bazy danych
class Controller{

    private static array $configuration = []; 

    private Request $request;

    private Database $database;
    private View $view;


    public static function initConfiguration(array $configuration):void{

      self::$configuration = $configuration;
    }
    
    const DEFAULT_ACTION = 'list';

    public function __construct(Request $request)
    {    if(empty(self::$configuration['db'])){
      throw new ConfigurationException("Configuration Error");

    }
        $this->database = new Database(self::$configuration['db']);
        
        $this->request = $request;
        $this->view = new View();

    }



    public function run():void{  
      $viewParams =[];       
        
        switch($this->action()){
            case "create":
              $page = "create";          
                      
              if ($this->request->hasPost()) { // jeżeli są dane w tablicy superglobalnej post 

              $noteData = [
                'title'=>$this->request->postParam('title'),
                'description'=>$this->request->postParam('description'),                
              ];

              $this->database->createNote($noteData);

              header("Location: /?before=created");
              exit;
            }
            
              break;
            case "show":
              $page = "show";


              $noteId =(int) $this->request->getParam('id'); // rzutowanie na inta

              dump($noteId);

              if(!$noteId){
                header("Location: /?error=missingNoteId");
                exit;
              }

              try{
                $note = $this->database->getNote($noteId);
              }catch(NotFoundException $e){                
                header("Location: /?error=noteNotFound");
                exit;
              }

              $viewParams = [
              "note"=>$note,
              ];
              break;
            default: // domyślnie ta strona się wyświetli
              $page = 'list';

             
              $viewParams = [
                'notes'=> $this->database->getNotes(),
                'before'=>$this->request->getParam('before') ?? null,
                'error'=>$this->request->getParam('error')?? null,
              ];

              
              break;
            
          
          }
          
          $this->view->render($page,$viewParams); // wywołujemy na obiekcie powstałym z klasy View, metodę redner i przekazujemy rodzaj strony i tablicę z danymi notatki
          
    
    }

    private function action():string{
        return $this->request->getParam('action',self::DEFAULT_ACTION); // zwraca nam to co kryje sie pod kluczem action w tablicy $get, albo domyślną akcję 
          
    }
    
    
}


?>