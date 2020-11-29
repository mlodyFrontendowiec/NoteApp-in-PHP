<?php 
declare(strict_types=1);



namespace App\Controller;

use App\Exception\NotFoundException;


class NoteController extends AbstractController{

    private const PAGE_SIZE = 25 ;

    
    public function createAction():void{     
      
                      
      if ($this->request->hasPost()) { // jeżeli są dane w tablicy superglobalnej post 
      $noteData = [
        'title'=>$this->request->postParam('title'),
        'description'=>$this->request->postParam('description'),                
      ];

      $this->database->createNote($noteData);

      $this->redirect('/',['before'=>"created"]);
      
      }
    $this->view->render('create'); 
    }

    public function showAction():void{

      $noteId =(int) $this->request->getParam('id'); // rzutowanie na inta
      
      $this->view->render('show',
      ['note' => $this->getNote()]
    ); 
    }

    public function listAction():void
    {
      $pageNumber = (int) $this->request->getParam('page',1);
      $pageSize = (int) $this->request->getParam('pagesize',self::PAGE_SIZE);      
      $sortBy = $this->request->getParam('sortby','title');
      $sortOrder = $this->request->getParam('sortorder','desc');

      if(!in_array($pageSize,[1,5,10,25])){
        $pageSize = self::PAGE_SIZE;
      }
      $note = $this->database->getNotes($pageNumber, $pageSize,$sortBy,$sortOrder);
      $notes = $this->database->getCount();
      dump($notes);
            
      $this->view->render(
        'list',
        [ 'page'=>['number'=>$pageNumber,'size'=>$pageSize,'pages'=>(int) ceil($notes/$pageSize)],
          'sort' => ['by'=>$sortBy,'order'=>$sortOrder],
          'notes'=>$note,
          'before'=>$this->request->getParam('before') ?? null,
          'error'=>$this->request->getParam('error')?? null,
        ]
    ); 
    }

    public function editAction():void{

      if($this->request->isPost()){
        $noteId = (int) $this->request->postParam('id');
        $noteData = [
          'title'=>$this->request->postParam('title'),
          'description'=>$this->request->postParam('description'),                
        ];
  
        $this->database->editNote($noteId,$noteData);
        $this->redirect('/',['before' => 'edited']);
      };

      $this->view->render('edit',['note'=> $this->getNote()]);
      
    }

    public function deleteAction():void {
      if($this->request->isPost()){
        $id = (int) $this->request->postParam('id');
        $this->database->deleteNote($id);
        $this->redirect('/',["before"=>"deleted"]);

      }

      $this->view->render('delete',['note'=> $this->getNote()]);

    }

    final private function getNote(): array{

      $noteId = (int)($this->request->getParam('id'));
      if(!$noteId){
        $this->redirect('/',['error'=>"missingNoteID"]);
      }
      try{
        $note = $this->database->getNote($noteId);
      }catch(NotFoundException $e){                
        $this->redirect('/',['error'=>"noteNotFound"]);
      }
      return $note;
    
  }
}


?>