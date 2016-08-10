<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        require_once APPLICATION_PATH ."/../library/Class/Class_Rss.php";   
        require_once APPLICATION_PATH ."/../library/Class/Logger.php";   
        require_once APPLICATION_PATH ."/models/CRUD.php";
    }

    /**
     * получаем список новостей, выводим
     */
    public function indexAction()
    {
        $link = array(
            "https://news.yandex.ru/software.rss",
            "https://news.yandex.ru/auto.rss"
        );
        $resourse = new Class_Rss($link);
        $items = $resourse->getItems();
        $this->view->list = $items;        
        $this->view->notDb = $resourse->getError();        
    }

    /**
     * Добавляем лайк
     */
    public function addAction()
    {
        if(
            $this->getRequest()->isPost() &&
            $this->_hasParam("name")      &&
            $this->_hasParam("count")     &&
            $this->_getParam("count")     < 10    
        ){
            $COUNT = $this->_getParam("count");
            $NAME  = $this->_getParam("name");
                        
            if ( !is_numeric($COUNT) ){
                $this->_redirect("/");             
                return;
            } 
            
            $tab = new Application_Model_CRUD;
            try {
                $tab->auto($NAME, $COUNT);           
            } catch (Exception $exc) {                
                $this->_redirect("error/"); 
            }

        }
        $this->_redirect("/"); 
    }

}





