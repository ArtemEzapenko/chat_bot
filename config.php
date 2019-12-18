<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Moscow');

$confirmation_token = '9c52a97a';
$token = '8bc529c3e1bdbf3063b5a8e70d29a6b95edec062437c32ce5b53200e1d1b161a60a51f5649d8c97385856';

$mes = '';


//ОТПРАВИТЬ СООБЩЕНИЕ
function send($mes,$user_id,$token){
    $request_params = array( 
      "message" => " {$mes}", 
      "user_id" => $user_id, 
      "access_token" => $token, 
      "v" => '5.0' 
    ); 
    $get_params = http_build_query($request_params,'','&amp;'); 
    $get_params = str_replace('amp;', '', $get_params);
    
    $url = "https://api.vk.com/method/messages.send?". $get_params;
    file_get_contents($url); 
}


function table($day=null){
    $daycount = 0;
    $mes = '';
    $days = [
     'понедельник'=>0,
     'вторник'=>1,
     'среда'=>2,
     'четверг'=>3,
     'пятница'=>4,
     'суббота'=>5,
     'воскресенье'=>6,
     ];

    if($day=='расписание'){
        $day = null;
    }
    elseif($day=='сегодня'){
        $day = date("w");
    }
    elseif($day=='завтра'){
        $day = date("w");
        if($day==6){
            $day = 0;
        } else{
            $day++;
        }
    }
    else{
        $day = $days[$day];
    }
    
    if (($handle = fopen("file.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $daycount++;
            if($day!==null){
                
                if($daycount-1 != $day){
                    continue;
                }
                
            }
            
            
        $num = count($data);
        
        //УДАЛЕНИЕ С КОНЦА ПУСТЫХ ЗНАЧЕНИЙ
            $data = array_reverse($data);
            for ($i=0; $i < $num; $i++) {
                if(empty($data[$i])){
                    array_shift($data);
                    $num--;
                    $i--;
                }
                else{
                    break;
                }
            }
            $data = array_reverse($data);
            if(empty($data[$num-1])){
                $num--;
            }
            $tmp = '';
        //   ЦИКЛ С ДАННЫМИ
            for ($i=1; $i < $num; $i++) {
                $tmp .= $i.' пара: ';
                if(!empty($data[$i])){
                   $tmp .= $data[$i]."\n";
                }
                else{
                    $tmp .= "форточка\n";
                }
                
                if($i==$num-1){
                    $tmp .="\r\n";
                }
            }
            $mes .= "✅ ".$data[0]."\n";
            // $mes .= $tmp;
            
            if(empty($tmp)){
                $mes .= "Пар нет";
            }
            else{
                $mes .= $tmp;
            }
            
        }
    fclose($handle);
    }
    else{
        $mes = 'Ошибка открытия файла';
    }
    
    return($mes);
    
}

                    

?>