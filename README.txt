BxSlider module integrates the bxSlider library (bxslider.com) with Fields.

Why bxSlider?

    Fully responsive - will adapt to any device
    Horizontal, vertical, and fade modes
    Slides can contain images, video, or HTML content
    Advanced touch / swipe support built-in
    Uses CSS transitions for slide animation (native hardware acceleration!)
    Full callback API and public methods
    Small file size, fully themed, simple to implement
    Browser support: Firefox, Chrome, Safari, iOS, Android, IE7+
    Tons of configuration options


DEPENDENCIES

 BxSlider Library - https://github.com/stevenwanderski/bxslider

INSTALLATION

 1. Download the libraries https://github.com/stevenwanderski/bxslider-4/archive/v4.2.15.zip

    Unzip and put the content of the archive to the /libraries/bxslider
    (create required directories).

    For example, note that the file jquery.bxslider.min.js is accessible by the path
    /libraries/bxslider/dist/jquery.bxslider.min.js.

    Drush users can use the command "drush bxslider-plugin".

 2. Enable the module.

 3. Select some content type, then select 'Manage display' and select a
    formatter "BxSlider" for required images field. Then click to the formatter settings
    for filling BxSlider settings

    Select the formatter "BxSlider - Thumbnail slider", if needed a carouser thumbnail pager.

    For example go to /admin/structure/types/manage/article/display , select a formatter
    BxSlider for an Images field and click 'the gear' at the right side of the page
    for required image field.


MORE

 For development of a carousel thumbnail pager was used
 http://stackoverflow.com/questions/19326160/bxslider-how-is-it-possible-to-make-thumbnails-like-a-carousel

 If needed integration with Views, use BxSlider - Views slideshow integration
 (https://drupal.org/project/bxslider_views_slideshow)
