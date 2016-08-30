<?php

/**
 * Description of UserController
 *
 * @author ppd
 */
class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $users = $em->getModel('gb:User')->selectCatigorizedUsers();
        echo $this->render('User/index.html.twig', ['users' => $users]);
    }

    public function addAction()
    {
        $em = $this->getEntityManager();
        $categories = $em->getModel('gb:Cat')->findAll();
        echo $this->render('User/add.html.twig', ['categories' => $categories]);
    }
    
    public function createAction()
    {
        $em = $this->getEntityManager();
        $user = $em->getModel('gb:User');
        $what = [
            'user_id' => $user->calculateNextID(),
            'UserName' => "\"".$this->request['username']."\"",
            'Category' => $this->request['category'],
            'Email' => "\"".$this->request['email']."\"",
            'Homepage' => "\"".$this->request['homepage']."\"",
            'MessageText' => "\"".$this->request['text']."\"",
            'MessageDate' => date('"Y-m-d H:i:s"'),
        ];
        $user->insert($what);
        $this->redirect('/gb/user/index');
    }
    
    public function editAction($id)
    {
        
    }
    
    public function deleteAction($id)
    {
        
    }
    
    public function promoteAction($id)
    {
        
    }
}
