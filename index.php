 <?php 

if (!isset($_REQUEST)) { 
  return; 
} 

include("config.php");



//Получаем и декодируем уведомление 
$data = json_decode(file_get_contents('php://input')); 

//Проверяем, что находится в поле "type" 
switch ($data->type) { 
  //Если это уведомление для подтверждения адреса... 
  case 'confirmation': 
    //...отправляем строку для подтверждения 
    echo $confirmation_token; 
    break; 

//Если это уведомление о новом сообщении... 
  case 'message_new': 
        $userId = $data->object->user_id;
        $body = $data->object->body; 
        $body = mb_strtolower($body, 'UTF-8');
        // $bodyar = explode(" ", $body);
        
      
            switch ($body) {
                
                case "расписание":
                case "сегодня":
                case "завтра":
                case "понедельник":
                case "вторник":
                case "среда":
                case "четверг":
                case "пятница":
                case "суббота":
                case "воскресенье":
                    $mes = table($body);
                break;
                
                
                
                
                case "обновить": 
                    
                    $url = $data->object->attachments[0]->doc->url; 
                    
                    if(isset($url)){
                        if($data->object->attachments[0]->doc->ext == 'csv'){
                            $mes = "Обновлено"; 
                            file_put_contents("file.csv", fopen($url, 'r'));
                        }
                        else{
                            $mes = "Файл должен быть в формате .csv"; 
                        }
                    }
                    else{
                        $mes = "Пришлите файл"; 
                    }
                    
                break;

                default:     
                $mes = "Я не знаю такую команду =(\n\nВозможности бота:\nРаписание\nСегодня\nЗавтра\n[День недели]\n\nЧтобы обновить расписание -- напишите «обновить» и пришлите CSV-файл с расписанием по шаблону в одном сообщении";
                break;
            }
                

send($mes,$userId,$token);
  
//Возвращаем "ok" серверу Callback API 

echo('ok'); 

break; 

} 

// mysql_close($db);
?> 