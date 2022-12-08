<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Ваша оплата прошла успешно — АВАНТЕРН");
$APPLICATION->SetTitle("Ваша оплата прошла успешно");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

?>

<h3 style="margin-top:0">Ваша оплата прошла успешно. <br>Спасибо, что пользуетесь нашими услугами.</h3>
<?global $APPLICATION;
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
$el = new CIBlockElement;
$cib_id="6"; //задаем ИД информационного блока
$ps_key=file_get_contents('/home/bitrix/.metadata/payler_key', false, null, 0, null); //извлекаем в строку все содержимое файла с ключем платежной системы
$cass_login=file_get_contents('/home/bitrix/.metadata/lifepay_login', false, null, 0, null); //извлекаем в строку все содержимое файла с логином облачной кассы
$cass_key=file_get_contents('/home/bitrix/.metadata/lifepay_key', false, null, 0, null); //извлекаем в строку все содержимое файла с ключем облачной кассы
//$dir = $APPLICATION->GetCurUri();
//$dir = explode("order_id=", $dir);
$order=$_GET["order_id"];
//print_r($order);
$arResult = Array();

function logFile($textLog) {
$file = 'logFile.txt';
$text = '=======================\n';
$text .= print_r($textLog,true);
$text .= '\n'. date('Y-m-d H:i:s') .'\n'; //Добавим актуальную дату после текста или дампа массива
$fOpen = fopen($file,'a');
fwrite($fOpen, $text);
fclose($fOpen);
}
$now = new DateTime();
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields
$arFilter = Array(
	"IBLOCK_ID"=>$cib_id,
	"ACTIVE"=>"N",  
	">TIMESTAMP_X" => $now->modify('-2 day')->format('d.m.Y H:i:s'),
	"PROPERTY_NUMBER_PAY"=>$order
	);
$res = CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);
while($ob = $res->GetNextElement())
	{ 
	$arFields = $ob->GetFields();  
	$arProps = $ob->GetProperties();
	if(empty($arProps['UID_NUMBER']['VALUE'])) //проверка не обрабон ли платеж с таким ИД заказа ранее. Если не обработан - запрашиваем статус у платежной системы
		{
    	$url = 'https://secure.payler.com/gapi/GetAdvancedStatus?';

    	$params = array(
        	'key' => $ps_key, 
        	'order_id' => $order
    		);
    	$result = file_get_contents($url.http_build_query($params), false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($params)
        	)
    	)));
		//	echo "<pre>"; print_r($url.http_build_query($params));echo "</pre>";
    	$result = json_decode($result);
		//	echo "<pre>"; print_r($result); echo "</pre>";
		$arResult["status"] = $result->status;
		$arResult["amount"] = ($result->amount/100);
		$arResult["description"] = $result->userdata;
		$email = explode("E-mail: ",$arResult["description"]);
		//	echo "<pre>"; print_r($email); echo "</pre>";	
		if($arResult["status"]=="Charged") //если оплата подтверждена - формируем чек
    		{
			//выбираем страницу на которую необходимо отправить запрос
			$url1 = 'https://sapi.life-pay.ru/cloud-print/create-receipt';
			//параметры которые необходимо передать
			$params1 = array(
    			'apikey' => $cass_login,
    			'login' => $cass_key,
    			'purchase' =>array(
				'products'=>array([
					'name'=>$arResult["description"],
					'price'=>$arResult["amount"],
					'quantity'=>1,
					'tax'=>'none',
					'unit'=>'piece'
					])),
				'test'=>0,
				'mode'=>'email',
				'customer_email'=>$email[1],
				'type'=>'payment',
				'card_amount'=>'#',
				'ext_id'=>$order,
				'pos'=>array
					(
					'perform'=>0,
					'slip_count'=>0
					),
				'tax_system'=>'usn15'
				);
			//$buf=json_encode($params,JSON_UNESCAPED_UNICODE);
			$result1 = file_get_contents($url1, false, stream_context_create(array(
    			'http' => array(
        			'method'  => 'POST',
        			'header'  => 'Content-type: application/json',	
        			'content' => json_encode($params1)
    				)
			)));
 			$result1 = json_decode($result1); 
			$name=time();
			//?order_id=1569319542
			$UID_NUMBER=$result1->data->uuid;
			$arSelect = Array("ID", "PROPERTY_UID_NUMBER");
			//$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_NUMBER_PAY"=>$order);
			$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_NUMBER_PAY"=>$order);
			$res1 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
			while($ob1 = $res1->GetNextElement())
				{
 				$arFields = $ob1->GetFields();
 				CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER, "UID_NUMBER");
				CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS");
				// Обновляем статус элемента на Активный
 				$ob11 = new CIBlockElement();
 				$ob11->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}
			logFile($result1);
			header('Refresh: 1; url="https://pay.avantern.ru"'); //перенаправляем клиента обратно на сайт
			}
		}
	}
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>