(function($) {
  Drupal.behaviors.bxslider = {
    attach: function(context, settings) {

        if(settings.bxslider.buildPager) {
            settings.bxslider.buildPager = new Function('slideIndex', settings.bxslider.buildPager);
            settings.bxslider.pagerCustom = null;
        }

        $('.bxslider').bxSlider( settings.bxslider);
    }
  };
}(jQuery));
