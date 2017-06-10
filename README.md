# MDL Layout components

Material Design Lite Layout components integrated with Drupal 8.

- see https://getmdl.io/components/index.html#layout-section for more information about MDL
- see https://drupal.org/ for more information about Drupal.
- tested with [d8mdl theme](https://github.com/andreapaiola/d8mdl)

##  Scrollable Tabs

- **adds new Display Manager region 'MDL Layout Tab Panel':** 
  Use this field_group region type for view and form mode
  and place fields inside.
- **client side:** 
  Remembers the tab that was clicked last, so when you return to the page, it will reactivate the tab again.

## MDL Bottomsheet
### Standard bottomsheet
### Bottomsheet with tab bar
    [ ] save state closed/active in localstorage
    [ ] allow setting the icon for a tab like projectgroup
        @see profiles/uipedia/modules/projectgroup/templates/contentlist/bottomsheet.html.twig
    [ ] migrate node-view template to mdl-tabs

### Insent, persistent bottomsheet


# TODO
    [ ] scrub for publishing

# BUGS
    [ ] vertical-tabs theme-wrapper eliminieren
    @see function mdl_layout_form_alter(&$form, FormStateInterface $form_state, $form_id);
