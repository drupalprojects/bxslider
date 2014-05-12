<?php
/**
 * @file
 * Template for BxSlider.
 */
?>
<ul class="bxslider">
  <?php foreach($items as $item): ?>
    <li><?php print $item['slide']; ?></li>
  <?php endforeach; ?>
</ul>
<?php if($settings['pagerCustom_type'] == 'thumbnail_pager_method1'): ?>
  <div id="<?php  print $settings['pagerCustom'] ?>">
    <?php foreach($items as $key => $item): ?>
      <a data-slide-index="<?php print $key ?>" href=""><?php print $item['slide_pagerCustom']; ?></a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
