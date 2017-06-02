(function (Drupal) {

    'use strict';

    Drupal.behaviors.bottomsheet = {
        buttonClass: 'mdl-layout__bottomsheet__button',
        buttonActiveClass: 'mdl-layout__bottomsheet__button--active',
        activeClass: 'mdl-layout__bottomsheet--active',
        $buttons: [],
        getBottomsheet: function($button) {
            var id = $button.getAttribute('for');
            if (!id) throw new Error("Could not find for-attribute at", $button);
            var bottomsheet = document.getElementById(id);
            if (!bottomsheet) throw new Error("Could not find bottomsheet #"+id+" referenced by", $button);
            return bottomsheet;
        },
        setActive: function($button, $bottomsheet) {
            $bottomsheet.classList.add(this.activeClass);
            $button.classList.add(this.buttonActiveClass);
            var icon = $button.querySelector('i.material-icons');
            if (icon) {
                icon.firstChild.closedValue = icon.firstChild.nodeValue;
                icon.firstChild.nodeValue = 'close';
            }
            localStorage.setItem('bottomsheet.'+$bottomsheet.id+'.state', 'active');
        },
        setClosed: function($button, $bottomsheet) {
            $bottomsheet.classList.remove(this.activeClass);
            $button.classList.remove(this.buttonActiveClass);
            var icon = $button.querySelector('i.material-icons');
            if (icon && icon.firstChild.closedValue) {
                icon.firstChild.nodeValue = icon.firstChild.closedValue;
            }
            localStorage.removeItem('bottomsheet.'+$bottomsheet.id+'.state');
        },
        handleButtonClicked: function(event) {
            event.preventDefault();
            var $button = event.target;
            while(!$button.classList.contains(this.buttonClass)) {
                $button = $button.parentNode;
            }
            var $bottomsheet = this.getBottomsheet($button);

            if ($bottomsheet.classList.contains(this.activeClass)) {
                this.setClosed($button, $bottomsheet);
            } else {
                this.setActive($button, $bottomsheet);
            }
            return true;
        },
        attach: function (context, settings) {
            var handler = function(e) { this.handleButtonClicked(e); }.bind(this);
            [].forEach.call(document.getElementsByClassName(this.buttonClass), function($el) {
                this.$buttons[this.$buttons.length] = $el;
                console.log('icon:', $el.querySelector('i.material-icons'));
                $el.setAttribute('data-icon', $el.querySelector('i.material-icons'));
                $el.addEventListener('click', handler);
                $el.addEventListener('touch', handler);
                var bottomsheet = this.getBottomsheet($el);
                var isActive = bottomsheet.classList.contains(this.activeClass);
                var savedState = localStorage.getItem('bottomsheet.'+bottomsheet.id+'.state');
                if (isActive || savedState != null) { this.setActive($el, bottomsheet); }
            }.bind(this));
        }
    };
})(Drupal);
