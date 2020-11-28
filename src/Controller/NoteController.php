<?php 
declare(strict_types=1);



namespace App\Controller;

use App\Exception\NotFoundException;


class NoteController extends AbstractController{

    
    public function createAction()
    {     
      
                      
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

    public function showAction()
    {

      $noteId =(int) $this->request->getParam('id'); // rzutowanie na inta



      if(!$noteId){
        $this->redirect('/',['error'=>"missingNoteID"]);
      }

      try{
        $note = $this->database->getNote($noteId);
      }catch(NotFoundException $e){                
        $this->redirect('/',['error'=>"noteNotFound"]);
      }

      $viewParams = [
      "note"=>$note,
      ];
      $this->view->render('show',
      ['note' =>$note]
    ); 
    }

    public function listAction()
    {
            
      $this->view->render(
        'list',
        [
          'notes'=> $this->database->getNotes(),
          'before'=>$this->request->getParam('before') ?? null,
          'error'=>$this->request->getParam('error')?? null,
        ]
    ); 
    }

    public function editAction(){

      if($this->request->isPost()){
        $noteId = (int) $this->request->postParam('id');
        $noteData = [
          'title'=>$this->request->postParam('title'),
          'description'=>$this->request->postParam('description'),                
        ];
  
        $this->database->editNote($noteId,$noteData);
        $this->redirect('/',['before' => 'edited']);
      }

      $noteId = (int)($this->request->getParam('id'));
      if(!$noteId){
        $this->redirect('/',['error'=>"missingNoteID"]);
      }
      try{
        $note = $this->database->getNote($noteId);
      }catch(NotFoundException $e){                
        $this->redirect('/',['error'=>"noteNotFound"]);
      }
      $this->view->render('edit',['note'=>$note]);
      
    }

    
}


?>