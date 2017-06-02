<?php


/**
 * Prepares variables for mdl-layout__tab-panel element templates.
 *
 * Default template: mdl-layout-tab-panel.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #attributes, #children, #open,
 *     #description, #id, #title, #value, #optional.
 */
function template_preprocess_mdl_layout_tab_panel(&$variables) {
  $element = $variables['element'];
  $variables['attributes'] = $element['#attributes'];

  $variables['description'] = (!empty($element['#description'])) ? $element['#description'] : '';
  $variables['children'] = (isset($element['#children'])) ? $element['#children'] : '';
  $variables['value'] = (isset($element['#value'])) ? $element['#value'] : '';
  $variables['required'] = !empty($element['#required']) ? $element['#required'] : NULL;

  // Suppress error messages.
  $variables['errors'] = NULL;
}

/**
 * Prepares variables for mdl-layout__tabbar element templates.
 */
function template_preprocess_mdl_layout_tabbar(&$variables) {
  $element = $variables['element'];
  $variables['items'] = $element['#items'];
}

function template_preprocess_mdl_tabs_tabbar(&$variables) {
  $element = $variables['element'];
  $variables['items'] = $element['#items'];
  $variables['#attached']['library'][] = 'mdl_layout/tabs_tabbar';
}

/**
 * Prepares variables for bottomsheet templates.
 *
 * Default template: bottomsheet.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the details element. Properties used: #children, #icon.
 */
function template_preprocess_mdl_layout_bottomsheet(&$variables) {
  $element = $variables['element'];
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['attributes'] = $element['#attributes'];
  $variables['style'] = $element['#style'];
  $variables['id'] = $element['#id'];

  if (!isset($variables['attributes']['class'])) {
    $variables['attributes']['class'] = [];
  }

  $button_attributes = [
    'for' => $element['#attributes']['id'],
    'class' => [
      'mdl-layout__bottomsheet__button',
      'mdl-button',
      'mdl-js-button',
      'mdl-button--fab',
      'mdl-js-ripple-effect',
      'mdl-button--colored'
    ],
  ];

  $variables['button_attributes'] = new \Drupal\Core\Template\Attribute($button_attributes);
  $variables['button_icon'] = isset($element['#icon']) ? $element['#icon'] : 'settings';

  $element['#attached']['library'][] = 'mdl_layout/bottomsheet';
}
