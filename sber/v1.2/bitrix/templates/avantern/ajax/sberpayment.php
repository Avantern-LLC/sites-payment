<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?

Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');

$el = new CIBlockElement;

$arResult = Array();
$company = trim($_REQUEST["company"]);
$service = trim($_REQUEST["service"]);
$name = trim($_REQUEST["name"]);
$num = trim($_REQUEST["dogovor"]);
$email = trim($_REQUEST["email"]);
$rub = intval(trim($_REQUEST["rub"]));
$kop = intval(trim($_REQUEST["kop"]));
    
$sum = $rub*100+$kop;
$description = 'Оплата услуг по договору '.$num.'. Услуга: '.$service.'. Плательщик: '.$name.'. E-mail: '.$email;
  
$cib_id="7"; //задаем ИД информационного блока
//извлекаем логин и пароль из файлf (!важно до символа EOF, т.к. Сбер не игнорирует EOF!)
$sber_user=file_get_contents("/home/bitrix/.metadata/sber", false, null, 0, 15); 
$sber_pass=file_get_contents("/home/bitrix/.metadata/sber", false, null, 16, 8); 
$pay_host = 'https://3dsec.sberbank.ru'; //платежный шлюз (для тестовой и боевой установки они разные!)
$returnUrl = 'https://pay.avantern.ru/sberpay/success'; //url, на который переадресовываем клиента после успешной оплаты
$failUrl = 'https://avantern.ru'; //url, на который перенаправляем клиента при неуспешной оплате
    
$orderId = time(); //формируем ид заказа в системе сайта
      
$q_Url = $pay_host.'/payment/rest/register.do?';
$q_Data = array(
    'userName'=>$sber_user,
    'password'=>$sber_pass,
    'orderNumber'=>$orderId,
    'amount'=>$sum,
    'returnUrl'=>$returnUrl,
    'failUrl'=>$failUrl,
    'email'=>$email
);
    
$result = file_get_contents($q_Url.http_build_query($q_Data), false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($q_Data)
        )
    )
));
    
$result = json_decode($result);
    
$pay_id = $result->orderId;
$pay_url = $result->formUrl;
    
header('Refresh: 1; url="'.$pay_url.'"');

$PROP = array();
//ФИО
$PROP["FIO"]=$name;
//Услуга
$PROP["SERVICE"]=$service;
//Номер договора
$PROP["DOGOVOR"]=$num;
//Почта
$PROP["EMAIL"]=$email;
//Сумма
$PROP["SUM"]=$sum/100;
//Номер заказа
$PROP["ORDER_ID"]=$orderId;
//Статус платежа
$PROP["STATUS"]=$status;
//Детали платежа
$PROP["STAT_DESC"]=$adv_status;
//UUID принтера
$PROP["UID_NUMBER"]=$uuid_printer;
//ИД платежа
$PROP["PAY_ID"]=$pay_id;
//IP клиента
$PROP["CLIENT_IP"]="";
    
$arLoadProductArray = Array(
    "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
    "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
    "IBLOCK_ID"      => $cib_id,
    "PROPERTY_VALUES"=> $PROP,
    "NAME"           => $description,
    "ACTIVE"         => "N"            //пока не активен
);
    
    
$PROD_ID = $el->Add($arLoadProductArray);    

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>