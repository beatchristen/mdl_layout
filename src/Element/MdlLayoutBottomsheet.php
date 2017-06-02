<?php
namespace Drupal\mdl_layout\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Annotation\FormElement;
use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for one MDL-layout bottomsheet.
 *
 * Formats all child details and all non-child details whose #group is
 * assigned this element's name as horizontal tabs.
 *
 * @FormElement("mdl_layout_bottomsheet")
 */
class MdlLayoutBottomsheet extends RenderElement {
  const NORMAL_STYLE = 'mdl-layout__bottomsheet--normal';
  const INSET_PERSISTENT_STYLE = 'mdl-layout__bottomsheet--inset-persistent';
  const TABBAR_STYLE = 'mdl-layout__bottomsheet--tabbar';

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

    $theme_wrappers = [];
    if ($element['#style']==self::TABBAR_STYLE) {
      $theme_wrappers[] = 'mdl_tabs_tabbar';
    }

    // Inject a new details as child, so that form_process_details() processes
    // this details element like any other details.
    $element['group'] = array(
      '#type' => 'mdl_layout_bottomsheet', //'mdl_tab_panel',
      '#theme_wrappers' => $theme_wrappers,
      '#parents' => $element['#parents'],
    );

    // Add an invisible label for accessibility.
    if (!isset($element['#title'])) {
      $element['#title'] = t('MDL Layout Bottomsheet');
      $element['#title_display'] = 'invisible';
    }

    return $element;
  }

}
