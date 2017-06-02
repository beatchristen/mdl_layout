(function (Drupal) {

    'use strict';

    Drupal.behaviors.mdl_layout_bottomsheet = {
        $edit_actions: false,
        $main: false,
        $parents: {},

        attach: function (context, settings) {

            // locate the hopefully first <main> element...
            this.$main = document.querySelector('[role="main"]');
            if (!this.$main) {
                throw new Error('could not find an element with main role');
            }

            [].forEach.call(document.querySelectorAll('[data-mdl-layout-bottomsheet-id]'), function($el) {
                var parentId = $el.getAttribute('data-mdl-layout-bottomsheet-id');
                if (parentId && !this.$parents[parentId]) {
                    this.$parents[parentId] = document.getElementById(parentId);
                    this.$main.parentNode.insertBefore(this.$parents[parentId], this.$main.nextSibling);

                    this.$parents[parentId].classList.add('mdl-layout__bottomsheet-loser');
                    this.$parents[parentId].classList.add('mdl-layout__bottomsheet--inset-persistent');
                }
                // move this element after the parent element
                this.$parents[parentId].appendChild($el);
            }.bind(this));
        }
    };
})(Drupal);
