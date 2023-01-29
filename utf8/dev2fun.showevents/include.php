<?php
/**
 * @author dev2fun <darkfriend>
 * @copyright (c) 2018, darkfriend <hi@darkfriend.ru>
 * @version 1.0.2
 */
IncludeModuleLangFile(__FILE__);

\Bitrix\Main\Loader::registerAutoLoadClasses(
    "dev2fun.showevents",
    [
        'Dev2funShowEvents' => 'include.php',
    ]
);

if (class_exists('Dev2funShowEvents')) return;

use \Bitrix\Main\Localization\Loc;

class Dev2funShowEvents
{

    private static $instance;
    public static $module_id = 'dev2fun.showevents';

    /**
     * Singleton instance
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Dev2funShowEvents();
        }
        return self::$instance;
    }

    public function getList()
    {
        $events = $this->getEvents();
        return $events;
    }

    public function getEvents()
    {
        $arEvents = [];

        $con = \Bitrix\Main\Application::getConnection();
        $rs = $con->query("
			SELECT FROM_MODULE_ID, MESSAGE_ID, SORT, TO_MODULE_ID, TO_PATH,
				TO_CLASS, TO_METHOD, TO_METHOD_ARG, VERSION
			FROM b_module_to_module m2m
				INNER JOIN b_module m ON (m2m.TO_MODULE_ID = m.ID)
			ORDER BY SORT
		");
        while ($ar = $rs->fetch()) {
            $ar['TO_NAME'] = $this->formatEventName(
                [
                    "TO_MODULE_ID" => $ar["TO_MODULE_ID"],
                    "TO_CLASS" => $ar["TO_CLASS"],
                    "TO_METHOD" => $ar["TO_METHOD"],
                ]
            );
            $ar["~FROM_MODULE_ID"] = strtoupper($ar["FROM_MODULE_ID"]);
            $ar["~MESSAGE_ID"] = strtoupper($ar["MESSAGE_ID"]);
            if (strlen($ar["TO_METHOD_ARG"]) > 0) {
                $ar["TO_METHOD_ARG"] = unserialize($ar["TO_METHOD_ARG"], ["allowed_classes" => true]);
            } else {
                $ar["TO_METHOD_ARG"] = [];
            }

            $arEvents[] = $ar;
        }
        return $arEvents;
    }

    protected function formatEventName($arEvent)
    {
        $strName = '';
        if (isset($arEvent["CALLBACK"])) {
            if (is_array($arEvent["CALLBACK"])) {
                $strName .= (is_object($arEvent["CALLBACK"][0]) ? get_class($arEvent["CALLBACK"][0]) : $arEvent["CALLBACK"][0]) . '::' . $arEvent["CALLBACK"][1];
            } elseif (is_callable($arEvent["CALLBACK"])) {
                $strName .= "callable";
            } else {
                $strName .= $arEvent["CALLBACK"];
            }
        } else {
            $strName .= $arEvent["TO_CLASS"] . '::' . $arEvent["TO_METHOD"];
        }
        if (isset($arEvent['TO_MODULE_ID']) && !empty($arEvent['TO_MODULE_ID'])) {
            $strName .= ' (' . $arEvent['TO_MODULE_ID'] . ')';
        }
        return $strName;
    }

    public static function ShowThanksNotice()
    {
        \CAdminNotify::Add([
            'MESSAGE' => Loc::getMessage('D2F_SHOWEVENTS_DONATE_MESSAGE', ['#URL#' => '/bitrix/admin/dev2fun_show_events.php?action=settings&tabControl_active_tab=donate']),
            'TAG' => 'dev2fun_showevents_update',
            'MODULE_ID' => 'dev2fun.showevents',
        ]);
    }

    public function DoBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        $aModuleMenu[] = [
            "parent_menu" => "global_menu_settings",
            "icon" => "dev2fun_showevents_admin_icon",
            "page_icon" => "dev2fun_showevents_admin_icon",
            "sort" => "900",
            "text" => Loc::getMessage("D2F_SHOWEVENTS_MENU_TEXT"),
            "title" => Loc::getMessage("D2F_SHOWEVENTS_MENU_TITLE"),
            "url" => "/bitrix/admin/dev2fun_show_events.php?action=settings",
            "items_id" => "menu_dev2fun_showevents",
            "section" => "dev2fun_showevents",
            "more_url" => [],
            // "items" => array(
            //     array(
            //         "text" => GetMessage("SUB_SETINGS_MENU_TEXT"),
            //         "title" => GetMessage("SUB_SETINGS_MENU_TITLE"),
            //         "url" => "/bitrix/admin/dev2fun_opengraph_manager.php?action=settings",
            //         "sort" => "100",
            //         "icon" => "sys_menu_icon",
            //         "page_icon" => "default_page_icon",
            //     ),
            // )
        ];
    }
}