<?php
/**
 * 2007-2019 Frédéric BENOIST
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    Frédéric BENOIST
 *  @copyright 2013-2019 Frédéric BENOIST <https://www.fbenoist.com/>
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once _PS_MODULE_DIR_.'fbsample_legacyvsmodern/vendor/autoload.php';

use FBenoist\FbSampleLegacyVsModern\Model\DocumentType;

class AdminLegacyDocumentTypeController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'documenttype';
        $this->className = 'FBenoist\FbSampleLegacyVsModern\Model\DocumentType';
        $this->lang = true;
        $this->bootstrap = true;
        parent::__construct();

        // HelperList Fields params
        $this->fields_list = array(
            'id_documenttype' => array('title' => '#'),
            'name' => array(
                'title' => $this->module->l('Name', 'AdminLegacyDocumentTypeController')
            ),
            'active' => array(
                'title' => $this->module->l('Active', 'AdminLegacyDocumentTypeController'),
                'active' => 'status'
            ),
            'maxsize' => array(
                'title' => $this->module->l('Max file size (Ko)', 'AdminLegacyDocumentTypeController')
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->module->l('Delete selected', 'AdminLegacyDocumentTypeController'),
                'confirm' => $this->module->l('Delete selected items?', 'AdminLegacyDocumentTypeController')
            ),
            'enableSelection' => array(
                'text' => $this->module->l('Enable selection', 'AdminLegacyDocumentTypeController')
            ),
            'disableSelection' => array(
                'text' => $this->module->l('Disable selection', 'AdminLegacyDocumentTypeController')
                )
        );
    }

    /**
     * Add Edit and Delete on each line
     *
     * @return void
     */
    public function renderList()
    {
        // TODO: Add custom action
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    /**
     * Create form
     *
     * @return void
     */
    public function renderForm()
    {
        if (!$this->loadObject(true)) {
            return;
        }

        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
               'title' => $this->module->l('Edit document type', 'AdminLegacyDocumentTypeController')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Name', 'AdminLegacyDocumentTypeController'),
                    'name' => 'name',
                    'size' => 120,
                    'lang' => true,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Max file size', 'AdminLegacyDocumentTypeController'),
                    'name' => 'maxsize',
                    'class' => 'input fixed-width-md',
                    'suffix' => 'Ko',
                    'desc' => $this->module->l(
                        'Enter 0 to disable the maximum size control',
                        'AdminLegacyDocumentTypeController'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->module->l('Active', 'AdminLegacyDocumentTypeController'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->module->l('Yes', 'AdminLegacyDocumentTypeController')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->module->l('No', 'AdminLegacyDocumentTypeController')
                        )
                    )
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->module->l('Description', 'AdminLegacyDocumentTypeController'),
                    'name' => 'description',
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 10,
                    'cols' => 100
                )
            ),
            'submit' => array(
                'title' => $this->module->l('Save')
            )
        );
        return parent::renderForm();
    }

    /**
     * Install AdminDocumentType in customer back office menu
     * @return boolean true if success
     */
    public static function installInBO($menu_entry_title)
    {
        // Use Legacy
        $new_menu = new Tab();
        $new_menu->id_parent = Tab::getIdFromClassName('PC_Adddoconorder'); // @see TabManager
        $new_menu->class_name = 'AdminLegacyDocumentType'; // Class Name (Without "Controller")
        $new_menu->module = 'fbsample_legacyvsmodern'; // Module name
        $new_menu->active = true;

        // Set menu name in all active Language.
        $languages = Language::getLanguages(true);
        foreach ($languages as $language) {
            $new_menu->name[(int)$language['id_lang']] = $menu_entry_title;
        }
        return $new_menu->save();
    }

    /**
     * Remove AdminDocumentType in customer back office menu
     * @return boolean true if success
     */
    public static function removeFromBO()
    {
        $remove_id = Tab::getIdFromClassName('AdminLegacyDocumentType');
        if ($remove_id) {
            $to_remove = new Tab((int)$remove_id);
            if (validate::isLoadedObject($to_remove)) {
                return $to_remove->delete();
            }
        }
        return true;
    }
}
