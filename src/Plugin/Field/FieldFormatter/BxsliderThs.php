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
    return [
      'thumbnail_slider' => [
        'thumbnail_style' => 'thumbnail',
        'general' => [
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
        ],
        'pager' => [
          'pager' => FALSE,
        ],
        'controls' => [
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
        ],
        'auto' => [
          'auto' => FALSE,
          'pause' => 4000,
          'autoStart' => TRUE,
          'autoDirection' => 'next',
          'autoHover' => FALSE,
          'autoDelay' => 0,
        ],
        'carousel' => [
          'minSlides' => 4,
          'maxSlides' => 4,
          'moveSlides' => 1,
          'slideWidth' => 0,
        ],
      ],
    ] + parent::defaultSettings();
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

    $elements['thumbnail_slider'] = [
      '#type' => 'fieldset',
      '#title' => t('Thumbnail slider'),
      '#weight' => 10,
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $elements['thumbnail_slider']['thumbnail_style'] = [
      '#title' => t('Image style'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['thumbnail_style'],
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
    ];

    $elements['thumbnail_slider']['general'] = [
      '#type' => 'fieldset',
      '#title' => t('General'),
      '#weight' => 1,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['thumbnail_slider']['general']['mode'] = [
      '#title' => t('Mode'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['general']['mode'],
      '#options' => [
        'horizontal' => 'horizontal',
        'fade' => 'fade',
      ],
    ];
    $elements['thumbnail_slider']['general']['speed'] = [
      '#title' => t('Speed'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['speed'],
    ];
    $elements['thumbnail_slider']['general']['slideMargin'] = [
      '#title' => t('slideMargin'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['slideMargin'],
    ];
    $elements['thumbnail_slider']['general']['startSlide'] = [
      '#title' => t('startSlide'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['startSlide'],
    ];
    $elements['thumbnail_slider']['general']['randomStart'] = [
      '#type' => 'checkbox',
      '#title' => t('randomStart'),
      '#default_value' => $settings['thumbnail_slider']['general']['randomStart'],
    ];
    $elements['thumbnail_slider']['general']['infiniteLoop'] = [
      '#type' => 'checkbox',
      '#title' => t('infiniteLoop'),
      '#default_value' => $settings['thumbnail_slider']['general']['infiniteLoop'],
    ];
    $elements['thumbnail_slider']['general']['hideControlOnEnd'] = [
      '#type' => 'checkbox',
      '#title' => t('hideControlOnEnd'),
      '#default_value' => $settings['thumbnail_slider']['general']['hideControlOnEnd'],
    ];
    $elements['thumbnail_slider']['general']['easing'] = [
      '#title' => t('easing'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['easing'],
    ];
    $elements['thumbnail_slider']['general']['captions'] = [
      '#type' => 'checkbox',
      '#title' => t('captions'),
      '#default_value' => $settings['thumbnail_slider']['general']['captions'],
    ];
    $elements['thumbnail_slider']['general']['ticker'] = [
      '#type' => 'checkbox',
      '#title' => t('ticker'),
      '#default_value' => $settings['thumbnail_slider']['general']['ticker'],
    ];
    $elements['thumbnail_slider']['general']['tickerHover'] = [
      '#type' => 'checkbox',
      '#title' => t('tickerHover'),
      '#default_value' => $settings['thumbnail_slider']['general']['tickerHover'],
    ];
    $elements['thumbnail_slider']['general']['adaptiveHeight'] = [
      '#type' => 'checkbox',
      '#title' => t('adaptiveHeight'),
      '#default_value' => $settings['thumbnail_slider']['general']['adaptiveHeight'],
    ];
    $elements['thumbnail_slider']['general']['adaptiveHeightSpeed'] = [
      '#title' => t('adaptiveHeightSpeed'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['adaptiveHeightSpeed'],
    ];
    $elements['thumbnail_slider']['general']['responsive'] = [
      '#type' => 'checkbox',
      '#title' => t('responsive'),
      '#default_value' => $settings['thumbnail_slider']['general']['responsive'],
    ];
    $elements['thumbnail_slider']['general']['useCSS'] = [
      '#type' => 'checkbox',
      '#title' => t('useCSS'),
      '#default_value' => $settings['thumbnail_slider']['general']['useCSS'],
    ];
    $elements['thumbnail_slider']['general']['preloadImages'] = [
      '#title' => t('preloadImages'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['general']['preloadImages'],
      '#options' => [
        'all' => 'all',
        'visible' => 'visible',
      ],
    ];
    $elements['thumbnail_slider']['general']['preloadImages'] = [
      '#type' => 'checkbox',
      '#title' => t('preloadImages'),
      '#default_value' => $settings['thumbnail_slider']['general']['preloadImages'],
    ];
    $elements['thumbnail_slider']['general']['swipeThreshold'] = [
      '#title' => t('swipeThreshold'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['general']['swipeThreshold'],
    ];
    $elements['thumbnail_slider']['general']['oneToOneTouch'] = [
      '#type' => 'checkbox',
      '#title' => t('oneToOneTouch'),
      '#default_value' => $settings['thumbnail_slider']['general']['oneToOneTouch'],
    ];
    $elements['thumbnail_slider']['general']['preventDefaultSwipeX'] = [
      '#type' => 'checkbox',
      '#title' => t('preventDefaultSwipeX'),
      '#default_value' => $settings['thumbnail_slider']['general']['preventDefaultSwipeX'],
    ];
    $elements['thumbnail_slider']['general']['preventDefaultSwipeY'] = [
      '#type' => 'checkbox',
      '#title' => t('preventDefaultSwipeY'),
      '#default_value' => $settings['thumbnail_slider']['general']['preventDefaultSwipeY'],
    ];

    $elements['thumbnail_slider']['pager']['pager'] = [
      '#type' => 'hidden',
      '#default_value' => $settings['thumbnail_slider']['pager']['pager'],
    ];

    $elements['thumbnail_slider']['controls'] = [
      '#type' => 'fieldset',
      '#title' => t('Controls'),
      '#weight' => 3,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['thumbnail_slider']['controls']['controls'] = [
      '#type' => 'checkbox',
      '#title' => t('controls'),
      '#default_value' => $settings['thumbnail_slider']['controls']['controls'],
    ];
    $elements['thumbnail_slider']['controls']['nextText'] = [
      '#title' => t('nextText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['nextText'],
    ];
    $elements['thumbnail_slider']['controls']['prevText'] = [
      '#title' => t('prevText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['prevText'],
    ];
    $elements['thumbnail_slider']['controls']['autoControls'] = [
      '#type' => 'checkbox',
      '#title' => t('autoControls'),
      '#default_value' => $settings['thumbnail_slider']['controls']['autoControls'],
    ];
    $elements['thumbnail_slider']['controls']['startText'] = [
      '#title' => t('startText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['startText'],
    ];
    $elements['thumbnail_slider']['controls']['stopText'] = [
      '#title' => t('stopText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['controls']['stopText'],
    ];
    $elements['thumbnail_slider']['controls']['autoControlsCombine'] = [
      '#type' => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['thumbnail_slider']['controls']['autoControlsCombine'],
    ];

    $elements['thumbnail_slider']['auto'] = [
      '#type' => 'fieldset',
      '#title' => t('Auto'),
      '#weight' => 4,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['thumbnail_slider']['auto']['auto'] = [
      '#type' => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['thumbnail_slider']['auto']['auto'],
    ];
    $elements['thumbnail_slider']['auto']['pause'] = [
      '#title' => t('pause'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['auto']['pause'],
    ];
    $elements['thumbnail_slider']['auto']['autoStart'] = [
      '#type' => 'checkbox',
      '#title' => t('autoStart'),
      '#default_value' => $settings['thumbnail_slider']['auto']['autoStart'],
    ];
    $elements['thumbnail_slider']['auto']['autoDirection'] = [
      '#title' => t('autoDirection'),
      '#type' => 'select',
      '#default_value' => $settings['thumbnail_slider']['auto']['autoDirection'],
      '#options' => [
        'next' => 'next',
        'prev' => 'prev',
      ],
    ];
    $elements['thumbnail_slider']['auto']['autoHover'] = [
      '#type' => 'checkbox',
      '#title' => t('autoHover'),
      '#default_value' => $settings['thumbnail_slider']['auto']['autoHover'],
    ];
    $elements['thumbnail_slider']['auto']['autoDelay'] = [
      '#title' => t('autoDelay'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['auto']['autoDelay'],
    ];

    $elements['thumbnail_slider']['carousel'] = [
      '#type' => 'fieldset',
      '#title' => t('Carousel'),
      '#weight' => 5,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['thumbnail_slider']['carousel']['minSlides'] = [
      '#title' => t('minSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['carousel']['minSlides'],
    ];
    $elements['thumbnail_slider']['carousel']['maxSlides'] = [
      '#title' => t('maxSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['carousel']['maxSlides'],
    ];
    $elements['thumbnail_slider']['carousel']['moveSlides'] = [
      '#title' => t('moveSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['thumbnail_slider']['carousel']['moveSlides'],
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('BxSlider (with thumbnail slider) configuration');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $parent_elements = parent::viewElements($items, $langcode);

    $elements = [];
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

    $rendering_ths_items = [];
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

    $element = [
      '#theme' => 'bxslider_ths',
      '#items' => $parent_elements['#items'],
      '#thumbnail_items' => $rendering_ths_items,
      '#settings' => $bxslider_settings,
      '#cache' => [
        'tags' => $cache_tags,
        'contexts' => $cache_contexts,
      ],
    ];

    // Attach library.
    $element['#attached']['library'][] = 'bxslider/jquery.bxslider_ths';

    // Attach settings.
    $element['#attached']['drupalSettings']['bxslider_ths'][$bxslider_settings['slider_id']] = $bxslider_settings;

    return $element;
  }

}
