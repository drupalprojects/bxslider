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
