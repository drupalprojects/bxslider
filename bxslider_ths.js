(function($) {
  Drupal.behaviors.bxslider_ths = {
    attach: function(context, settings) {

        settings.bxslider_ths.slider_settings.onSlideBefore = function($slideElement, oldIndex, newIndex){
            changeRealThumb(realThumbSlider,newIndex);

        }
        var realSlider = $('#' + settings.bxslider_ths.slider_id + ' .bxslider').bxSlider(settings.bxslider_ths.slider_settings);

        settings.bxslider_ths.thumbnail_slider_settings.onSlideBefore = function($slideElement, oldIndex, newIndex){
            $('#' + settings.bxslider_ths.slider_id + " ul.bxslider .active").removeClass("active");
            $slideElement.addClass("active");

        }
        var realThumbSlider = $('#' + settings.bxslider_ths.slider_id + " .bxslider-ths").bxSlider(settings.bxslider_ths.thumbnail_slider_settings);


        linkRealSliders(realSlider, realThumbSlider);

        if($('#' + settings.bxslider_ths.slider_id + " .bxslider-ths li").length <= settings.bxslider_ths.thumbnail_slider_settings.maxSlides) {
            $('#' + settings.bxslider_ths.slider_id + " .bxslider-ths .bx-next").hide();
        }

        function linkRealSliders(bigS, thumbS){

            $('#' + settings.bxslider_ths.slider_id + " ul.bxslider-ths").on("click", "a", function(event) {
                event.preventDefault();
                var newIndex = $(this).parent().attr("slideIndex");
                bigS.goToSlide(newIndex);
            });
        }

        function changeRealThumb(slider, newIndex) {

            var $thumbS = $('#' + settings.bxslider_ths.slider_id + ' .bxslider');
            $thumbS.find('.active').removeClass("active");
            $thumbS.find('li[slideIndex="' + newIndex + '"]').addClass("active");

            if(slider.getSlideCount() - newIndex >= settings.bxslider_ths.thumbnail_slider_settings.maxSlides) slider.goToSlide(newIndex);
            else slider.goToSlide(slider.getSlideCount() - settings.bxslider_ths.thumbnail_slider_settings.maxSlides);
        }

    }
  };
}(jQuery));
