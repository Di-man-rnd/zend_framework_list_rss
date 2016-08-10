<?php

require_once 'classes/DBConnector.php';
require_once 'Dealer.php';

class Primitives{
    //  ========================================================================
    //  смена кодировки
    static public function iconv($from, $to, $value){
        if($from == $to) return $value;
        if (is_array($value)) {
            $result = array();
            foreach($value as $_key => $_value){
                $result[self::iconv($from, $to, $_key)] = self::iconv($from, $to, $_value);
            }
            return $result;
        }else{
            return iconv($from, $to, $value);
        }
    }
    //  ========================================================================
    //  преобразование в транслит
    static private $_translit_alphabet = array(
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'E',
        'Ж' => 'J',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'X',
        'Ц' => 'C',
        'Ч' => 'CH',
        'Ш' => 'SH',
        'Щ' => 'SH',
        'Ъ' => '`',
        'Ы' => 'I',
        'Ь' => '`',
        'Э' => 'E',
        'Ю' => 'YU',
        'Я' => 'YA',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'j',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'x',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sh',
        'ъ' => '`',
        'ы' => 'i',
        'ь' => '`',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        '№' => '#'
    );
    static private $_delete_from_translit = array(
        ',',
        '~',
        '!',
        '@',
        '%',
        '^',
        '(',
        ')',
        '<',
        '>',
        ':',
        ';',
        '{',
        '}',
        '[',
        ']',
        '&',
        '`',
        '„',
        '‹',
        '’',
        '‘',
        '“',
        '”',
        '•',
        '›',
        '«',
        '?',
        '»',
        '°'
    );
    static public function getTranslit($text){
        if (is_string($text)){
            $result = '';
            for($i = 0; $i < mb_strlen($text, 'utf-8'); $i++){
                $letter = mb_substr($text, $i, 1, 'utf-8');
                if (array_key_exists($letter, self::$_translit_alphabet)) {
                    $result .= self::$_translit_alphabet[$letter];
                }else{
                    $result .= $letter;
                }
            }
            return str_replace(self::$_delete_from_translit, '', $result);
        }
        return false;
    }
    
    /**
     * Вырезаем ненужные символы из транслита для дальнейшего исп. в урл
     * @param string $translit
     * @return string
     */
    static public function prepareTranstilToUrl($translit){
        return str_replace(
            array(' ', '/', '#', '&', '%', '"', '<', '>'), 
            '_', 
            $translit
        );
    }


    //  ========================================================================
    //  получить урл товара
    static public function getUrl($id, $text, $is_translit = true){
        if (!$is_translit){
            $text = self::getTranslit($text);
        }
        return sprintf('/products/%d_%s.html', $id, self::prepareTranstilToUrl($text));
    }
    //  ========================================================================
    //  курл
    static public function curl($url, $post, $options){
        global $config;
        $curlopt_timeout = (isset($config->curlopt_timeout)) ? $config->curlopt_timeout : 5;

        $curl = @curl_init($url);
        @curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($curl, CURLOPT_VERBOSE, 0); 
        @curl_setopt($curl, CURLOPT_HEADER, 0);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        @curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        @curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        @curl_setopt($curl, CURLOPT_TIMEOUT, $curlopt_timeout);
        if ($config->proxy) {
            @curl_setopt($curl, CURLOPT_PROXY, $config->proxy->ip);
            @curl_setopt($curl, CURLOPT_PROXYPORT, $config->proxy->port);
            @curl_setopt($curl, CURLOPT_PROXYUSERPWD, $config->proxy->user.':'.$config->proxy->pass);
        }
        if (!is_null($post)){
            @curl_setopt($curl, CURLOPT_POST, 1);
            if (is_array($post)){
                @curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
            }else{
                @curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            }
        }
        foreach($options as $key => $value){
            @curl_setopt($curl, $key, $value);
        }
        $page = @curl_exec($curl);
        @curl_close($curl);
        return $page;
    }
    //  ========================================================================
    //  IP
    static public function getIp(){
        return $_SERVER['HTTP_X_REAL_IP'];
    }
    //  ========================================================================
    //  ucfirst for utf-8
    static public function ucfirst($string){
        return mb_strtoupper(mb_substr($string, 0, 1)).mb_strtolower(mb_substr($string, 1));
    }
    //  ========================================================================
   
    static public function getTemplate($type, $filename){
        global $config;
        $full_filename = sprintf('%sapplication/template/%s/%s', $config->path, $type, $filename);
        if (!file_exists($full_filename)) {
            throw Exception(sprintf('Файл с шаблоном "%s" не найден', $full_filename));
        }
        return file_get_contents(
            sprintf('%sapplication/template/%s/%s', $config->path, $type, $filename)
        );
    }
    
    static public function renderCaption($labels, $field_values, $string_format){
        $caption = '';
        foreach($labels as $label => $field_name){
            if (!empty($field_values[$field_name])) {
                $caption .= sprintf($string_format, $label, $field_values[$field_name]);
            }
        }
        return $caption;
    }
    
    static public function array_copy($item, $field_names){
        $result = array();
        foreach($field_names as $field_name){
            if (!empty($item[$field_name])) $result[$field_name] = $item[$field_name];
        }
        return $result;
    }
    
    static public function setCookie($name, $value, $expire = 2592000){
        setcookie($name, $value, time() + $expire, '/', '.cro.ru');
    }
    
    static public function removeCookie($name){
        unset($_COOKIE[$name]);
        setcookie($name, '', time() - 3600, '/', '.cro.ru');
    }
    
    static public function getGroupImageUrl($code){
        global $config;
        return sprintf($config->cdn_mask->group, $code);
    }
    
    static public function getCarouselImageUrl($filename){
        global $config;
        return sprintf($config->cdn_mask->carousel, $filename);
    }
    
    static public function getSeoblockImageUrl($filename){
        global $config;
        return sprintf($config->cdn_mask->seoblock, $filename);
    }
    
    static public function getProductImageUrl($code, $props=array('type' => 'small', 'filename' => 'ko_small.jpg')){
        global $config;
        return sprintf($config->cdn_mask->product, $code, $props['filename']);
    }
    
    static public function getArticleImageUrl($id, $filename){
        global $config;
        return sprintf($config->cdn_mask->article, $id, $filename);
    }
    
    static public function getAvailableCaptions($isAvailable=null){
        $captions = array(
            'available' => 'В наличии',
            'not_available' => 'Под заказ'
        );
        if (is_bool($isAvailable)) {
            return $captions[sprintf('%savailable', $isAvailable ? '' : 'not_')];
        }
        return $captions;
    }
    
    static public function getForTheOrderCaption(){
        return 'Срок поставки до 30 дней';
    }
    
    static public function getAllowShippingCaption(){
        return 'через 2-14 дней';
    }

    static public function isOnlinePayment($payment){
        return in_array($payment, array('ALFA CLICK', 'MOBILE', 'ONLINE',
            'TERMINAL', 'WEB MONEY', 'YANDEX MONEY', 'MASTERPASS', 'QIWI'));
    }
    
    static public function inArrayKeyContains($key, $array, &$returned){
        foreach(array_keys($array) as $_key){
            if (mb_stripos($key, $_key) !== false || mb_stripos($_key, $key) !== false){
                if (isset($returned)) {
                    $returned = $array[$_key];
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Возращает статический файл *.js или *.css в нужном формате (в зависимости
     * от режима разработки (debug или prodction)
     * в debug-режиме подключаются несжатые медиа-файлы, иначе сжатые (*.min.*)
     * Если подключаемый файл .min.js|css изначально, нвзвание остатся тем же
     *
     * @param $script_name - скрипт (вместе с путем: "/scripts/system/moment.js")
     * @return string - исправленное имя скрипта
     */
    static public function prepareMediaName($script_name) {
        global $main_config;
        $postfix = (!$main_config->debug) ? '.min' : '';
        $matches = [];
        if (preg_match_all("/(.*?)(\.min)?(\.js|\.css)+$/i", $script_name, $matches)) {
            return ($matches[2][0] == '.min') ? $script_name : $matches[1][0] . $postfix . $matches[3][0];
        } else throw new Exception('$script_name == [' . $script_name . '] must ends with .js or .css');
    }

    /**
     * Возвращает подробную информацию о населенном пункте
     *
     * @param $cookieJson json из кук (содержит информацию о населенном пункте)
     * @return array allowShipping и locality
     */
    static public function prepareLocation($cookieJson){
        $location = json_decode($cookieJson, true);
        return array(
            'allowShipping' => $location['allowShipping'],
            'locality' => array(
                'id' => $location['localityId'],
                'kladrId' => $location['localityKladrId'],
                'name' => $location['localityName'],
                'parent' => $location['localityParentName']
            )
        );
    }

}
?>
