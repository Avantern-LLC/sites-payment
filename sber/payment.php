<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
//LocalRedirect("/success/");
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
$el = new CIBlockElement;
//$companies = Array();

//$companies[1] = Array();
//$companies[1]["USERNAME"] = "avantern-api";
//$companies[1]["PASSWORD"] = "avntrN15*";

//$companies[2] = Array();
//$companies[2]["USERNAME"] = "avantern3-api";
//$companies[2]["PASSWORD"] = "3avntrN15*";

//if($_REQUEST["send"] == "Y")
//{


  
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
  

/*
//$products = array(array('PRODUCT_ID' => 1, 'NAME' => $description, 'PRICE' => $rub."."$kop, 'CURRENCY' => 'RUB', 'QUANTITY' => 1));
        
$basket = Bitrix\Sale\Basket::create(SITE_ID);

foreach ($products as $product)
    {
        $item = $basket->createItem("catalog", $product["PRODUCT_ID"]);
        unset($product["PRODUCT_ID"]);
        $item->setFields($product);
    }
    
$order = Bitrix\Sale\Order::create(SITE_ID, 1);
$order->setPersonTypeId(1);
$order->setBasket($basket);

$shipmentCollection = $order->getShipmentCollection();
$shipment = $shipmentCollection->createItem(
        Bitrix\Sale\Delivery\Services\Manager::getObjectById(1)
    );

$shipmentItemCollection = $shipment->getShipmentItemCollection();

/** @var Sale\BasketItem $basketItem */
/*
foreach ($basket as $basketItem)
    {
   		$item = $shipmentItemCollection->createItem($basketItem);
        $item->setQuantity($basketItem->getQuantity());
    }

$paymentCollection = $order->getPaymentCollection();
$payment = $paymentCollection->createItem(
        Bitrix\Sale\PaySystem\Manager::getObjectById(5)
    );
$payment->setField("SUM", $order->getPrice());
$payment->setField("CURRENCY", $order->getCurrency());
    
$result = $order->save();
    if (!$result->isSuccess())
        {
            //$result->getErrors();
        }
$orderId = $order->getId();
//echo $orderId;
*/
 
   /* $url = 'https://3dsec.paymentgate.ru/ipay/rest/register.do';

    $params = array(
        'userName' => $companies[$company]["USERNAME"], 
        'password' => $companies[$company]["PASSWORD"], 
        'orderNumber' => (int) date('YmdHis'),
        'description' => $description,
        'amount' => $sum,
        'returnUrl' => 'http://pay.avantern.ru/success/'
    );
    $result = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($params)
        )
    )));
    
    $result = json_decode($result);
	
    
    if(strlen($result->orderId) > 0 && strlen($result->formUrl) > 0)
    {
        $arResult["OK"] = "Y";
        $arResult["formUrl"] = $result->formUrl;
    }
    else
        $arResult["OK"] = "N";
        
    $arResult = json_encode($arResult);
    
    echo $arResult;*/
//$sum=100;

$orderId=time();


$queryUrl = 'https://secure.payler.com/gapi/StartSession?';
 $queryData = http_build_query(array(
        'key' => '81841953-4e79-4123-9272-ecdac2b9c5fa', 
//		'password'=>'ZhGNYXdCxA',
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
// echo "<pre>"; print_r($result); echo "</pre>";
 curl_close($curl);




 //   $res = json_decode($res);
//	print_r($result->session_id);
	$session_id=$result->session_id;
    $url = 'https://secure.payler.com/gapi/Pay?session_id='.$session_id;
 header('Refresh: 1; url="'.$url.'"');
  //  echo "Через 5 секунд вы будите переведены на платежный шлюз банка))";
//	LocalRedirect($url);

$queryUrl = 'https://secure.payler.com/gapi/GetStatus';
 $queryData = http_build_query(array(
        'key' => '81841953-4e79-4123-9272-ecdac2b9c5fa', 
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
  "IBLOCK_ID"      => 6,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $description,
  "ACTIVE"         => "N"            //пока не активен
  );


$PRODUCT_ID = $el->Add($arLoadProductArray);

?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>