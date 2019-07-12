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

namespace FBenoist\FbSampleLegacyVsModern\Controller;

use FBenoist\FbSampleLegacyVsModern\Model\DocumentType;
use Validate;

use FBenoist\FbSampleLegacyVsModern\Filter\AdminDocumentTypeFilter;
use FBenoist\FbSampleLegacyVsModern\Manager\TabManager;

use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminDocumentTypeController
 */
class AdminDocumentTypeController extends FrameworkBundleAdminController
{
    /**
     * Show DocumentType listing.
     *
     * @AdminSecurity("is_granted(['read', 'update', 'create', 'delete'], request.get('_legacy_controller'))")
     *
     * @param Request $request
     * @param AdminDocumentTypeFilter $filters
     *
     * @return Response
     */
    public function indexAction(Request $request, AdminDocumentTypeFilter $filters)
    {
        $gridFactory = $this->get('fbsample_legacyvsmodern.admindocumenttype.grid_factory');
        $grid = $gridFactory->getGrid($filters);
        $gridPresenter = $this->get('prestashop.core.grid.presenter.grid_presenter');

        return $this->render('@Modules/fbsample_legacyvsmodern/views/templates/admin/AdminDocumentTypeController.index.html.twig', [
            'layoutTitle' => $this->trans('Document type (Modern)', 'Modules.Adddoconorder.Admin'),
            'AdminDocumentTypeGrid' => $gridPresenter->present($grid),
            'enableSidebar' => true,
        ]);
    }

    /**
     * Perform search
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        $definitionFactory = $this->get('fbsample_legacyvsmodern.admindocumenttype.grid_definition_factory');
        $groupDefinition = $definitionFactory->getDefinition();

        $gridFilterFormFactory = $this->get('prestashop.core.grid.filter.form_factory');
        $filtersForm = $gridFilterFormFactory->create($groupDefinition);
        $filtersForm->handleRequest($request);

        $filters = [];
        if ($filtersForm->isSubmitted()) {
            $filters = $filtersForm->getData();
        }
        return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index', ['filters' => $filters]);
    }

    /**
     * @AdminSecurity(
     *     "is_granted(['create'], request.get('_legacy_controller'))",
     *     message="You do not have permission to create this."
     * )
     */
    public function addAction(Request $request)
    {
        $formBuilder = $this->get('fbsample_legacyvsmodern.admindocumenttype.admindocumenttypeform.form_builder');
        $formHandler = $this->get('fbsample_legacyvsmodern.admindocumenttype.admindocumenttypeform.form_handler');
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        try {
            $result = $formHandler->handle($form);
            if ($result->isSubmitted() && $result->isValid()) {
                $this->addFlash('success', $this->trans('Successful created.', 'Modules.Adddoconorder.Admin'));
                return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index');
          }
        } catch (Exception $e) {
            $this->addFlash('error', $this->handleException($e));
            return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index');
        }

        return $this->render('@Modules/fbsample_legacyvsmodern/views/templates/admin/AdminDocumentTypeController.form.html.twig', [
            'layoutTitle' => $this->trans('Document type (Modern)', 'Modules.Adddoconorder.Admin'),
            'requireAddonsSearch' => false,
            'enableSidebar' => true,
            'AdminDocumentTypeForm' => $form->createView(),
        ]);
    }


    /**
     * @AdminSecurity(
     *     "is_granted(['update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to edit this."
     * )
     */
    public function editAction(Int $recordId, Request $request)
    {
        try {
            $formBuilder = $this->get('fbsample_legacyvsmodern.admindocumenttype.admindocumenttypeform.form_builder');
            $formHandler = $this->get('fbsample_legacyvsmodern.admindocumenttype.admindocumenttypeform.form_handler');
            $form = $formBuilder->getFormFor($recordId);
            $form->handleRequest($request);

            $result = $formHandler->handleFor($recordId, $form);
            if ($result->isSubmitted() && $result->isValid()) {
                $this->addFlash('success', $this->trans('Successful update.', 'Modules.Adddoconorder.Admin'));
                return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index');
          }
        } catch (Exception $e) {
            $this->addFlash('error', $this->handleException($e));
            return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index');
        }

        return $this->render('@Modules/fbsample_legacyvsmodern/views/templates/admin/AdminDocumentTypeController.form.html.twig', [
            'layoutTitle' => $this->trans('Document type (Modern)', 'Modules.Adddoconorder.Admin'),
            'requireAddonsSearch' => false,
            'enableSidebar' => true,
            'AdminDocumentTypeForm' => $form->createView(),
        ]);
    }


    /**
     * @AdminSecurity(
     *     "is_granted(['update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to edit this."
     * )
     */
    public function toggleActiveAction(Int $recordId)
    {
        $record = new DocumentType((int)$recordId);
        if (Validate::isLoadedObject($record)) {
            $record->active = !(int) $record->active;
            $record->save();
            $this->addFlash(
                'success',
                $this->trans('The record has been successfully updated.', 'Modules.Adddoconorder.Admin')
            );
        }
        return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index');
    }
    /**
     * @AdminSecurity(
     *     "is_granted(['delete'], request.get('_legacy_controller'))",
     *     message="You do not have permission to delete this."
     * )
     */
    public function deleteAction(Int $recordId)
    {
        $record = new DocumentType((int)$recordId);
        if (Validate::isLoadedObject($record)) {
            if ($record->delete()) {
                $this->addFlash(
                    'success',
                    $this->trans('Delete successful', 'Modules.Adddoconorder.Admin')
                );
            } else {
                    $this->addFlash(
                        'error',
                        $this->trans('Delete failed', 'Modules.Adddoconorder.Admin')
                    );
            }
        } else {
            $this->addFlash(
                'error',
                $this->trans('Internal error: recordId is invalid', 'Modules.Adddoconorder.Admin')
            );
        }
        return $this->redirectToRoute('fbsample_legacyvsmodern_admindocumenttype_index');
    }

    public static function installInBO($menu_entry_title)
    {
        TabManager::addChildTab('AdminDocumentTypeControllerLegacyClass', $menu_entry_title);
        return true;
    }

    public static function removeFromBO()
    {
        TabManager::removeTab('AdminDocumentTypeControllerLegacyClass');
        return true;
    }
}
