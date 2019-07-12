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

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\TranslateType;
use PrestaShopBundle\Form\Admin\Type\TextWithUnitType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;

class AdminDocumentTypeFormType extends TranslatorAwareType
{
    private $defaultCurrencyIsoCode;

    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        $defaultCurrencyIsoCode
    ) {
        parent::__construct($translator, $locales);
        $this->defaultCurrencyIsoCode = $defaultCurrencyIsoCode;
    }

   /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TranslatableType::class, [
                'required' => true,
                'label' => $this->trans('Name ', 'Module.fbsample_legacyvsmodern.Admin'),
            ])
            ->add('maxsize', TextWithUnitType::class, [
                'label' => $this->trans('Max file size', 'Module.fbsample_legacyvsmodern.Admin'),
                'unit' => 'Ko',
                'help' => $this->trans(
                    'Enter 0 to disable the maximum size control',
                    'Module.fbsample_legacyvsmodern.Admin'
                )
            ])
            ->add('active', SwitchType::class, [
                'label' => $this->trans('Active', 'Module.fbsample_legacyvsmodern.Admin'),
            ])
            ->add('description', TranslateType::class, [
                'label' => $this->trans('Description', 'Module.fbsample_legacyvsmodern.Admin'),
                'type' => FormattedTextareaType::class,
                'locales' => $this->locales,
                'hideTabs' => false,
                'options' => [
                    'constraints' => [
                        new CleanHtml([
                            'message' => $this->trans(
                                '%s is invalid.',
                                'Admin.Notifications.Error'
                            ),
                        ]),
                    ],
                ],
            ])
            ->add('id_documenttype', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => $this->trans('Save', 'Module.fbsample_legacyvsmodern.Admin'),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'Module.fbsample_legacyvsmodern.Admin',
        ]);
    }
}
