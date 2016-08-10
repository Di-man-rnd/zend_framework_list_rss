<?php
/**
 * Description: Парсер html страницы
 * на вход      получает url
 * на выходе    получаем array(tag => count)
 * задача       вернуть кол-во тегов
 * min php5.4
 */
class Parse{
    private $array;
    private $url;
    private $form = <<<EOD
        <form method='post' >
        <label>URL(http://): 
		<input 
		name='url' 
		required
		placeholder='http://rambler.ru'/>
	</label>
        <input type='submit'/>
        </form>
EOD;
    

    public function printForm(){
        return $this->form;
    }

    public function getUrl($param) {
        return $this->url;
    }

    public function setUrl(){
        $this->url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if(empty($this->url))
            return false;
        return true;
    }

    public function perse(){
        $html = $this->getContent();
        
        if(empty($html)){
            return false;
        }

        $re = "/(<[a-z0-9]+)/m";
        preg_match_all($re, $html, $matches);

        $rez = [];
        foreach($matches[0] as $value){
            $value = substr($value, 1);
            isset($rez[$value]) ? $rez[$value]++ : $rez[$value]=1;
        }
        $this->array = $rez;
        return true;
    }

    public function getContent(){
        if($this->isCurl()){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);                    
        }else{
            $result = file_get_contents($this->url);            
        }
	if(!empty($result)) return $result;
        return null;
    }

    public function isCurl(){
        if(function_exists('curl_init')) return true;
        return false;
    }

    public function get() {
        return $this->array;
    }
}
