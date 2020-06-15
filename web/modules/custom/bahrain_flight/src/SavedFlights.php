<?php


namespace Drupal\bahrain_flight;

use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SavedFlights {

  /**
   * @var \Drupal\bahrain_flight\GetFlightDetails
   */
  protected $flights;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  protected $date_format;

  /**
   * {@inheritdoc}
   */
  public function __construct(GetFlightDetails $flights, Connection $database, DateFormatter $date_format) {
    $this->flights = $flights;
    $this->database = $database;
    $this->date_format = $date_format;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('bahrain_flight.get_flight_details'),
      $container->get('database'),
      $container->get('date.formatter')
    );
  }

  /**
   * Insert information about flights.
   */
  public function insert() {
    $current_data = $this->select();
    if (!empty($current_data)) {
      $this->delete();
    }
    $arrivals = $this->flights->getArrivals('today');
    $arrivals_t= $this->flights->getArrivals('tomorrow');
    $arrivals = array_merge($arrivals, $arrivals_t);
    $departures = $this->flights->getDepartures('today');
    $departures_t = $this->flights->getDepartures('tomorrow');
    $departures = array_merge($departures, $departures_t);
    $query = $this->database->insert('flights');
    $query->fields([
      'flight_number',
      'airline',
      'flight_time',
      'status',
      'origin',
      'arrival_departures',
      'notification',
    ]);
    foreach ($arrivals as $key => $value) {
      if ($arrivals[$key]['estDepartureAirport'] === NULL) {
        $arrivals[$key]['estDepartureAirport'] = '';
      }
      $arrivals[$key]['lastSeen']
        = $this->date_format->format($arrivals[$key]['lastSeen'], 'custom',
          'Y-m-d H:i:s');
      $query->values([
        $arrivals[$key]['callsign'],
        $this->getAirline(),
        $arrivals[$key]['lastSeen'],
        $this->getStatus(),
        $arrivals[$key]['estDepartureAirport'],
        1,
        '1',
      ]);
      try {
        $query->execute();
      }
      catch (\Exception $e) {
        continue;
      }
    }
    foreach ($departures as $key => $value) {
      if ($departures[$key]['estArrivalAirport'] === NULL) {
        $departures[$key]['estArrivalAirport'] = '';
      }
      $departures[$key]['firstSeen'] = $this->date_format->format($departures[$key]['firstSeen'], 'custom', 'Y-m-d H:i:s');
      $query->values([
        $departures[$key]['callsign'],
        $this->getAirline(),
        $departures[$key]['firstSeen'],
        $this->getStatus(),
        $departures[$key]['estArrivalAirport'],
        0,
        '1',
      ]);
      try {
        $query->execute();
      }
      catch (\Exception $e) {
        continue;
      }
    }
  }

  /**
   * Get current data from table flight.
   *
   * @return mixed
   *   Current data about flights.
   */
  public function select() {
    $query = $this->database->select('flights', 'fd');
    $query->fields('fd', [
      'id',
      'flight_number',
      'airline',
      'flight_time',
      'status',
      'origin',
      'arrival_departures',
      'notification',
    ]);
    $query->orderBy('fd.id', 'DESC');
    $output = $query->execute()->fetchAll();
    return $output;
  }

  /**
   * Delete all data about flights from database.
   */
  public function delete() {
    $query = $this->database->delete('flights');
    $query->execute();
  }

  /**
   * Return airline name for flight.
   *
   * @return mixed
   *  Airline name.
   */
  public function getAirline() {
    $airline = [
      'AEROLOGIC GMBH',
      'AIR ARABIA',
      'Air India',
      'AIR INDIA EXPRESS',
      'Al Naser Airlines',
      'ARKE FLY',
      'Atlas Air (Cargo)',
      'Atlas Global',
      'Azerbaijan Airlines',
      'BRITISH AIRWAYS',
      'Cargolux (Cargo)',
      'CATHAY PACIFIC',
      'DHL International',
      'DHL INTERNATIONAL (Cargo)',
      'EGYPT AIR',
      'EMIRATES AIRLINES',
      'Emirates Sky Cargo',
      'ETHIOPIAN AIRLINES',
      'ETIHAD AIRWAYS',
      'Etihad Cargo',
      'FLY DUBAI',
      'Georgian Airways',
      'GULF AIR',
      'IRAQI AIRWAYS',
      'JAZEERA AIRWAYS',
      'KALITTA AIR (CARGO)',
      'KLM ROYAL DUTCH AIRLINES',
      'KUWAIT AIRWAYS',
      'LUFTHANSA',
      'OMAN AIR',
      'Pegasus Airlines',
      'Polar Air (Cargo)',
      'SAUDI ARABIAN AIRLINES',
      'Southern Air (Cargo)',
      'SRILANKAN AIRLINES',
      'SunExpress Airline',
      'SYRIAN ARAB AIRLINES',
      'TRAVEL SERVICES',
      'TURKISH AIRLINES',
    ];
    $result = array_rand($airline, 1);
    return $airline[$result];
  }

  /**
   * Return status for one flight.
   *
   * @return mixed
   *   Status for flight.
   */
  public function getStatus() {
    $status = ['Cancelled', 'Scheduled', 'Landed'];
    $result = array_rand($status, 1);
    return $status[$result];
  }
}