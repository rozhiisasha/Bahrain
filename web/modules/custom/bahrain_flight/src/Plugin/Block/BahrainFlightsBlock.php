<?php

/**
 * @file
 * Contains \Drupal\bahrain_flight\Plugin\Block\BahrainFlightsBlock.
 */

namespace Drupal\bahrain_flight\Plugin\Block;

use Drupal\bahrain_flight\GetFlightDetails;
use Drupal\Component\Datetime\Time;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "bahrain_flights_Block",
 *   admin_label = @Translation("Bahrain flights block"),
 * )
 */
class BahrainFlightsBlock extends BlockBase
  implements ContainerFactoryPluginInterface {

  protected $form_builder;

  protected $flights;

  protected $date;

  protected $date_format;

  public $time;

  public $arrival;

  public $departure;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    FormBuilder $form_builder,
    GetFlightDetails $flights,
    Time $date,
    DateFormatter $date_format
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->form_builder = $form_builder;
    $this->flights = $flights;
    $this->date = $date;
    $this->date_format = $date_format;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('bahrain_flight.get_flight_details'),
      $container->get('datetime.time'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block['#theme'] = 'bahrain_flights_block';
    $arrivals = $this->prepareArrival();
    $departures = $this->prepareDepartures();
    $block['#form']
      = $this->form_builder->getForm('Drupal\bahrain_flight\Form\FlightsDisplayForm');
    $block['#search_a']
      = $this->form_builder->getForm('Drupal\bahrain_flight\Form\SearchArrivalsForm');
    $block['#search_d']
      = $this->form_builder->getForm('Drupal\bahrain_flight\Form\SearchDeparturesForm');
    $block['#tab']['0'] = [
      'flights' => $arrivals,
      'titles' => [
        $this->t('Time'),
        $this->t('Flight'),
        $this->t('Origin'),
        $this->t('Status'),
      ],
    ];
    $block['#tab'][1] = [
      'flights' => $departures,
      'titles' => [
        $this->t('Time'),
        $this->t('Flight'),
        $this->t('Destination'),
        $this->t('Status'),
      ],
    ];
    $current_time = $this->date->getRequestTime();
    $block['#last_update'] = $this->date_format->format($current_time, 'custom', 'H:i');
    $block['#refresh']
      = $this->form_builder->getForm('Drupal\bahrain_flight\Form\RefreshForm');
    $block['#link_to_all'][0] = [
      '#title'  => $this->t('View all arrivals'),
      '#type'   => 'link',
      '#url'    => Url::fromUri('internal:'
        . '/flight-details/flight-arrivals'),
    ];
    $block['#link_to_all'][1] = [
      '#title'  => $this->t('View all departures'),
      '#type'   => 'link',
      '#url'    => Url::fromUri('internal:'
        . '/flight-details/flight-departures'),
    ];
    return $block;
  }

  function getCacheMaxAge() {
    return 0;
  }

  public function prepareArrival() {
    $arrivals = $this->flights->getArrivals('current');
    if (!empty($arrivals)) {
      $arrivals = array_reverse($arrivals);
      $count = count($arrivals);
    }
    else {
      $count = 0;
    }

    if ($count < 7) {
      $arrivals_tomorrow = $this->flights->getArrivals('tomorrow');
      for ($i = 0; $i <= (7 - $count); $i++) {
        $arrivals[$i + $count] = $arrivals_tomorrow[$i];
      }
    }
    $arrivals = array_slice($arrivals,0,7);
    for ($i = 0; $i < 7; $i++) {
      if (is_int($arrivals[$i]['lastSeen'])) {
        $this->time = $this->date_format->format($arrivals[$i]['lastSeen'],
          'custom', 'H:i');
      }
      $this->arrival[$i] = [
        'time'   => $this->time,
        'flight' => $arrivals[$i]['callsign'],
        'origin' => $arrivals[$i]['estDepartureAirport'],
        'stat'   => $this->getStatus(),
      ];
    }
    return $this->arrival;
  }

  /**
   *
   * @return array
   */
  public function prepareDepartures() {
    $departures = $this->flights->getDepartures('current');
    if (!empty($departures)) {
      $departures = array_reverse($departures);
      $count = count($departures);
    }
    else {
      $count = 0;
    }
    if ($count < 7) {
      $departures_tomorrow = $this->flights->getDepartures('tomorrow');
      for ($i = 0; $i <= (7 - $count); $i++) {
        $departures[$i + $count] = $departures_tomorrow[$i];
      }
    }
    $departures = array_slice($departures,0,7);
    for ($i = 0; $i < 7; $i++) {
      if (is_int($departures[$i]['firstSeen'])) {
        $this->time = $this->date_format->format($departures[$i]['firstSeen'],
          'custom', 'H:i');
      }
      $this->departure[$i] = [
        'time'        => $this->time,
        'flight'      => $departures[$i]['callsign'],
        'origin' => $departures[$i]['estArrivalAirport'],
        'stat'        => $this->getStatus(),
      ];
    }
    return $this->departure;
  }

  /**
   * Return status for one flight.
   *
   * @return mixed
   *   Status for flight.
   */
  public function getStatus() {
    $status = [t('Cancelled'), t('Scheduled'), t('Landed')];
    $result = array_rand($status, 1);
    return $status[$result];
  }
}
