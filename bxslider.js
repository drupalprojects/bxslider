(function($) {
  Drupal.behaviors.bxslider = {
    attach: function(context, settings) {

      $('.bxslider').bxSlider(settings.bxslider);
    }
  };
}(jQuery));
