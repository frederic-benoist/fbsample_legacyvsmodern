<?php
/**
 * 2013-2019 Frédéric BENOIST
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * Use, copy, modification or distribution of this source file without written
 * license agreement from Frédéric BENOIST is strictly forbidden.
 *
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence Academic Free License (AFL 3.0)
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de Frédéric BENOIST est
 * expressement interdite.
 *
 *  @author    Frédéric BENOIST
 *  @copyright 2013-2019 Frédéric BENOIST <https://www.fbenoist.com>
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace FBenoist\FbSampleLegacyVsModern\Manager;

use Tab;
use Language;

class TabManager
{
    const PARENT_CLASS = 'PC_Adddoconorder';

    private static function addTab($className, $tabName, $moduleName, $parentClassName, $icon = '')
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }
        $tab->id_parent = (int) Tab::getIdFromClassName($parentClassName);
        $tab->module = $moduleName;
        $tab->icon = $icon;
        $tab->add();

        return $tab;
    }

    public static function addParentTab($tabName)
    {
        self::addTab(self::PARENT_CLASS, $tabName, 'fbsample_legacyvsmodern', 'CONFIGURE', 'business');
        return true;
    }

    public static function removeParentTab()
    {
        self::removeTab(self::PARENT_CLASS);
        return true;
    }

    public static function addChildTab($className, $tabName)
    {
        self::addTab($className, $tabName, 'fbsample_legacyvsmodern', self::PARENT_CLASS);
        return true;
    }

    public static function removeTab($className)
    {
        $id_tab = (int) Tab::getIdFromClassName($className);
        $tab = new Tab($id_tab);
        if ($tab->name !== '') {
            $tab->delete();
        }
        return true;
    }
}
