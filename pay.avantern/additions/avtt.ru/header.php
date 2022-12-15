<script type="text/javascript">
var __cs = __cs || [];
__cs.push(["setCsAccount", "YCspHvw_mzRcqqYuaqy9GbLlGSiIhZ25"]);
</script>
<script type="text/javascript" async src="https://app.uiscom.ru/static/cs.min.js"></script>;<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");?>
<?$curPage = $APPLICATION->GetCurPage(true);?>
<?$theme = COption::GetOptionString("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);?>
<?CJSCore::Init(array("fx"));?>
<!DOCTYPE HTML>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="author" content="Avantern" />
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
	<title><?$APPLICATION->ShowTitle()?></title>
    <?$APPLICATION->ShowHead();?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/bootstrap.css");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/styles.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/font-awesome.min.css");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/fancybox/jquery.fancybox.css?v=2.1.5");?>
    <?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/bootstrap-image-gallery.min.css");?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/responsive.css");?>
    <meta property="og:site_name" content="Авантерн Телематика"/>
    <meta property="og:url" content="https://avtt.ru"/> 
    <meta property="og:title" content="<?$APPLICATION->ShowTitle()?>" />
    <meta property="og:image" content="https://avtt.ru/<?=SITE_TEMPLATE_PATH?>/images/logo_soc.png" />
    <?=$APPLICATION->ShowMeta("description", "og:description")?>

<meta name="yandex-verification" content="436a6d15e0a9db41" />
  <meta name="mailru-verification" content="9ec8ba8c18f43baa" />
<meta name="facebook-domain-verification" content="quwtzj3fee6hb7lvvcrki2re3abgxm" />
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-85199372-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-85199372-1');
  gtag('set', {'user_id': 'USER_ID'});
</script>

<!-- Event snippet for Просмотр страницы conversion page -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TJHV5H7');</script>
<!-- End Google Tag Manager -->

<!-- Определяем IP -->  
<script>
var yaParams = {ip_adress: "<? echo $_SERVER['REMOTE_ADDR'];?>"};
</script>
  <!-- / Определяем IP --> 
<script type="text/javascript">
var __cs = __cs || [];
__cs.push(["setCsAccount", "YCspHvw_mzRcqqYuaqy9GbLlGSiIhZ25"]);
</script>
<script type="text/javascript" async src="https://app.uiscom.ru/static/cs.min.js"></script>
<!-- Marquiz script start -->
<script>
(function(w, d, s, o){
  var j = d.createElement(s); j.async = true; j.src = '//script.marquiz.ru/v2.js';j.onload = function() {
    if (document.readyState !== 'loading') Marquiz.init(o);
    else document.addEventListener("DOMContentLoaded", function() {
      Marquiz.init(o);
    });
  };
  d.head.insertBefore(j, d.head.firstElementChild);
})(window, document, 'script', {
    host: '//quiz.marquiz.ru',
    region: 'eu',
    id: '60db30875c2305004cd19e43',
    autoOpen: false,
    autoOpenFreq: 'once',
    openOnExit: false,
    disableOnMobile: false
  }
);
</script>
<!-- Marquiz script end -->
</head>
<?$APPLICATION->ShowPanel();?>
<body id="body">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TJHV5H7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<img style="position: absolute; top: -10000px; left: -10000px;" src="<?=SITE_TEMPLATE_PATH?>/images/logo_soc.png" alt="<?$APPLICATION->ShowTitle()?>" />
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hidden-lg hidden-md hidden-sm header-top-mobile">
        <div class="row">                          
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 logo">
                <div class="row">
                    <a href="/">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/logo_mob.png" class="img-responsive" alt="<?$APPLICATION->ShowTitle()?>" />
                    </a>                       
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="float:right;">
                <div class="row">
                    <div class="menu-open">
                        <a href="#" class="mobile-menu-open"></a>
                    </div>
                             <a href="tel:+74953207764" class="mobile-phone"><span>+7 (495) 320-77-64</span></a>
                  </div>
            </div>
        </div>
    </div>
    <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top-menu-mobile", 
	array(
		"COMPONENT_TEMPLATE" => "top-menu-mobile",
		"ROOT_MENU_TYPE" => "top",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "2",
		"CHILD_MENU_TYPE" => "pod",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
    <header <?if(($APPLICATION->GetCurDir()) == "/"):?>class="inner"<?else:?>class="content_image"<?endif;?> style="background-image: url('<?=$APPLICATION->GetDirProperty("header-picture");?>');">
        <div class="container padding">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hidden-xs">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-5 col-xs-12">
                                    <div class="row">
                                        <div class="header-logo-menu">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <a href="/" class="logo">
                                                            <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" class="img-responsive logo" alt="<?$APPLICATION->ShowTitle()?>" />
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <div class="drop-down">
                                                            <div class="drop-down-container">
                                                                <a class="drop">Телематика</a>
                                                            </div>
                                                        </div>
                                                    </td> 
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                                    <div class="row">
                                        <div class="contacts">
                                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 phone">
                                                <div class="row">
                                                    <span class="phone <?if($APPLICATION->GetCurDir() != "/support/"):?>call_phone<?endif;?>">
                                                        <?if($APPLICATION->GetCurDir() == "/support/"):?>
                                                            <a href="tel:88001003039">8 800 100-30-39</a>
                                                        <?else:?>
                                                      
                                                      <a href="tel:+74953207764" ><span>+7 (495) 320-77-64</span></a>
 
                                                        <?endif;?>
                                                    </span>
                                                    <span class="email">
														<a href="/contacts/">Контактная информация</a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 button">
                                                <div class="row">
<script data-b24-form="click/14/m53tke" data-skip-moving="true">
        (function(w,d,u){
                var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://avantern24.ru/upload/crm/form/loader_14_m53tke.js');
</script>
<a class="form callback" data-title="Заказ обратного звонка" data-tech-title="Заказать звонок (из шапки)">Обратный звонок</a>

                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top-menu", 
	array(
		"COMPONENT_TEMPLATE" => "top-menu",
		"ROOT_MENU_TYPE" => "top",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "3",
		"CHILD_MENU_TYPE" => "pod",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPOSITE_FRAME_MODE" => "Y",
		"COMPOSITE_FRAME_TYPE" => "STATIC"
	),
	false
);?>
                        <?if($APPLICATION->GetCurDir() == "/" ):?>    
                            <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/lang/ru/main-page-header.php', array(),
                                array(
                                    'MODE'  => 'html',
                                    'TEMPLATE'  => 'page_inc.php',
                                )
                            );?>
                        <?else:?>             
                            <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"breadcrumbs", 
	array(
		"COMPONENT_TEMPLATE" => "breadcrumbs",
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => "s2",
		"COMPOSITE_FRAME_MODE" => "Y",
		"COMPOSITE_FRAME_TYPE" => "DYNAMIC_WITH_STUB_LOADING"
	),
	false
);?>               
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">
                                <div class="row">
							<?if($APPLICATION->GetCurDir() == "/solutions/"):?>
                                    <h1>
									<p>GPS и ГЛОНАСС. Спутниковые системы контроля за автотранспортом.</p></h1>
							  <?else:?>
							<h1><?=$APPLICATION->ShowTitle(false);?></h1>
							<?endif;?>
                                </div>
                            </div>
                        <?endif;?>                
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?if($APPLICATION->GetCurDir() != "/"):?>
        <div class="container padding page-container">
            <div class="row">
				<?if($APPLICATION->GetDirProperty("left-menu") == "Y" && $APPLICATION->GetCurDir() != "/about/stuff/"):?>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 left-m-cont">
                        <div class="row">
                            <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"left-menu", 
	array(
		"COMPONENT_TEMPLATE" => "left-menu",
		"ROOT_MENU_TYPE" => "pod",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPOSITE_FRAME_MODE" => "Y",
		"COMPOSITE_FRAME_TYPE" => "STATIC"
	),
	false
);?>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 work-area">
                        <div class="row">
                <?else:?>      
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 work-area">
                        <div class="row">        
                <?endif;?>
    <?endif;?>