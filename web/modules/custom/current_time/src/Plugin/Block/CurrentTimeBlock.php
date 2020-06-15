<?php

/**
 * @file
 * Contains \Drupal\current_time\Plugin\Block\CurrentTime.
 */

namespace Drupal\current_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "current_time_Block",
 *   admin_label = @Translation("Сurrent time in Bahrain and more info block"),
 * )
 */
class CurrentTimeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block['#theme'] = 'current_time_block';
    $current_time = \Drupal::service('datetime.time')->getRequestTime();
    $block['#last_update'] = \Drupal::service('date.formatter')
      ->format($current_time, 'custom', 'h:i A');
    $current_uri = \Drupal::request()->getRequestUri();
    $path_arr = explode('/', $current_uri);
    $block['#path_arr_last'] = array_pop($path_arr);
    return $block;
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
