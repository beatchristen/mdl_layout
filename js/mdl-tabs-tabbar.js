(function (Drupal) {

    'use strict';

    Drupal.behaviors.mdl_tabs_tabbar = {
        tabBarClass: 'mdl-tabs__tab-bar',
        tabClass: 'mdl-tabs__tab',
        $tabs: {},
        storageName: document.location.pathname.sub(1).replace(new RegExp('/', 'g'), '.'),
        handleButtonClicked: function(event) {


        },
        getItem: function($storageName) {
            var key = 'mdl-tabs.tab-bar.' + $storageName.id;
            console.log('getItem: '+key);
            return localStorage.getItem(key);
        },
        setItem: function($storageName, value) {
            var key = 'mdl-tabs.tab-bar.' + $storageName.id;
            console.log('setItem: '+key+":== "+value);
            return localStorage.setItem(key, value);
        },
        createHandler: function($tabbar, $storageName) {
            return function(event) {
                var $el = event.target;
                while(!$el.classList.contains('mdl-tabs__tab')) {
                    $el = $el.parentNode;
                }
                var tab_id = $el.href.split('#')[1];
                this.setItem($storageName, tab_id);
            }.bind(this);
        },
        attach: function (context, settings) {

            [].forEach.call(document.getElementsByClassName(this.tabBarClass), function($tabbar) {
                // find a storage name in the document structure
                var $storageName = $tabbar;
                while (!$storageName.hasAttribute('id') && $storageName.parentNode) $storageName = $storageName.parentNode;

                // register callbacks for this tab-bar
                var handler = this.createHandler($tabbar, $storageName);
                $tabbar.addEventListener('click', handler);
                $tabbar.addEventListener('touch', handler);

                var savedActiveTab = this.getItem($storageName);
                if (!savedActiveTab) return;

                // activate this tab, if it isn't already
                var $activeTab = $tabbar.querySelector('.is-active.'+this.tabClass);
                if ($activeTab) {
                    var activeId = $activeTab.href.split('#')[1];
                    if (savedActiveTab != activeId) {
                        $activeTab.classList.remove('is-active');
                        var $panel = document.getElementById(activeId);
                        $panel.classList.remove('is-active');
                    }
                }

                // set the saved active tab
                [].forEach.call($tabbar.querySelectorAll('.mdl-tabs__tab'), function($tab) {
                    var id = $tab.href.split('#')[1];
                    var hasActive = $tab.classList.contains('is-active');
                    if (id==savedActiveTab && !hasActive) return $tab.classList.add('is-active');
                    if (id!=savedActiveTab && hasActive) return $tab.classList.remove('is-active');
                });

                var $savedPanel = document.getElementById(savedActiveTab);
                $savedPanel.classList.add('is-active');
            }.bind(this));
        }
    };
})(Drupal);
