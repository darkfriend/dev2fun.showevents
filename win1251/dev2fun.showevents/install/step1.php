<?php
/**
 * @author dev2fun <darkfriend>
 * @copyright 2018, darkfriend <hi@darkfriend.ru>
 * @version 1.0.0
 */
if(!check_bitrix_sessid()) return;
IncludeModuleLangFile(__FILE__);

CModule::IncludeModule("main");

$msg = new CAdminMessage([
    'MESSAGE' => GetMessage("D2F_SHOWEVENTS_INSTALL_SUCCESS"),
    'TYPE' => 'OK',
]);
echo $msg->Show();

echo BeginNote();
	echo GetMessage("D2F_SHOWEVENTS_INSTALL_LAST_MSG");
EndNote();