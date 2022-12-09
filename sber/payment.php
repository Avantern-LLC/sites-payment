<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
//LocalRedirect("/success/");
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
$el = new CIBlockElement;
$cib_id="6"; //задаем ИД информационного блока
$ps_key=file_get_contents('/home/bitrix/.metadata/payler_key', false, null, 0); //извлекаем в строку все содержимое файла с ключем платежной системы
$sber_user=file_get_contents('/home/bitrix/.metadata/sber_user', false, null, 0);
$sber_pass=file_get_contents('/home/bitrix/.metadata/sber_password', false, null, 0);
$p_host = 'https://3dsec.sberbank.ru';
$returnUrl = 'https://pay.avantern.ru';

/*
$arResult = Array();
$company = trim($_REQUEST["company"]);
$service = trim($_REQUEST["service"]);
$name = trim($_REQUEST["name"]);
$num = trim($_REQUEST["dogovor"]);
$email = trim($_REQUEST["email"]);
$phone = trim($_REQUEST["phone"]);
$rub = intval(trim($_REQUEST["rub"]));
$kop = intval(trim($_REQUEST["kop"]));
*/

$service = "За мониторинг";
$name = "Иван Иванов";
$num = "T-1234";
$email = "i.ivanov@noemail";
$rub = "10";
$kop = "00";
$orderId = "test-0812222123";

$sum = $rub*100+$kop;
$description = 'Оплата услуг по договору '.$num.'. Услуга: '.$service.'. Плательщик: '.$name.'. E-mail: '.$email;
  
$sUrl = 'https://3dsec.sberbank.ru/payment/rest/register.do';
$sData = http_build_query(array(
    'userName' => $sber_user,
    'password' => $sber_pass,
    'orderNumber' => $orderId,
    'amount' => $sum,
    'returnUrl' => $returnUrl,
    'email' => $email
    )
);

$result = file_get_contents($sUrl.http_build_query($sData), false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($sData)
        )
    )
));

$result = json_decode($result);

/*
$qUrl = 'https://secure.payler.com/gapi/StartSession?';
$qData = http_build_query(array(
    'key' => $ps_key, 
	'type' =>1,
	'order_id'=>$orderId,
	'amount'=>$sum,
	'product'=>$description,
	'userdata'=>$description,
	'email'=>$email,
	'total'=>1,
	'lang'=>'ru')
);

$result = file_get_contents($qUrl.http_build_query($qData), false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($qData)
        )
    )));
*/
    /*
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
    ));
$result = curl_exec($curl);
curl_close($curl);

$result = json_decode($result);
*/
// echo "<pre>"; print_r($result); echo "</pre>";
// $res = json_decode($res);
//	print_r($result->session_id);

$p_orderId = $result->orderId;
$pf_url = $result->formUrl;

//$session_id=$result->session_id;

//$url = 'https://secure.payler.com/gapi/Pay?session_id='.$session_id;

header('Refresh: 1; url="'.$pf_url.'"');

//  echo "Через 5 секунд вы будите переведены на платежный шлюз банка))";
//	LocalRedirect($url);

$sUrl = 'https://3dsec.sberbank.ru/payment/rest/getOrderStatusExtended.do';
$sData = http_build_query(array(
    'userName' => "",
    'password' => "",
    'orderNumber' => $orderId
    )
);

$result = file_get_contents($sUrl.http_build_query($sData), false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($sData)
         )
    )
));

$result1 = json_decode($result);

$status = $result1->actionCode;

echo $result1->actionCode;

/*
$qUrl = 'https://secure.payler.com/gapi/GetStatus';
$qData = http_build_query(array(
    'key' => $ps_key, 
	'order_id'=>$orderId
    ));

$result = file_get_contents($qUrl.http_build_query($qData), false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($qData)
        )
    )));
*/
/*
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
));

$result1 = curl_exec($curl);
curl_close($curl);
*/
/*
$result1 = json_decode($result);

$status = $result1->status;

echo $result1->status;
*/
/*
$PROP = array();
//ФИО
$PROP[1]=$name;
//Услуга
$PROP[2]=$service;
//Номер договора
$PROP[3]=$num;
//Почта
$PROP[4]=$email;
//Телефон
$PROP[5]=$phone;
//Сумма
$PROP[6]=$sum/100;
//Номер транзакции
$PROP[7]=$orderId;
//Статус операции
$PROP[8]=$status;

$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"      => $cib_id,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $description,
  "ACTIVE"         => "N"            //пока не активен
  );


//$PRODUCT_ID = $el->Add($arLoadProductArray);
*/

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>