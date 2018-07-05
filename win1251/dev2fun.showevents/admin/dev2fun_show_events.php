<?php
/**
 * @author dev2fun <darkfriend>
 * @copyright (c) 2018, darkfriend <hi@darkfriend.ru>
 * @version 1.0.0
 * @global CUser $USER
 * @global CMain $APPLICATION
 */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule("dev2fun.showevents");

use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

IncludeModuleLangFile($GLOBALS['reqPath']);

$canRead = $USER->CanDoOperation('d2f_showevents_settings_read');
$canWrite = $USER->CanDoOperation('d2f_showevents_settings_write');
if(!$canRead && !$canWrite) $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$EDITION_RIGHT = $APPLICATION->GetGroupRight(Dev2funShowEvents::$module_id);
if ($EDITION_RIGHT=="D") $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ACTION'])) {
	$eventManager = \Bitrix\Main\EventManager::getInstance();
//	var_dump($_REQUEST);die();
//	$_REQUEST['FROM_MODULE_ID'];
//	$_REQUEST['MESSAGE_ID'];
//	$_REQUEST['TO_MODULE_ID'];
//	$_REQUEST['TO_CLASS'];
//	$_REQUEST['TO_METHOD'];
	$fromModuleID = false;
	$eventType = false;
	$moduleID = false;
	$moduleClass = false;
	$moduleClassMethod = false;

	if(isset($_REQUEST['FROM_MODULE_ID'])) {
		$fromModuleID = htmlspecialcharsbx($_REQUEST['FROM_MODULE_ID']);
	}
	if(isset($_REQUEST['MESSAGE_ID'])) {
		$eventType = htmlspecialcharsbx($_REQUEST['MESSAGE_ID']);
	}
	if(isset($_REQUEST['TO_MODULE_ID'])) {
		$moduleID = htmlspecialcharsbx($_REQUEST['TO_MODULE_ID']);
	}
	if(isset($_REQUEST['TO_CLASS'])) {
		$moduleClass = htmlspecialcharsbx($_REQUEST['TO_CLASS']);
	}
	if(isset($_REQUEST['TO_METHOD'])) {
		$moduleClassMethod = htmlspecialcharsbx($_REQUEST['TO_METHOD']);
	}

	if($fromModuleID&&$eventType&&$moduleID&&$moduleClass&&$moduleClassMethod) {
		$eventManager->unRegisterEventHandler(
			$fromModuleID,
			$eventType,
			$moduleID,
			$moduleClass,
			$moduleClassMethod
		);
	}
	die('1');
}

$aTabs = array(
	array(
		"DIV" => "main",
		"TAB" => Loc::getMessage("SEC_MAIN_TAB"),
		"ICON"=>"main_user_edit",
		"TITLE"=>Loc::getMessage("SEC_MAIN_TAB_TITLE"),
	),
	array(
		"DIV" => "donate",
		"TAB" => Loc::getMessage('SEC_DONATE_TAB'),
		"ICON"=>"main_user_edit",
		"TITLE"=>Loc::getMessage('SEC_DONATE_TAB_TITLE'),
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);
$bVarsFromForm = false;

require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
?>

    <link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/components.cards.min.css">
    <link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/objects.grid.min.css">
    <link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/objects.grid.responsive.min.css">
    <link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/objects.containers.min.css">
    <link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/components.tables.min.css">

    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

<?
$tabControl->Begin();
$tabControl->BeginNextTab();

$list = Dev2funShowEvents::getInstance()->getList();
$result = [];
foreach ($list as $arItem) {
	$event = [];
	foreach ($arItem as $k=>$item) {
		if(in_array($k,['VERSION','TO_PATH','TO_NAME'])) continue;
		$event[$k] = $item;
	}
	$result[] = $event;
}
?>
    <tr>
        <td colspan="2" align="left">
            <div style="float: left;margin-bottom: 10px;width: 177px; height: 36px; overflow: hidden">
                <a href="https://www.patreon.com/bePatron?u=10402766" data-patreon-widget-type="become-patron-button">Become a Patron!</a><script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>
            </div>
            <div style="float: left;margin-bottom: 10px;">
                <iframe src="https://money.yandex.ru/quickpay/button-widget?targets=%D0%9F%D0%BE%D0%B4%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D1%82%D1%8C%20%D0%BC%D0%BE%D0%B4%D1%83%D0%BB%D1%8C&default-sum=500&button-text=14&any-card-payment-type=on&button-size=m&button-color=orange&successURL=&quickpay=small&account=410011413398643&" width="230" height="36" frameborder="0" allowtransparency="true" scrolling="no"></iframe>
            </div>
            <div id="jsGrid"></div>

            <script type="text/javascript">

				var clients = <?=json_encode($result)?>;

				$("#jsGrid").jsGrid({
					width: "100%",
					// height: "90%",

					autoload: true,
					filtering: true,
					inserting: false,
					editing: false,
					sorting: true,
					paging: true,
					pageSize: 50,
					deleteConfirm: "<?=Loc::getMessage('DEV2FUN_SHOW_EVENTS_DELETE_CONFIRM_ALERT')?>",

					data: clients,

					controller : {
						deleteItem : function(item) {
							item.ACTION = 'event_delete';
							return $.ajax({
								type: "POST",
								url: "/bitrix/admin/dev2fun_show_events.php",
								data: item
							});
						},
						data:clients,
						loadData: function (filter) {
							var filtering = false;
							for(item in filter) {
								if(typeof filter[item] != 'undefined' && filter[item].length>0) {
									filtering = item;
									break;
								}
							}
							return $.grep(this.data, function (item) {
								// console.log(filter);
								if(filtering) {
									return (!filter[filtering] || item[filtering].indexOf(filter[filtering]) >= 0);
								} else {
									return filter;
								}
							});
						},
					},

					fields: [
						{ name: "FROM_MODULE_ID", type: "text", width: 100, autosearch: true },
						{ name: "MESSAGE_ID", type: "text", width: 250 },
						{ name: "SORT", type: "number", width: 50 },
						{ name: "TO_MODULE_ID", type: "text", width: 250 },
						{ name: "TO_CLASS", type: "text", width: 250 },
						{ name: "TO_METHOD", type: "text", width: 250 },
						// { name: "VERSION", type: "number", width: 50 },
						// { name: "TO_NAME", type: "text", width: 250 },
						// { name: "Name", type: "text", width: 150, validate: "required" },
						// { name: "Age", type: "number", width: 50 },
						// { name: "Address", type: "text", width: 200 },
						// { name: "Country", type: "select", items: countries, valueField: "Id", textField: "Name" },
						// { name: "Married", type: "checkbox", title: "Is Married", sorting: false },
						{
							type: "control",
							editButton : false
						}
					]
				});
            </script>
        </td>
    </tr>
<?$tabControl->BeginNextTab();?>
    <tr>
        <td colspan="2" align="left">
            <div class="o-container--super">
                <div class="o-grid">
                    <div class="o-grid__cell o-grid__cell--width-70">
                        <div class="c-card">
                            <div class="c-card__body">
                                <p class="c-paragraph"><?= Loc::getMessage('LABEL_TITLE_HELP_BEGIN')?>.</p>
								<?=Loc::getMessage('LABEL_TITLE_HELP_BEGIN_TEXT');?>
                            </div>
                        </div>
                        <div class="o-container--large">
                            <h2 id="yaPay" class="c-heading u-large"><?=Loc::getMessage('LABEL_TITLE_HELP_DONATE_TEXT');?></h2>
                            <iframe src="https://money.yandex.ru/quickpay/shop-widget?writer=seller&targets=%D0%9F%D0%BE%D0%B4%D0%B4%D0%B5%D1%80%D0%B6%D0%BA%D0%B0%20%D0%BE%D0%B1%D0%BD%D0%BE%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B9%20%D0%B1%D0%B5%D1%81%D0%BF%D0%BB%D0%B0%D1%82%D0%BD%D1%8B%D1%85%20%D0%BC%D0%BE%D0%B4%D1%83%D0%BB%D0%B5%D0%B9&targets-hint=&default-sum=500&button-text=14&payment-type-choice=on&mobile-payment-type-choice=on&hint=&successURL=&quickpay=shop&account=410011413398643" width="450" height="228" frameborder="0" allowtransparency="true" scrolling="no"></iframe>
                            <h2 id="morePay" class="c-heading u-large"><?=Loc::getMessage('LABEL_TITLE_HELP_DONATE_ALL_TEXT');?></h2>
                            <table class="c-table">
                                <tbody class="c-table__body c-table--striped">
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Yandex.Money</td>
                                    <td class="c-table__cell">410011413398643</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WMR (rub)</td>
                                    <td class="c-table__cell">R218843696478</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WMU (uah)</td>
                                    <td class="c-table__cell">U135571355496</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WMZ (usd)</td>
                                    <td class="c-table__cell">Z418373807413</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WME (euro)</td>
                                    <td class="c-table__cell">E331660539346</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WMX (btc)</td>
                                    <td class="c-table__cell">X740165207511</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WML (ltc)</td>
                                    <td class="c-table__cell">L718094223715</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Webmoney WMH (bch)</td>
                                    <td class="c-table__cell">H526457512792</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">PayPal</td>
                                    <td class="c-table__cell"><a href="https://www.paypal.me/darkfriend" target="_blank">paypal.me/@darkfriend</a></td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Payeer</td>
                                    <td class="c-table__cell">P93175651</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Bitcoin</td>
                                    <td class="c-table__cell">15Veahdvoqg3AFx3FvvKL4KEfZb6xZiM6n</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Litecoin</td>
                                    <td class="c-table__cell">LRN5cssgwrGWMnQruumfV2V7wySoRu7A5t</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">Ethereum</td>
                                    <td class="c-table__cell">0xe287Ac7150a087e582ab223532928a89c7A7E7B2</td>
                                </tr>
                                <tr class="c-table__row">
                                    <td class="c-table__cell">BitcoinCash</td>
                                    <td class="c-table__cell">bitcoincash:qrl8p6jxgpkeupmvyukg6mnkeafs9fl5dszft9fw9w</td>
                                </tr>
                                </tbody>
                            </table>
                            <h2 id="moreThanks" class="c-heading u-large"><?=Loc::getMessage('LABEL_TITLE_HELP_DONATE_OTHER_TEXT');?></h2>
							<?=Loc::getMessage('LABEL_TITLE_HELP_DONATE_OTHER_TEXT_S');?>
                        </div>
                    </div>
                    <div class="o-grid__cell o-grid__cell--width-30">
                        <h2 id="moreThanks" class="c-heading u-large"><?=Loc::getMessage('LABEL_TITLE_HELP_DONATE_FOLLOW');?></h2>
                        <table class="c-table">
                            <tbody class="c-table__body">
                            <tr class="c-table__row">
                                <td class="c-table__cell">
                                    <a href="https://vk.com/dev2fun" target="_blank">vk.com/dev2fun</a>
                                </td>
                            </tr>
                            <tr class="c-table__row">
                                <td class="c-table__cell">
                                    <a href="https://facebook.com/dev2fun" target="_blank">facebook.com/dev2fun</a>
                                </td>
                            </tr>
                            <tr class="c-table__row">
                                <td class="c-table__cell">
                                    <a href="https://twitter.com/dev2fun" target="_blank">twitter.com/dev2fun</a>
                                </td>
                            </tr>
                            <tr class="c-table__row">
                                <td class="c-table__cell">
                                    <a href="https://t.me/dev2fun" target="_blank">telegram/dev2fun</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </td>
    </tr>
<?
$tabControl->End();
?>
    <style type="text/css">
        .jsgrid-cell{
            word-wrap: break-word;
        }
        .adm-workarea input[type="button"].jsgrid-button{
            background-image: url(data:image/png;base64,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) !important;
            padding: 0;
            margin-top: 7px;
            border: 0;
            box-shadow: none;
            background-color: transparent !important;
            height: 19px;
        }
        .adm-workarea input[type="button"].jsgrid-button:hover{
            padding: 0;
            margin-top: 7px;
            border: 0;
            box-shadow: none;
            background-color: transparent !important;
            height: 19px;
        }
    </style>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>