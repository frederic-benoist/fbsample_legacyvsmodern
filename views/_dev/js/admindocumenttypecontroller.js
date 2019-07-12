/* global $, prestashop */
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

// Use this file to customize AdminDocumentTypeController

import Grid from './components/grid/grid';
import FiltersResetExtension from "./components/grid/extension/filters-reset-extension";
import SortingExtension from "./components/grid/extension/sorting-extension";
import ReloadListExtension from "./components/grid/extension/reload-list-extension";
import ColumnTogglingExtension from "./components/grid/extension/column-toggling-extension";
import ExportToSqlManagerExtension from "./components/grid/extension/export-to-sql-manager-extension";
import TranslatableInput from './components/translatable-input';
import ChoiceTable from './components/choice-table';
import FormSubmitButton from './components/form-submit-button';
import TaggableField from './components/taggable-field';
import TextWithRecommendedLengthCounter from './components/form/text-with-recommended-length-counter';
import TranslatableField from './components/translatable-field';
import TinyMCEEditor from './components/tinymce-editor';

const $ = window.$;

$(document).ready(() => {
  const theGrid = new Grid('admindocumenttypegrid');

  theGrid.addExtension(new FiltersResetExtension());
  theGrid.addExtension(new SortingExtension());
  theGrid.addExtension(new ReloadListExtension());
  theGrid.addExtension(new ExportToSqlManagerExtension());
  theGrid.addExtension(new ColumnTogglingExtension());

  const translatorInput = new TranslatableInput();

  new ChoiceTable();
  new FormSubmitButton();
  new TaggableField({
    tokenFieldSelector: 'input.js-taggable-field',
    options: {
      createTokensOnBlur: true,
    },
  });
  new TextWithRecommendedLengthCounter();
  new TranslatableField();
  new TinyMCEEditor();



});

