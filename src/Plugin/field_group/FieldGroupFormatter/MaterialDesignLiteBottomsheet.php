<?php

namespace Drupal\mdl_layout\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormState;
use Drupal\Core\Render\Element;
use Drupal\field_group\Annotation\FieldGroupFormatter;
use Drupal\field_group\FieldGroupFormatterBase;
use Drupal\mdl_layout\Element\MdlLayoutBottomsheet;

/**
 * Plugin implementation of the 'inset persistent bottomsheet' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "mdl_layout_bottomsheet",
 *   label = @Translation("MDL bottomsheet"),
 *   description = @Translation("This fieldgroup renders the content inside a fixed region at the bottom of the page."),
 *   supported_contexts = {
 *     "form",
 *     "view",
 *   },
 * )
 */
class MaterialDesignLiteBottomsheet extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function preRender(&$element, $rendering_object) {
    parent::preRender($element, $rendering_object);

    $element['#id'] = Html::getId($this->getSetting('id'));

    $classes = $this->getClasses();
    $element += array(
      '#tree' => TRUE,
      '#parents' => array($this->group->group_name),
      '#default_tab' => '',
      '#attributes' => [ 'id' => $element['#id'], 'class' => $classes],
    );
    $element['#attached']['library'][] = 'mdl_layout/bottomsheet';

    // By default tabs don't have titles but you can override it in the theme.
    if ($this->getLabel()) {
      $element['#title'] = Html::escape($this->getLabel());
    }

    $form_state = new FormState();

    $element += array(
      '#theme_wrappers' => array('mdl_layout_bottomsheet'),
    );
    $on_form = $this->context == 'form';
    $element['#style'] = $this->getSetting('style');
    $element = MdlLayoutBottomsheet::processTabs($element, $form_state, $on_form);

    // Make sure the group has 1 child. This is needed to succeed at form_pre_render_vertical_tabs().
    // Skipping this would force us to move all child groups to this array, making it an un-nestable.
    $element['group']['#groups'][$this->group->group_name] = array(0 => array());
    $element['group']['#groups'][$this->group->group_name]['#group_exists'] = TRUE;

    $imports = $this->getSetting('import');
    foreach ($imports as $import) {
      if (!empty($import)) {
        $element[$import]['#parent'] = $this->group->group_name;
      }
    }

    $element['#icon'] = $this->getSetting('button_icon');

    // Search for all children and create a referencing tab for it
    foreach (Element::children($element) as $tab_name) {
      $id = $element[$tab_name]['#id'];
      if (empty($id)) continue;
      $href = '#' . $id;
      $label = $element[$tab_name]['#title'];
      if (empty($label)) continue;
      $element['group']['#items'][$href] = $label;

      // add a theme wrapper for a panel
      $element[$tab_name]['#theme_wrappers']['mdl_layout_tab_panel'] = [
        '#attributes' => [
          'id' => $id,
          'class' => ['mdl-tabs__panel'],
        ],
      ];
    }
  }
  /**
   * {@inheritdoc}
   */
  public function settingsForm() {

    $form = parent::settingsForm();
    $form['id']['#required'] = 1;

    $form['import'] = array(
      '#title' => t('Import form elements'),
      '#type' => 'checkboxes',
      '#options' => [
        'actions' => 'Actions',
        'advanced' => 'Revision information',
      ],
      '#default_value' => $this->getSetting('import'),
      '#weight' => 10,
    );

    $form['button_icon'] = array(
      '#title' => t('Button icon'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('button_icon'),
      '#weight' => 10,
    );

    $form['style'] = array(
      '#title' => t('Bottomsheet style'),
      '#type' => 'select',
      '#options' => [
        MdlLayoutBottomsheet::NORMAL_STYLE => 'Normal bottomsheet',
        MdlLayoutBottomsheet::INSET_PERSISTENT_STYLE => 'Inset persistent bottomsheet',
        MdlLayoutBottomsheet::TABBAR_STYLE => 'Bottomsheet with Tabs',
      ],
      '#default_value' => $this->getSetting('style'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {

    $summary = parent::settingsSummary();

    if ($this->getSetting('import')) {
      $summary[] = 'Import '.join("/", $this->getSetting('import'));
    }

    if ($this->getSetting('button_icon')) {
      $summary[] = 'Icon: '. $this->getSetting('button_icon');
    }

    $summary[] = 'Style: ' . $this->getSetting('style');

    return $summary;
  }


  /**
   * {@inheritdoc}
   */
  public function getClasses() {
    $style = $this->getSetting('style');
    $classes = parent::getClasses();
    if ($style == MdlLayoutBottomsheet::TABBAR_STYLE) {
      $classes[] = 'mdl-layout__bottomsheet';
      $classes[] = $style;
    }
    else if ($style == MdlLayoutBottomsheet::INSET_PERSISTENT_STYLE) {
      $classes[] = 'mdl-mini-footer';
      $classes[] = $style;
    } else {
      $classes[] = 'mdl-layout__bottomsheet';
      $classes[] = 'mdl-layout__bottomsheet--normal';
      $classes[] = 'mdl-layout__bottomsheet--active';
    }
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';

    return $classes;
  }

}
