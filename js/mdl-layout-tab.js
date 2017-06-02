(function (Drupal) {

    'use strict';

    Drupal.behaviors.mdl_layout_tab = {
        $tabs: {},
        storageName: document.location.pathname.sub(1).replace(new RegExp('/', 'g'), '.'),
        handleButtonClicked: function(event) {

            var $el = event.target;
            while(!$el.classList.contains('mdl-layout__tab')) {
                $el = $el.parentNode;
            }
            var tab_id = this.getId($el);
            localStorage.setItem(this.storageName, tab_id);
        },
        getId: function($el) {
            return $el.getAttribute('href').substring(1);
        },
        attach: function (context, settings) {
            var activeTab = localStorage.getItem(this.storageName);

            var handler = function(e) { console.log(e); this.handleButtonClicked(e); }.bind(this);
            [].forEach.call(document.getElementsByClassName('mdl-layout__tab'), function($el) {
                var tab_id = this.getId($el);
                this.$tabs[tab_id] = $el;
                $el.addEventListener('click', handler);
                $el.addEventListener('touch', handler);

                // activate this tab, if it isn't already
                var isActive = $el.classList.contains('is-active');
                var $panel = document.getElementById(tab_id);

                if (activeTab && tab_id == activeTab && !isActive) {
                    $el.classList.add('is-active');
                    $panel.classList.add('is-active');
                } else if (activeTab && tab_id != activeTab && isActive) {
                    $el.classList.remove('is-active');
                    $panel.classList.remove('is-active');
                }

            }.bind(this));
        }
    };
})(Drupal);
