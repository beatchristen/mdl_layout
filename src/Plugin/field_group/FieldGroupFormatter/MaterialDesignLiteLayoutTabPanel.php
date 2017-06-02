<?php

namespace Drupal\mdl_layout\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Form\FormState;
use Drupal\field_group\Annotation\FieldGroupFormatter;
use Drupal\field_group\FieldGroupFormatterBase;
use Drupal\mdl_layout\Element\MdlLayoutTabPanel;

/**
 * Plugin implementation of the 'tab' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "mdl_layout_tab_panel",
 *   label = @Translation("MDL Layout Tab Panel"),
 *   description = @Translation("This fieldgroup renders the content inside a Tab panel, linked by a tabbar in the waterfall-region."),
 *   supported_contexts = {
 *     "form",
 *     "view",
 *   },
 * )
 */
class MaterialDesignLiteLayoutTabPanel extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function preRender(&$element, $rendering_object) {
    parent::preRender($element, $rendering_object);

    $element['#id'] = Html::getId($this->getSetting('id'));

    $classes = $this->getClasses();
    if ($element['#id']=='screens') $classes[] = 'is-active';
    $element += array(
      '#tree' => TRUE,
      '#parents' => array($this->group->group_name),
      '#default_tab' => '',
      '#attributes' => [ 'id' => $element['#id'], 'class' => $classes],
    );

    // By default tabs don't have titles but you can override it in the theme.
    if ($this->getLabel()) {
      $element['#title'] = Html::escape($this->getLabel());
    }

    $form_state = new FormState();

    $element += array(
      '#type' => 'mdl_layout_tab_panel',
      '#theme_wrappers' => array('mdl_layout_tab_panel'),
    );
    $on_form = $this->context == 'form';
    $element = MdlLayoutTabPanel::processTabs($element, $form_state, $on_form);

    // Make sure the group has 1 child. This is needed to succeed at form_pre_render_vertical_tabs().
    // Skipping this would force us to move all child groups to this array, making it an un-nestable.
    $element['group']['#groups'][$this->group->group_name] = array(0 => array());
    $element['group']['#groups'][$this->group->group_name]['#group_exists'] = TRUE;
  }
  /**
   * {@inheritdoc}
   */
  public function settingsForm() {

    $form = parent::settingsForm();
    $form['id']['#required'] = 1;

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function getClasses() {

    $classes = parent::getClasses();
    $classes[] = 'mdl-layout__tab-panel';
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';
    $classes[] = 'mdl-js-ripple-effect';

    return $classes;
  }

}
