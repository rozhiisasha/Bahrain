<?php

/**
 * @file
 * Contains \Drupal\switch_flight\Plugin\Block\SwitchFlightBlock.
 */

namespace Drupal\switch_flight\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * @Block(
 *   id = "switch_flight_block",
 *   admin_label = @Translation("Switch flight block"),
 * )
 */
class SwitchFlightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_uri = \Drupal::request()->getRequestUri();
    $path_arr = explode('/', $current_uri);
    $name_page = '';

    if (array_pop($path_arr) == 'flight-departures') {
      array_push($path_arr, 'flight-arrivals');
      $name_page = $this->t('switch to arrivals');
    }
    else {
      array_push($path_arr, 'flight-departures');
      $name_page = $this->t('switch to departures');
    }

    $block['switch_link'] = [
      '#title' => $name_page,
      '#type' => 'link',
      '#url' => Url::fromUri('internal:' . implode("/", $path_arr)),
    ];

    return $block;
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
