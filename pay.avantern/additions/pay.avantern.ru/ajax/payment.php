<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
//LocalRedirect("/success/");
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
$el = new CIBlockElement;
$cib_id="6"; //задаем ИД информационного блока
$ps_key=file_get_contents('/home/bitrix/.metadata/payler_key', false, null, 0, 36); //извлекаем в строку все содержимое файла с ключем платежной системы

    $arResult = Array();
    $company = trim($_REQUEST["company"]);
    $service = trim($_REQUEST["service"]);
    $name = trim($_REQUEST["name"]);
    $num = trim($_REQUEST["dogovor"]);
	$email = trim($_REQUEST["email"]);
	$phone = trim($_REQUEST["phone"]);
    $rub = intval(trim($_REQUEST["rub"]));
    $kop = intval(trim($_REQUEST["kop"]));
    
    $sum = $rub*100+$kop;
	$description = 'Оплата услуг по договору '.$num.'. Услуга: '.$service.'. Плательщик: '.$name.'. E-mail: '.$email;
  
$orderId=time();


$queryUrl = 'https://secure.payler.com/gapi/StartSession?';
 $queryData = http_build_query(array(
        'key' => $ps_key, 
		'type' =>1,
		'order_id'=>$orderId,
		'amount'=>$sum,
		'product'=>$description,
		'userdata'=>$description,
		'email'=>$email,
		'total'=>1,
		'lang'=>'ru'));

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
 $result = json_decode($result);
 curl_close($curl);


	$session_id=$result->session_id;
    $url = 'https://secure.payler.com/gapi/Pay?session_id='.$session_id;
 header('Refresh: 1; url="'.$url.'"');

$queryUrl = 'https://secure.payler.com/gapi/GetStatus';
 $queryData = http_build_query(array(
        'key' => $ps_key, 
		'order_id'=>$orderId
        ));

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

$result1 = json_decode($result);

$status = $result1->status;

echo $result1->status;


$PROP = array();
//ФИО
$PROP["FIO"]=$name;
//Услуга
$PROP["SERVICES"]=$service;
//Номер договора
$PROP["NUMBER"]=$num;
//Почта
$PROP["EMAIL"]=$email;
//Телефон
$PROP["PHONE"]=$phone;
//Сумма
$PROP["SUMM"]=$sum/100;
//Номер транзакции
$PROP["NUMBER_PAY"]=$orderId;
//Статус операции
$PROP["STATUS"]=$status;

		 $arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"      => $cib_id,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $description,
  "ACTIVE"         => "N"            //пока не активен
  );


$PRODUCT_ID = $el->Add($arLoadProductArray);

?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>