<?
$_SERVER["DOCUMENT_ROOT"]="/home/bitrix/www";
ini_set("max_execution_time",2400);
ini_set('memory_limit','100M');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
?>
<?
//$el = new CIBlockElement;
//$cib_id="7"; //задаем ИД информационного блока
$sber_user=file_get_contents('/home/bitrix/.metadata/sber_user', false, null, 0, 15); //придется ограничивать число символов для ивзлечения, шлюз не понимает служебные
$sber_pass=file_get_contents('/home/bitrix/.metadata/sber_password', false, null, 0, 8); 
$p_host = 'https://3dsec.sberbank.ru';
$returnUrl = 'https://pay.avantern.ru';

$service = "За мониторинг";
$name = "Иван Иванов";
$num = "T-1234";
$email = "i.ivanov@noemail";
$rub = "10";
$kop = "00";
$orderId = time();

$sum = $rub*100+$kop;
$description = 'Оплата услуг по договору '.$num.'. Услуга: '.$service.'. Плательщик: '.$name.'. E-mail: '.$email;

  
$sUrl = $p_host.'/payment/rest/register.do?';
$sData = array(
    'userName' => $sber_user,
    'password' => $sber_pass,
    'orderNumber' => $orderId,
    'amount' => $sum,
    'returnUrl' => $returnUrl,
    'email' => $email
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

print_r($result);

$p_orderId = $result->orderId;
$pf_url = $result->formUrl;

print_r($p_orderId);
echo($pf_url);

//header('Refresh: 1; url="'.$pf_url.'"');

//  echo "Через 5 секунд вы будите переведены на платежный шлюз банка))";
//	LocalRedirect($url);

$sUrl = $p_host.'/payment/rest/getOrderStatusExtended.do?';
$sData = array(
    'userName' => "",
    'password' => "",
    'orderNumber' => $orderId
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

//echo $result1->actionCode;

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