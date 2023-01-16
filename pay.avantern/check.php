<? 
$_SERVER["DOCUMENT_ROOT"]="/home/bitrix/www";
ini_set("max_execution_time",2400);
ini_set('memory_limit','100M');
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
$el = new CIBlockElement;
$cib_id="6"; //задаем ИД информационного блока
$ps_key=file_get_contents('/home/bitrix/.metadata/payler_key', false, null, 0); //извлекаем в строку все содержимое файла с ключем платежной системы
$cass_login=file_get_contents('/home/bitrix/.metadata/lifepay_login', false, null, 0); //извлекаем в строку все содержимое файла с логином облачной кассы
$cass_key=file_get_contents('/home/bitrix/.metadata/lifepay_key', false, null, 0); //извлекаем в строку все содержимое файла с ключем облачной кассы
?>
<?
//получаем текущую дату и время
$now1 = new DateTime();
$now2 = new DateTime();
//print_r($cib_id);
//print_r($ps_key);
//print_r($cass_login);
//print_r($cass_key);
//формируем запрос на формирование массива элементов инфоблока
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields
//$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
//выбираем из инфоблока с ид=6 только неактивные элементы с датой изменения не старше 10 дней и не младше 20 минут от текущей даты (лаг нужен, если клиент совершает несколько неудачных попыток оплаты)
$arFilter = Array("IBLOCK_ID"=>$cib_id, ">=TIMESTAMP_X"=>$now1->modify('-10 days')->format('d.m.Y H:i:s'), "<=TIMESTAMP_X"=>$now2->modify('-30 minutes')->format('d.m.Y H:i:s'), "ACTIVE"=>"N");
//$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE"=>"N");
$res = CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);
	while($ob = $res->GetNextElement())
	{ 
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties();
		print_r($ob);
		if((empty($arProps['UID_NUMBER']['VALUE']))) //если свойство UID_NUMBER (ид чека) не содержит значения - запрашивает статус платежа по ИД-заказа у платежной системы
		{
			$url = 'https://secure.payler.com/gapi/GetAdvancedStatus?';
			$params = array(
				'key' => $ps_key, 
				'order_id' => $arProps['NUMBER_PAY']['VALUE']
				);
			$order=$arProps['NUMBER_PAY']['VALUE'];
			//print_r($order);
			$result = file_get_contents($url.http_build_query($params), false, stream_context_create(array(
				'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
				)
			)));
			$result = json_decode($result);
			$arResult["status"] = $result->status;
			$arResult["amount"] = $arProps['SUMM']['VALUE'];
			$arResult["description"] = $arFields['NAME'];
			$email = $arProps['EMAIL']['VALUE'];
			if($arResult["status"]=="Charged") //если оплата прошла успешно - формируем чек и записываем его номер в св-во инфоблока
			{
				$url1 = 'https://sapi.life-pay.ru/cloud-print/create-receipt';
				$params1 = array(
					'apikey' => $cass_key,
					'login' => $cass_login,
					'purchase' =>array(
						'products'=>array([
							'name'=>$arResult["description"],
							'price'=>$arResult["amount"],
							'quantity'=>1,
							'tax'=>'none',
							'unit'=>'piece'
							])
						),
						'test'=>0,
						'mode'=>'email',
						'customer_email'=>$email,
						'type'=>'payment',
						'card_amount'=>'#',
						'ext_id'=>$order,
						'pos'=>array(
							'perform'=>0,
							'slip_count'=>0
							),
						'tax_system'=>'usn15'
					);
				$result1 = file_get_contents($url1, false, stream_context_create(array(
					'http' => array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/json',	
					'content' => json_encode($params1)
					)
				)));
				$result1 = json_decode($result1); 
				$name=time();
				$UID_NUMBER=$result1->data->uuid;
				$arSelect = Array("ID", "PROPERTY_UID_NUMBER");
				//$arFilter = Array("IBLOCK_ID"=>6, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_NUMBER_PAY"=>$order);
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_NUMBER_PAY"=>$order);
				$res1 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob1 = $res1->GetNextElement())
				{
					$arFields = $ob1->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS"); //записываем полученный статус оплаты в свойство элемента инфоблока
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER, "UID_NUMBER"); //записываем номер сформированного чека
					// Обновляем статус элемента на Активный
 					$ob11 = new CIBlockElement();
 					$ob11->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}							
			} elseif($arResult["status"]=="Rejected") { //если оплата отклонена банком - записываем статус, а в номер чека пишем оплата отклонена
				$UID_NUMBER_2="payment_rejected";
				$arSelect = Array("ID", "PROPERTY_UID_NUMBER");
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_NUMBER_PAY"=>$order);
				$res2 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob2 = $res2->GetNextElement())
				{
					$arFields = $ob2->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS");
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER_2, "UID_NUMBER");
					$ob22 = new CIBlockElement();
 					$ob22->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}
			} elseif($arResult["status"]=="") { //если платежная система не вернула никакой ответ (платеж с таким ИД заказа не найден) - пишем это вместо номера чека
				$UID_NUMBER_3="payment_not_found";
				$arSelect = Array("ID", "PROPERTY_UID_NUMBER");
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_NUMBER_PAY"=>$order);
				$res3 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob3 = $res3->GetNextElement())
				{
					$arFields = $ob3->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER_3, "UID_NUMBER");
					$ob33 = new CIBlockElement();
 					$ob33->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}
			} else { // во всех остальных случаях - записываем статус, активность элемента не меняем, чтобы он попал в запрос при следующем запуске скрипта
				$arSelect = Array("ID", "PROPERTY_UID_NUMBER");
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_NUMBER_PAY"=>$order);
				$res4 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob4 = $res4->GetNextElement())
				{
					$arFields = $ob4->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS");
				}
			}
		}
	}
?>