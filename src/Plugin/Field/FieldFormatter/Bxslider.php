<?php

namespace Drupal\bxslider\Plugin\Field\FieldFormatter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Cache\Cache;

/**
 * BxSlider.
 *
 * @FieldFormatter(
 *  id = "bxslider",
 *  label = @Translation("BxSlider"),
 *  field_types = {"image", "media"}
 * )
 */
class Bxslider extends ImageFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The image style entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $imageStyleStorage;

  /**
   * Constructs an ImageFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\Core\Entity\EntityStorageInterface $image_style_storage
   *   The entity storage for the image.
   */
  public function __construct($plugin_id,
                              $plugin_definition,
                              FieldDefinitionInterface $field_definition,
                              array $settings,
                              $label,
                              $view_mode,
                              array $third_party_settings,
                              EntityStorageInterface $image_style_storage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->imageStyleStorage = $image_style_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity.manager')->getStorage('image_style')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'image_style' => 'large',
        'general' => [
          'mode' => 'horizontal',
          'speed' => 500,
          'slideMargin' => 0,
          'startSlide' => 0,
          'randomStart' => FALSE,
          'infiniteLoop' => TRUE,
          'hideControlOnEnd' => TRUE,
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
          'pager' => TRUE,
          'pagerType' => 'full',
          'pagerShortSeparator' => ' / ',
          'pagerSelector' => '',
          'pagerCustom_type' => 'none',
          'pagerCustom' => 'null',
          'pagerCustom_image_style' => 'thumbnail',
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
          'auto' => TRUE,
          'pause' => 4000,
          'autoStart' => TRUE,
          'autoDirection' => 'next',
          'autoHover' => FALSE,
          'autoDelay' => 0,
        ],
        'carousel' => [
          'minSlides' => 1,
          'maxSlides' => 1,
          'moveSlides' => 0,
          'slideWidth' => 0,
        ],

      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $settings = $this->getSettings();
    $field_name = $this->fieldDefinition->getName();

    $image_styles = image_style_options(FALSE);

    $elements['image_style'] = [
      '#title' => t('Image style'),
      '#type' => 'select',
      '#default_value' => $settings['image_style'],
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
    ];

    $elements['description'] = [
      '#markup' => t('Visit <a href="@field-help" target="_blank">http://bxslider.com/options</a> for more information about bxSlider options.', ['@field-help' => 'http://bxslider.com/options']),
    ];

    $elements['general'] = [
      '#type' => 'fieldset',
      '#title' => t('General'),
      '#weight' => 1,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['general']['mode'] = [
      '#title' => t('Mode'),
      '#type' => 'select',
      '#default_value' => $settings['general']['mode'],
      '#options' => [
        'horizontal' => 'horizontal',
        'vertical' => 'vertical',
        'fade' => 'fade',
      ],
    ];
    $elements['general']['speed'] = [
      '#title' => t('Speed'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['general']['speed'],
    ];
    $elements['general']['slideMargin'] = [
      '#title' => t('slideMargin'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['general']['slideMargin'],
    ];
    $elements['general']['startSlide'] = [
      '#title' => t('startSlide'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['general']['startSlide'],
    ];
    $elements['general']['randomStart'] = [
      '#type' => 'checkbox',
      '#title' => t('randomStart'),
      '#default_value' => $settings['general']['randomStart'],
    ];
    $elements['general']['infiniteLoop'] = [
      '#type' => 'checkbox',
      '#title' => t('infiniteLoop'),
      '#default_value' => $settings['general']['infiniteLoop'],
    ];
    $elements['general']['hideControlOnEnd'] = [
      '#type' => 'checkbox',
      '#title' => t('hideControlOnEnd'),
      '#default_value' => $settings['general']['hideControlOnEnd'],
    ];
    $elements['general']['easing'] = [
      '#title' => t('easing'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['general']['easing'],
    ];
    $elements['general']['captions'] = [
      '#type' => 'checkbox',
      '#title' => t('captions'),
      '#default_value' => $settings['general']['captions'],
    ];
    $elements['general']['ticker'] = [
      '#type' => 'checkbox',
      '#title' => t('ticker'),
      '#default_value' => $settings['general']['ticker'],
    ];
    $elements['general']['tickerHover'] = [
      '#type' => 'checkbox',
      '#title' => t('tickerHover'),
      '#default_value' => $settings['general']['tickerHover'],
    ];
    $elements['general']['adaptiveHeight'] = [
      '#type' => 'checkbox',
      '#title' => t('adaptiveHeight'),
      '#default_value' => $settings['general']['adaptiveHeight'],
    ];
    $elements['general']['adaptiveHeightSpeed'] = [
      '#title' => t('adaptiveHeightSpeed'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['general']['adaptiveHeightSpeed'],
    ];
    $elements['general']['video'] = [
      '#type' => 'checkbox',
      '#title' => t('video'),
      '#default_value' => $settings['general']['video'],
    ];
    $elements['general']['responsive'] = [
      '#type' => 'checkbox',
      '#title' => t('responsive'),
      '#default_value' => $settings['general']['responsive'],
    ];
    $elements['general']['useCSS'] = [
      '#type' => 'checkbox',
      '#title' => t('useCSS'),
      '#default_value' => $settings['general']['useCSS'],
    ];
    $elements['general']['preloadImages'] = [
      '#title' => t('preloadImages'),
      '#type' => 'select',
      '#default_value' => $settings['general']['preloadImages'],
      '#options' => [
        'all' => 'all',
        'visible' => 'visible',
      ],
    ];
    $elements['general']['preloadImages'] = [
      '#type' => 'checkbox',
      '#title' => t('preloadImages'),
      '#default_value' => $settings['general']['preloadImages'],
    ];
    $elements['general']['swipeThreshold'] = [
      '#title' => t('swipeThreshold'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['general']['swipeThreshold'],
    ];
    $elements['general']['oneToOneTouch'] = [
      '#type' => 'checkbox',
      '#title' => t('oneToOneTouch'),
      '#default_value' => $settings['general']['oneToOneTouch'],
    ];
    $elements['general']['preventDefaultSwipeX'] = [
      '#type' => 'checkbox',
      '#title' => t('preventDefaultSwipeX'),
      '#default_value' => $settings['general']['preventDefaultSwipeX'],
    ];
    $elements['general']['preventDefaultSwipeY'] = [
      '#type' => 'checkbox',
      '#title' => t('preventDefaultSwipeY'),
      '#default_value' => $settings['general']['preventDefaultSwipeY'],
    ];

    $elements['pager'] = [
      '#type' => 'fieldset',
      '#title' => t('Pager'),
      '#weight' => 2,
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];
    $elements['pager']['pager'] = [
      '#type' => 'checkbox',
      '#title' => t('pager'),
      '#default_value' => $settings['pager']['pager'],
    ];
    $elements['pager']['pagerType'] = [
      '#title' => t('pagerType'),
      '#type' => 'select',
      '#default_value' => $settings['pager']['pagerType'],
      '#options' => [
        'full' => 'full',
        'short' => 'short',
      ],
      '#states' => [
        'enabled' => [
          ':input[name="fields[' . $field_name . '][settings_edit_form][settings][pager][pager]"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['pager']['pagerShortSeparator'] = [
      '#title' => t('pagerShortSeparator'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['pager']['pagerShortSeparator'],
      '#states' => [
        'enabled' => [
          ':input[name="fields[' . $field_name . '][settings_edit_form][settings][pager][pager]"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $elements['pager']['pagerSelector'] = [
      '#title' => t('pagerSelector'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['pager']['pagerSelector'],
      '#states' => [
        'enabled' => [
          ':input[name="fields[' . $field_name . '][settings_edit_form][settings][pager][pager]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $elements['controls'] = [
      '#type' => 'fieldset',
      '#title' => t('Controls'),
      '#weight' => 3,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['controls']['controls'] = [
      '#type' => 'checkbox',
      '#title' => t('controls'),
      '#default_value' => $settings['controls']['controls'],
    ];
    $elements['controls']['nextText'] = [
      '#title' => t('nextText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['nextText'],
    ];
    $elements['controls']['prevText'] = [
      '#title' => t('prevText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['prevText'],
    ];
    $elements['controls']['nextSelector'] = [
      '#title' => t('nextSelector'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['nextSelector'],
    ];
    $elements['controls']['prevSelector'] = [
      '#title' => t('prevSelector'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['prevSelector'],
    ];
    $elements['controls']['autoControls'] = [
      '#type' => 'checkbox',
      '#title' => t('autoControls'),
      '#default_value' => $settings['controls']['autoControls'],
    ];
    $elements['controls']['startText'] = [
      '#title' => t('startText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['startText'],
    ];
    $elements['controls']['stopText'] = [
      '#title' => t('stopText'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['stopText'],
    ];
    $elements['controls']['autoControlsCombine'] = [
      '#type' => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['controls']['autoControlsCombine'],
    ];
    $elements['controls']['autoControlsSelector'] = [
      '#title' => t('autoControlsSelector'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['controls']['autoControlsSelector'],
    ];

    $elements['auto'] = [
      '#type' => 'fieldset',
      '#title' => t('Auto'),
      '#weight' => 4,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['auto']['auto'] = [
      '#type' => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['auto']['auto'],
    ];
    $elements['auto']['pause'] = [
      '#title' => t('pause'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['auto']['pause'],
    ];
    $elements['auto']['autoStart'] = [
      '#type' => 'checkbox',
      '#title' => t('autoStart'),
      '#default_value' => $settings['auto']['autoStart'],
    ];
    $elements['auto']['autoDirection'] = [
      '#title' => t('autoDirection'),
      '#type' => 'select',
      '#default_value' => $settings['auto']['autoDirection'],
      '#options' => [
        'next' => 'next',
        'prev' => 'prev',
      ],
    ];
    $elements['auto']['autoHover'] = [
      '#type' => 'checkbox',
      '#title' => t('autoHover'),
      '#default_value' => $settings['auto']['autoHover'],
    ];
    $elements['auto']['autoDelay'] = [
      '#title' => t('autoDelay'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['auto']['autoDelay'],
    ];

    $elements['carousel'] = [
      '#type' => 'fieldset',
      '#title' => t('Carousel'),
      '#weight' => 5,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $elements['carousel']['minSlides'] = [
      '#title' => t('minSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['carousel']['minSlides'],
    ];
    $elements['carousel']['maxSlides'] = [
      '#title' => t('maxSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['carousel']['maxSlides'],
    ];
    $elements['carousel']['moveSlides'] = [
      '#title' => t('moveSlides'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['carousel']['moveSlides'],
    ];
    $elements['carousel']['slideWidth'] = [
      '#title' => t('slideWidth'),
      '#type' => 'textfield',
      '#size' => 60,
      '#default_value' => $settings['carousel']['slideWidth'],
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('BxSlider configuration');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
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

    $rendering_items = [];
    foreach ($files as $delta => $file) {
      $cache_contexts = [];
      if (isset($link_file)) {
        $image_uri = $file->getFileUri();
        $url = Url::fromUri(file_create_url($image_uri));
        $cache_contexts[] = 'url.site';
      }
      $cache_tags = Cache::mergeTags($base_cache_tags, $file->getCacheTags());

      $rendering_items[] = $file->_referringItem;

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

    $element = [
      '#theme' => 'bxslider',
      '#items' => $rendering_items,
      '#settings' => $bxslider_settings,
      '#cache' => [
        'tags' => $cache_tags,
        'contexts' => $cache_contexts,
      ],
    ];

    // Attach library.
    $element['#attached']['library'][] = 'bxslider/jquery.bxslider';

    // Attach settings.
    $element['#attached']['drupalSettings']['bxslider'][$bxslider_settings['slider_id']] = $bxslider_settings;

    return $element;
  }

}
