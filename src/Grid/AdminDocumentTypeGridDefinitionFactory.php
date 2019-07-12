<?php
/**
 * 2013-2019 Frédéric BENOIST
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license.
 * Use, copy, modification or distribution of this source file without written
 * license agreement from Frédéric BENOIST is strictly forbidden.
 *
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de Frédéric BENOIST est
 * expressement interdite.
 *
 *  @author    Frédéric BENOIST
 *  @copyright 2013-2019 Frédéric BENOIST <https://www.fbenoist.com>
 *  @license   Commercial License
 */

namespace FBenoist\FbSampleLegacyVsModern\Grid;

use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;

use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class AdminDocumentTypeGridDefinitionFactory
 */
final class AdminDocumentTypeGridDefinitionFactory extends AbstractGridDefinitionFactory
{
   /**
     * @var string
     */
    private $resetFiltersUrl;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @param string $resetFiltersUrl
     * @param string $redirectUrl
     */
    public function __construct(
        HookDispatcherInterface $hookDispatcher = null,
        $resetFiltersUrl,
        $redirectUrl
    ) {
        $this->resetFiltersUrl = $resetFiltersUrl;
        $this->redirectUrl = $redirectUrl;
        parent::__construct($hookDispatcher);
    }

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return 'admindocumenttypegrid';
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('DocumentType', [], 'Module.fbsample_legacyvsmodern.Admin');
    }

   /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id_documenttype'))
                ->setName($this->trans('ID', [], 'Modules.fbsample_legacyvsmodern.Admin'))
                ->setOptions([
                    'field' => 'id_documenttype',
                ])
            )
            ->add(
                (new DataColumn('name'))
                  ->setName($this->trans('Name', [], 'Modules.fbsample_legacyvsmodern.Admin'))
                  ->setOptions([
                      'field' => 'name',
                  ])
            )
            ->add(
                (new ToggleColumn('active'))
                ->setName($this->trans('Active', [], 'Modules.fbsample_legacyvsmodern.Admin'))
                ->setOptions([
                    'field' => 'active',
                    'primary_field' => 'id_documenttype',
                    'route' => 'fbsample_legacyvsmodern_admindocumenttype_active_toggle',
                    'route_param_name' => 'recordId',
                ])
            )
            ->add(
                (new DataColumn('maxsize'))
                    ->setName($this->trans('Max file size (Ko)', [], 'Modules.fbsample_legacyvsmodern.Admin'))
                    ->setOptions([
                      'field' => 'maxsize',
                    ])
            )
            ->add(
                (new ActionColumn('actions'))
                    ->setName($this->trans('Actions', [], 'Admin.Actions'))
                    ->setOptions([
                        'actions' => $this->getRowActions(),
                    ])
            )
        ;
    }

   /**
     * {@inheritdoc}
     *
     * Define filters and associate them with columns.
     * Note that you can add filters that are not associated with any column.
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('id_documenttype', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('id_documenttype')
            )
            ->add(
                (new Filter('name', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('name')
            )
            ->add(
                (new Filter('active', YesAndNoChoiceType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('active')
            )
            ->add(
                (new Filter('maxsize', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                    ])
                    ->setAssociatedColumn('maxsize')
            )

            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setTypeOptions([
                        'attr' => [
                            'data-url' => $this->resetFiltersUrl,
                            'data-redirect' => $this->redirectUrl,
                        ],
                    ])
                    ->setAssociatedColumn('actions')
            )

        ;
    }

    /**
     * {@inheritdoc}
     *
     * Here we define what actions our products grid will have.
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add(
                (new SimpleGridAction('common_refresh_list'))
                    ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                    ->setIcon('refresh')
            )
            ->add(
                (new SimpleGridAction('common_show_query'))
                    ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                    ->setIcon('code')
            )
            ->add(
                (new SimpleGridAction('common_export_sql_manager'))
                    ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                    ->setIcon('storage')
            )
        ;
    }

    /**
     * Extracted row action definition into separate method.
     */
    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add(
                (new LinkRowAction('edit'))
                    ->setName($this->trans('Edit', [], 'Admin.Actions'))
                    ->setOptions([
                        'route' => 'fbsample_legacyvsmodern_admindocumenttype_edit',
                        'route_param_name' => 'recordId',
                        'route_param_field' => 'id_documenttype',
                    ])
                    ->setIcon('edit')
            )
            ->add(
                (new LinkRowAction('delete'))
                    ->setName($this->trans('Delete', [], 'Admin.Actions'))
                    ->setOptions([
                        'route' => 'fbsample_legacyvsmodern_admindocumenttype_delete',
                        'route_param_name' => 'recordId',
                        'route_param_field' => 'id_documenttype',
                    ])
                    ->setIcon('delete')
            )
        ;
    }
}
