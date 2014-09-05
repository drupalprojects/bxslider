(function($) {
  Drupal.behaviors.bxslider_ths = {
    attach: function(context, settings) {

        var slider_id = settings.bxslider_ths.slider_id;

        settings.bxslider_ths.slider_settings.onSlideBefore = function($slideElement, oldIndex, newIndex){
            changeRealThumb(realThumbSlider,newIndex);

        }
        var realSlider = $('#' + slider_id + ' .bxslider').show().bxSlider(settings.bxslider_ths.slider_settings);

        var realThumbSlider = $('#' + slider_id + " .bxslider-ths").show().bxSlider(settings.bxslider_ths.thumbnail_slider_settings);

        linkRealSliders(realSlider, realThumbSlider);

        $('#' + slider_id + ' .bxslider-ths').find('li[slideIndex="0"]').addClass("active");

        if($('#' + slider_id + " .bxslider-ths li").length <= settings.bxslider_ths.thumbnail_slider_settings.maxSlides) {
            $('#' + slider_id + " .bxslider-ths .bx-next").hide();
        }

        function linkRealSliders(bigS, thumbS){

            $('#' + slider_id + " ul.bxslider-ths").on("click", "a", function(event) {
                event.preventDefault();
                var newIndex = $(this).parent().attr("slideIndex");
                bigS.goToSlide(newIndex);
            });
        }

        function changeRealThumb(slider, newIndex) {

            var $thumbS = $('#' + slider_id + ' .bxslider-ths');
            $thumbS.find('.active').removeClass("active");
            $thumbS.find('li[slideIndex="' + newIndex + '"]').addClass("active");

            if(slider.getSlideCount() - newIndex >= settings.bxslider_ths.thumbnail_slider_settings.maxSlides) slider.goToSlide(newIndex);
            else slider.goToSlide(slider.getSlideCount() - settings.bxslider_ths.thumbnail_slider_settings.maxSlides);
        }

    }
  };
}(jQuery));
