$(document).ready(function() {

    $('.animate-element').bind('inview', function() {
        var $this = $(this),
            duration = $this.data('duration') || 750;

        if ( $this.hasClass('from-left') ) { $this.animate({left: 0, opacity: 1}, duration); }
        if ( $this.hasClass('from-right') ) { $this.animate({right: 0, opacity: 1}, duration); }
        if ( $this.hasClass('from-top') ) { $this.animate({top: 0, opacity: 1}, duration); }
        if ( $this.hasClass('from-bottom') ) { $this.animate({bottom: 0, opacity: 1}, duration); }
        if ( $this.hasClass('appear') ) { $this.animate({opacity: 1}); }
        if ( $this.hasClass('lift') ) { $this.animate({bottom: 0, opacity: 1}, duration); }
        if ( $this.hasClass('drop') ) { $this.animate({top: 0, opacity: 1}, duration); }
    });

});