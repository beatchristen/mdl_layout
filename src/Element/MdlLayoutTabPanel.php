<?php
namespace Drupal\mdl_layout\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Annotation\FormElement;
use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for one MDL-layout tab-panel.
 *
 * Formats all child details and all non-child details whose #group is
 * assigned this element's name as horizontal tabs.
 *
 * @FormElement("mdl_layout_tab_panel")
 */
class MdlLayoutTabPanel extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);

    return array(
      '#default_tab' => '',
      '#process' => array(
        array($class, 'processTabs'),
      ),
    );
  }

  /**
   * Creates a group formatted as mdl tabs.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   details element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param bool $on_form
   *   Are the tabs rendered on a form or not.
   *
   * @return array
   *   The processed element.
   */
  public static function processTabs(&$element, FormStateInterface $form_state, $on_form = TRUE) {

    // Inject a new details as child, so that form_process_details() processes
    // this details element like any other details.
    $element['group'] = array(
      '#type' => 'mdl_layout_tab_panel', //'mdl_tab_panel',
      '#theme_wrappers' => array('mdl_layout_tab_panel'),
      '#parents' => $element['#parents'],
    );

    // Add an invisible label for accessibility.
    if (!isset($element['#title'])) {
      $element['#title'] = t('MDL Layout Tab Panel');
      $element['#title_display'] = 'invisible';
    }

    return $element;
  }

}
