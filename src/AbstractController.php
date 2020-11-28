<?php

declare(strict_types=1);

namespace App;
use App\Exception\ConfigurationException;

require_once("src/Exception/ConfigurationException.php");
require_once("src/Database.php");
require_once("src/View.php");

abstract class AbstractController{

    const DEFAULT_ACTION = 'list';

    private static array $configuration = []; 

    protected Request $request;

    protected Database $database;
    protected View $view;


    public static function initConfiguration(array $configuration):void{

      self::$configuration = $configuration;
    }
    
    

    public function __construct(Request $request)
    {    if(empty(self::$configuration['db'])){
      throw new ConfigurationException("Configuration Error");

    }
        $this->database = new Database(self::$configuration['db']);
        
        $this->request = $request;
        $this->view = new View();

    }
    public function run():void{  

        $action = $this->action() . 'Action';
  
        dump($action);
        if(!method_exists($this,$action)){
          $action = self::DEFAULT_ACTION . 'Action';
  
        }
        
        $this->$action(); // wywołujemy metodą zależną od parametru action np. $action = 'create'
          
      }
  
      protected function action():string{
          return $this->request->getParam('action',self::DEFAULT_ACTION); // zwraca nam to co kryje sie pod kluczem action w tablicy $get, albo domyślną akcję 
            
      }
}