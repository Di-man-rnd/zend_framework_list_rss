<?php
class Class_Rss {
    
    public $link;
    public $channels;
    public $hasLikeList;
    public $error = false;


    /**
     * принимаем ссылки на rss ресурс
     * 
     * @param string|array $link
     */
    public function __construct($link) {
        if(is_array($link))
        {
            $this->link = $link;            
        }else{
            $this->link = array($link); 
        } 
        // вносим в элемент xml структуру 
        foreach ($this->link as $link) {
            $this->channels[] = new Zend_Feed_Rss($link);            
        }
        //Получаем все записи с лайками
        try {
            self::GetAllFromDB();
        } catch (Exception $exc) {
            $this->error = true;        
        }
    }  
    
    /**
     * get all row from DB
     * @return void  
     */
    public function GetAllFromDB(){
        $table = new Application_Model_CRUD;
        $select = $table->select();     
        $rows = $table->fetchAll($select);
        foreach ($rows as $row) {
            $this->hasLikeList[$row->name] =  $row->count;
        }
    }
    
    /**
     * перебор коллекций
     * 
     * @param array $params
     * @return array
     */
    public function getItems() {
        $allData = array();
        // перебираем массив с xml структурами 
        foreach ($this->channels as $channel) {
            // $res получит массив с элементами новостей
            $res =  self::item($channel);  
            // объединям объединяем коллекции новостей с разных источников
            $allData = array_merge($res, $allData);
        }
        return($allData);   
    }
    
    /**
     * перебор конкретной коллекции
     * 
     * @param Zend_Feed_Rss $channel
     * @return array
     */
    public function item($channel) { 
        // перебираем xml структуру, берем item-ы
        foreach ($channel as $item) { 
            
            $date = new DateTime($item->pubDate());
            $result[] = array(
                "TITLE" => $item->title(),
                "LINK" => $item->link(),
                "DESC" => $item->description(),
                "RESOURS" => $channel->title(),                
                "COUNT" => self::Count( $item->title() ),                
                "DISPLAY_DATE"=> date_format(new DateTime($item->pubDate()), 'd-m-Y H:i:s'),
                "DATE" => mktime(
                    date_format($date,'H'),
                    date_format($date,'i'),
                    date_format($date,'s'),
                    date_format($date,'n'),
                    date_format($date,'j'),
                    date_format($date,'Y')
                )             
            );                    
        }
        return $result;
    }
    
    /**
     * устанавливаем счетчик для каждой новости
     * ЕСЛИ новости в базе такой нет кол-во лайков =0 
     * ИНАЧЕ  кол-во лайков ставим из БД
     * @param string $name
     * @return int
     */
    public function Count($name){
        $nameFltr = self::Filter( $name );
        if( isset( $this->hasLikeList[$nameFltr] ) && !empty( $this->hasLikeList[$nameFltr] ))
        {
            return $this->hasLikeList[$nameFltr];
        }
        return 0;
    }
        
    /**
     * Фильтруем название (с удалением пробелов)
     * 
     * @param string $name
     * @return string
     */
    static public function Filter($name) {            
        $filter = new Zend_Filter_Alnum();      
        return $filter->filter($name); 
    }
    
    public function getError() {
        return $this->error;
    }
}