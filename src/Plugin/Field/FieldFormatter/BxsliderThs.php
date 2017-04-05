<?php

namespace Drupal\bxslider\Plugin\Field\FieldFormatter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Cache\Cache;

/**
 * BxSlider thumbnail pager.
 *
 * @FieldFormatter(
 *  id = "bxslider_ths",
 *  label = @Translation("BxSlider - Thumbnail slider"),
 *  field_types = {"image", "media"}
 * )
 */
class BxsliderThs extends Bxslider implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'thumbnail_slider' => array(
        'thumbnail_style' => 'thumbnail',
        'general' => array(
          'mode' => 'horizontal',
          'speed' => 500,
          'slideMargin' => 0,
          'startSlide' => 0,
          'randomStart' => FALSE,
          'infiniteLoop' => TRUE,
          'hideControlOnEnd' => FALSE,
          'easing' => '',
          'captions' => FALSE,
          'ticker' => FALSE,
          'tickerHover' => FALSE,
          'adaptiveHeight' => FALSE,
          'adaptiveHeightSpeed' => 500,
          'video' => FALSE,
          'responsive' => TRUE,
          'useCSS' => TRUE,
          'preloadImages' => 'visible',
          'touchEnabled' => TRUE,
          'swipeThreshold' => 50,
          'oneToOneTouch' => TRUE,
          'preventDefaultSwipeX' => TRUE,
          'preventDefaultSwipeY' => FALSE,
        ),
        'pager' => array(
          'pager' => FALSE,
        ),
        'controls' => array(
          'controls' => TRUE,
          'nextText' => 'Next',
          'prevText' => 'Prev',
          'nextSelector' => '',
          'prevSelector' => '',
          'autoControls' => FALSE,
          'startText' => 'Start',
          'stopText' => 'Stop',
          'autoControlsCombine' => FALSE,
          'autoControlsSelector' => '',
        ),
        'auto' => array(
          'auto' => FALSE,
          'pause' => 4000,
          'autoStart' => TRUE,
          'autoDirection' => 'next',
          'autoHover' => FALSE,
          'autoDelay' => 0,
        ),
        'carousel' => array(
          'minSlides' => 4,
          'maxSlides' => 4,
          'moveSlides' => 1,
          'slideWidth' => 0,
        ),
      ),
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    // Remove Pager options, because here is used Thumbnail image slider.
    unset($elements['pager']);

    $settings = $this->getSettings();

    $image_styles = image_style_options(FALSE);

    $elements['thumbnail_slider'] = array(
      '#type' => 'fieldset',
      '#title' => t('Thumbnail slider'),
      '#weight' => 10,
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    $elements['thumbnail_slider']['thumbnail_style'] = array(
      '#title' => t('Image style'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['thumbnail_style'],
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
    );

    $elements['thumbnail_slider']['general'] = array(
      '#type' => 'fieldset',
      '#title' => t('General'),
      '#weight' => 1,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $elements['thumbnail_slider']['general']['mode'] = array(
      '#title' => t('Mode'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['general']['mode'],
      '#options' => array(
        'horizontal' => 'horizontal',
        'fade' => 'fade',
      ),
    );
    $elements['thumbnail_slider']['general']['speed'] = array(
      '#title' => t('Speed'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['speed'],
    );
    $elements['thumbnail_slider']['general']['slideMargin'] = array(
      '#title' => t('slideMargin'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['slideMargin'],
    );
    $elements['thumbnail_slider']['general']['startSlide'] = array(
      '#title' => t('startSlide'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['startSlide'],
    );
    $elements['thumbnail_slider']['general']['randomStart'] = array(
      '#type' => 'checkbox',
      '#title' => t('randomStart'),
      '#default_value' => $settings['thumbnail_slider']['general']['randomStart'],
    );
    $elements['thumbnail_slider']['general']['infiniteLoop'] = array(
      '#type' => 'checkbox',
      '#title' => t('infiniteLoop'),
      '#default_value' => $settings['thumbnail_slider']['general']['infiniteLoop'],
    );
    $elements['thumbnail_slider']['general']['hideControlOnEnd'] = array(
      '#type' => 'checkbox',
      '#title' => t('hideControlOnEnd'),
      '#default_value' => $settings['thumbnail_slider']['general']['hideControlOnEnd'],
    );
    $elements['thumbnail_slider']['general']['easing'] = array(
      '#title' => t('easing'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['easing'],
    );
    $elements['thumbnail_slider']['general']['captions'] = array(
      '#type' => 'checkbox',
      '#title' => t('captions'),
      '#default_value' => $settings['thumbnail_slider']['general']['captions'],
    );
    $elements['thumbnail_slider']['general']['ticker'] = array(
      '#type' => 'checkbox',
      '#title' => t('ticker'),
      '#default_value' => $settings['thumbnail_slider']['general']['ticker'],
    );
    $elements['thumbnail_slider']['general']['tickerHover'] = array(
      '#type' => 'checkbox',
      '#title' => t('tickerHover'),
      '#default_value' => $settings['thumbnail_slider']['general']['tickerHover'],
    );
    $elements['thumbnail_slider']['general']['adaptiveHeight'] = array(
      '#type' => 'checkbox',
      '#title' => t('adaptiveHeight'),
      '#default_value' => $settings['thumbnail_slider']['general']['adaptiveHeight'],
    );
    $elements['thumbnail_slider']['general']['adaptiveHeightSpeed'] = array(
      '#title' => t('adaptiveHeightSpeed'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['adaptiveHeightSpeed'],
    );
    $elements['thumbnail_slider']['general']['responsive'] = array(
      '#type' => 'checkbox',
      '#title' => t('responsive'),
      '#default_value' => $settings['thumbnail_slider']['general']['responsive'],
    );
    $elements['thumbnail_slider']['general']['useCSS'] = array(
      '#type' => 'checkbox',
      '#title' => t('useCSS'),
      '#default_value' => $settings['thumbnail_slider']['general']['useCSS'],
    );
    $elements['thumbnail_slider']['general']['preloadImages'] = array(
      '#title' => t('preloadImages'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['general']['preloadImages'],
      '#options' => array(
        'all' => 'all',
        'visible' => 'visible',
      ),
    );
    $elements['thumbnail_slider']['general']['preloadImages'] = array(
      '#type' => 'checkbox',
      '#title' => t('preloadImages'),
      '#default_value' => $settings['thumbnail_slider']['general']['preloadImages'],
    );
    $elements['thumbnail_slider']['general']['swipeThreshold'] = array(
      '#title' => t('swipeThreshold'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['swipeThreshold'],
    );
    $elements['thumbnail_slider']['general']['oneToOneTouch'] = array(
      '#type' => 'checkbox',
      '#title' => t('oneToOneTouch'),
      '#default_value' => $settings['thumbnail_slider']['general']['oneToOneTouch'],
    );
    $elements['thumbnail_slider']['general']['preventDefaultSwipeX'] = array(
      '#type' => 'checkbox',
      '#title' => t('preventDefaultSwipeX'),
      '#default_value' => $settings['thumbnail_slider']['general']['preventDefaultSwipeX'],
    );
    $elements['thumbnail_slider']['general']['preventDefaultSwipeY'] = array(
      '#type' => 'checkbox',
      '#title' => t('preventDefaultSwipeY'),
      '#default_value' => $settings['thumbnail_slider']['general']['preventDefaultSwipeY'],
    );

    $elements['thumbnail_slider']['pager']['pager'] = array(
      '#type' => 'hidden',
      '#default_value' => $settings['thumbnail_slider']['pager']['pager'],
    );

    $elements['thumbnail_slider']['controls'] = array(
      '#type' => 'fieldset',
      '#title' => t('Controls'),
      '#weight' => 3,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $elements['thumbnail_slider']['controls']['controls'] = array(
      '#type' => 'checkbox',
      '#title' => t('controls'),
      '#default_value' => $settings['thumbnail_slider']['controls']['controls'],
    );
    $elements['thumbnail_slider']['controls']['nextText'] = array(
      '#title' => t('nextText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['nextText'],
    );
    $elements['thumbnail_slider']['controls']['prevText'] = array(
      '#title' => t('prevText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['prevText'],
    );
    $elements['thumbnail_slider']['controls']['autoControls'] = array(
      '#type' => 'checkbox',
      '#title' => t('autoControls'),
      '#default_value' => $settings['thumbnail_slider']['controls']['autoControls'],
    );
    $elements['thumbnail_slider']['controls']['startText'] = array(
      '#title' => t('startText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['startText'],
    );
    $elements['thumbnail_slider']['controls']['stopText'] = array(
      '#title' => t('stopText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['stopText'],
    );
    $elements['thumbnail_slider']['controls']['autoControlsCombine'] = array(
      '#type' => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['thumbnail_slider']['controls']['autoControlsCombine'],
    );

    $elements['thumbnail_slider']['auto'] = array(
      '#type' => 'fieldset',
      '#title' => t('Auto'),
      '#weight' => 4,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $elements['thumbnail_slider']['auto']['auto'] = array(
      '#type' => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['thumbnail_slider']['auto']['auto'],
    );
    $elements['thumbnail_slider']['auto']['pause'] = array(
      '#title' => t('pause'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['auto']['pause'],
    );
    $elements['thumbnail_slider']['auto']['autoStart'] = array(
      '#type' => 'checkbox',
      '#title' => t('autoStart'),
      '#default_value' => $settings['thumbnail_slider']['auto']['autoStart'],
    );
    $elements['thumbnail_slider']['auto']['autoDirection'] = array(
      '#title' => t('autoDirection'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['auto']['autoDirection'],
      '#options' => array(
        'next' => 'next',
        'prev' => 'prev',
      ),
    );
    $elements['thumbnail_slider']['auto']['autoHover'] = array(
      '#type' => 'checkbox',
      '#title' => t('autoHover'),
      '#default_value' => $settings['thumbnail_slider']['auto']['autoHover'],
    );
    $elements['thumbnail_slider']['auto']['autoDelay'] = array(
      '#title' => t('autoDelay'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['auto']['autoDelay'],
    );

    $elements['thumbnail_slider']['carousel'] = array(
      '#type' => 'fieldset',
      '#title' => t('Carousel'),
      '#weight' => 5,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $elements['thumbnail_slider']['carousel']['minSlides'] = array(
      '#title' => t('minSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['carousel']['minSlides'],
    );
    $elements['thumbnail_slider']['carousel']['maxSlides'] = array(
      '#title' => t('maxSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['carousel']['maxSlides'],
    );
    $elements['thumbnail_slider']['carousel']['moveSlides'] = array(
      '#title' => t('moveSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['carousel']['moveSlides'],
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $summary[] = t('BxSlider (with thumbnail slider) configuration');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $parent_elements = parent::viewElements($items, $langcode);

    $elements = array();
    $settings = $this->settings;
    $files = $this->getEntitiesToView($items, $langcode);

    // Early opt-out if the field is empty.
    if (empty($files)) {
      return $elements;
    }

    $image_style_setting = $this->getSetting('image_style');

    // Collect cache tags to be added for each item in the field.
    $base_cache_tags = [];
    if (!empty($image_style_setting)) {
      $image_style = $this->imageStyleStorage->load($image_style_setting);
      $base_cache_tags = $image_style->getCacheTags();
    }

    $rendering_ths_items = array();
    foreach ($files as $delta => $file) {
      $cache_contexts = [];
      if (isset($link_file)) {
        $image_uri = $file->getFileUri();
        $url = Url::fromUri(file_create_url($image_uri));
        $cache_contexts[] = 'url.site';
      }
      $cache_tags = Cache::mergeTags($base_cache_tags, $file->getCacheTags());

      $rendering_ths_items[] = $file->_referringItem;

    }

    $bxslider_settings = array_merge(
      $settings['general'],
      $settings['pager'],
      $settings['controls'],
      $settings['auto'],
      $settings['carousel']
    );
    $bxslider_settings['image_style'] = $settings['image_style'];
    $bxslider_settings['slider_id'] = $items->getName();

    $bxslider_settings['thumbnail_slider_settings'] = array_merge(
      $settings['thumbnail_slider']['general'],
      $settings['thumbnail_slider']['pager'],
      $settings['thumbnail_slider']['controls'],
      $settings['thumbnail_slider']['auto'],
      $settings['thumbnail_slider']['carousel']
    );
    $bxslider_settings['thumbnail_slider']['thumbnail_style'] = $settings['thumbnail_slider']['thumbnail_style'];
    // Get thumbnail's width.
    $image_style_ths = $this->imageStyleStorage->load($settings['thumbnail_slider']['thumbnail_style']);
    foreach ($image_style_ths->getEffects() as $effect) {
      $thumbnail_width = $effect->configuration['width'];
    }
    $bxslider_settings['thumbnail_slider']['slideWidth'] = $thumbnail_width;

    $element = array(
      '#theme' => 'bxslider_ths',
      '#items' => $parent_elements['#items'],
      '#thumbnail_items' => $rendering_ths_items,
      '#settings' => $bxslider_settings,
      '#cache' => array(
        'tags' => $cache_tags,
        'contexts' => $cache_contexts,
      ),
    );

    // Attach library.
    $element['#attached']['library'][] = 'bxslider/jquery.bxslider_ths';

    // Attach settings.
    $element['#attached']['drupalSettings']['bxslider_ths'][$bxslider_settings['slider_id']] = $bxslider_settings;

    return $element;
  }

}
