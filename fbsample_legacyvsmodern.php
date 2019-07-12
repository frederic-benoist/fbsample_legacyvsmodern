<?php
/**
 * 2007-2018 Frédéric BENOIST
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
 *  @copyright 2013-2018 Frédéric BENOIST <https://www.fbenoist.com/>
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__)."/vendor/autoload.php";

use FBenoist\FbSampleLegacyVsModern\Manager\TabManager;
use FBenoist\FbSampleLegacyVsModern\Model\DocumentType;
use FBenoist\FbSampleLegacyVsModern\Controller\AdminDocumentTypeController;

class FbSample_LegacyVsModern extends Module
{
    public function __construct()
    {
        $this->name = 'fbsample_legacyvsmodern';
        $this->version = '1.0.0';
        $this->author = 'Frédéric BENOIST';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Legacy VS Modern admin controller');
        $this->description = $this->l('See 1.7 Legacy and Modern admin controller');
    }

    public function install()
    {
        // Legacy BO Controller does not use namespaces
        include_once dirname(__FILE__).'/controllers/admin/adminlegacydocumenttypeController.php';

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()
            || !DocumentType::createDbTable()
            || !TabManager::addParentTab('Legacy VS Modern')
            || !AdminLegacyDocumentTypeController::installInBO('Document Type (legacy)')
            || !AdminDocumentTypeController::installInBO('Document Type (modern)')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        // Legacy BO Controller does not use namespaces
        include_once dirname(__FILE__).'/controllers/admin/adminlegacydocumenttypeController.php';

        return parent::uninstall()
            && AdminLegacyDocumentTypeController::removeFromBO()
            && AdminDocumentTypeController::removeFromBO()
            && TabManager::removeParentTab()
            && DocumentType::removeDbTable();
    }
}
