<?php
class GuestbookController extends Zend_Controller_Action
{
    protected $guestbookMapper;
	
	public function init()
    {
        $this->initScripts();
    }
    
    protected function initScripts()
    {
    	$this->view->headScript()->appendFile('/js/guestbook/index.js');
        $this->view->headLink()->appendStylesheet('/css/guestbook/index.css');
    }
    
    public function indexAction()
    {
        
    }

    public function indexJsonAction()
    {
        $this->respondJson(
        	$this->getGuestbookMapper()->getPaginatorJsonGrid(
        		array(), 
        		$this->_getParam('page'), 
        		$this->getConfigDefaultPageSize()
        ));
    }

    public function addAction()
    {
        $this->edit(0);
    }
    
    public function editAction()
    {
        $this->edit($this->_request->getParam('id'));
    }
    
    public function edit($id = 0)
    {
        if($id > 0){
        	$guestbook = $this->getGuestbookMapper()->find($id);
        } else {
        	$guestbook = $this->getGuestbookMapper()->getNewGuestbook();
        }
        $guestbookForm = $this->getGuestbookForm();
        if ($this->getRequest()->isPost()) {
            if ($guestbookForm->isValid($this->getRequest()->getPost())) {
                $guestbook->setOptions($guestbookForm->getValues());
                $this->getGuestbookMapper()->beginTransaction();
				try {
                	$this->getGuestbookMapper()->save($guestbook);
                	$this->getGuestbookMapper()->commit();
                	return $this->_helper->redirector('index');
				} catch (Exception $e) {
					$this->getGuestbookMapper()->rollBack();
    				echo $e->getMessage();					
				}
			}
        } else {
            $guestbookForm->populate($guestbook->toArray());
            $guestbookForm->setAction($this->view->url());
        }
 
        $this->view->guestbookForm = $guestbookForm;
    }
    
    protected function getGuestbookForm()
    {
    	return new Application_Form_Guestbook();
    }
    
    protected function getGuestbookMapper()
    {
        if(null == $this->guestbookMapper){
    		return new Application_Model_GuestbookMapper();
        } else {
        	return $this->guestbookMapper;
        }
    }
    
    protected function respondJson($response)
    {
        $this->_helper->json($response, true);
    }
    
    protected function getConfig()
    {
    	return Zend_Registry::get('config');
    }
    
    protected function getConfigDefaultPageSize()
    {
    	return $this->getConfig()->paginator->page_size;
    }
}