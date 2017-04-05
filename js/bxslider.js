(function ($) {
  Drupal.behaviors.bxslider = {
    attach: function (context, settings) {

      if (!settings.bxslider || context == '#cboxLoadedContent') {
        return;
      }
      for (var slider_id in settings.bxslider) {
//        $('#' + slider_id, context).once('bxslider-' + slider_id, function () {

          if (settings.bxslider[slider_id].buildPager) {
              settings.bxslider[slider_id].buildPager = new Function('slideIndex', settings.bxslider.buildPager);
              settings.bxslider[slider_id].pagerCustom = null;
          }
          $('#' + slider_id + ' .bxslider', context).show().bxSlider(settings.bxslider[slider_id]);

//        });
      }
    }
  };
}(jQuery));
