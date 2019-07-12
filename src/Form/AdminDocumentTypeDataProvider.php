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

namespace FBenoist\FbSampleLegacyVsModern\Form;

use Validate;
use FBenoist\FbSampleLegacyVsModern\Model\DocumentType;

use PrestaShop\PrestaShop\Adapter\Presenter\Object\ObjectPresenter;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class AdminDocumentTypeDataProvider implements FormDataProviderInterface
{
   /**
     * {@inheritdoc}
     */
    public function getData($id)
    {
        $objectPresenter = new ObjectPresenter();

        $record = new DocumentType((int)$id);
        if (Validate::isLoadedObject($record)) {
            return $objectPresenter->present($record);
        }

        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData()
    {
        $objectPresenter = new ObjectPresenter();

        $record = new DocumentType();
        return $objectPresenter->present($record);
    }
}
