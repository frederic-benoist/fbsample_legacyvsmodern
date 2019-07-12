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

namespace FBenoist\FbSampleLegacyVsModern\Model;

use Context;
use Db;
use DbQuery;
use ObjectModel;
use Validate;

class DocumentType extends ObjectModel
{
    public $active = false;
    public $maxsize = 0;
    public $name;
    public $description;

    public static $definition = array(
        'table' => 'documenttype',
        'primary' => 'id_documenttype',
        'multilang' => true,
        'fields' => array(
            'active' => array('type' => self::TYPE_BOOL, 'required' => true),
            'maxsize' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 120
            ),
            'description' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999
            ),
        )
    );

    public static function createDbTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'documenttype`(
                    `id_documenttype` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `active` TINYINT(1) NOT NULL DEFAULT \'0\',
                    `maxsize` BIGINT(10) UNSIGNED NOT NULL DEFAULT \'0\',
                    PRIMARY KEY (`id_documenttype`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8')
            && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'documenttype_lang`(
                    `id_documenttype` INT(10) UNSIGNED NOT NULL,
                    `id_lang` INT(10) UNSIGNED NOT NULL,
                    `name` VARCHAR(120) DEFAULT NULL,
                    `description` TEXT,
                    PRIMARY KEY (`id_documenttype`,`id_lang`)
                    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
    }

    public static function removeDbTable()
    {
        return Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'documenttype`')
            && Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'documenttype_lang`');
    }
}
