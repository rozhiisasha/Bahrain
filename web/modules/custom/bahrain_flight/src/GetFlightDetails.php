<?php

namespace Drupal\bahrain_flight;

use Drupal\Component\Datetime\Time;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetFlightDetails {

  protected $current_timestamp;
  protected $http_client;
  protected $decoder;
  protected $response;
  public $contents;

  /**
   * GetFlightDetails constructor.
   *
   * @param  \Drupal\Component\Datetime\Time  $current_timestamp
   * @param  \GuzzleHttp\Client  $client
   * @param  \Drupal\Component\Serialization\Json  $decoder
   */
  public function __construct(Time $current_timestamp, Client $client, Json $decoder) {
    $this->current_timestamp = $current_timestamp;
    $this->http_client = $client;
    $this->decoder = $decoder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('datetime.time'),
      $container->get('http_client'),
      $container->get('serialization.json')
    );
  }

  /**
   * Returns new JsonResponse object.
   *
   * @param  string  $route
   *   What way need for flights.
   *
   * @param  string  $day
   *   String for definition what day needed.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function flightDetails(string $route, string $day) {
    $res = $this->getResult($route,$day);
    $this->response = new JsonResponse($res);
    return $this->response;
  }

  /**
   * Get all flights.
   *
   * @param  string  $route
   *   What way need for flights.
   *
   * @param  string  $day
   *   String for definition what day needed.
   *
   * @return \Exception|mixed
   *   Return arrays with flights
   */
  public function getResult(string $route, string $day ) {
    $uri = $this->getUri($route, $day);
    try {
      $request
        = $this->http_client->get($uri);
      $this->contents = $request->getBody()->getContents();
      $flights = $this->decoder->decode($this->contents);
    }
    catch (\Exception $e) {
      return $e;
    }
    return $flights;
  }

  /**
   * Returns array with arrivals flights.
   *
   * @param  string  $day
   *   String for definition what day needed.
   *
   * @return mixed
   *    Return array with arrivals flights.
   */
  public function getArrivals(string $day) {
    $this->flightDetails('arrival', $day);
    $arrival_flights = $this->decoder->decode($this->contents);
    return $arrival_flights;
  }

  /**
   * Returns array with departures flights.
   *
   * @param  string  $day
   *   String for definition what day needed.
   *
   * @return mixed
   *    Return array with departures flights.
   */
  public function getDepartures(string $day) {
    $this->flightDetails('departure', $day);
    $departures_flights = $this->decoder->decode($this->contents);
    return $departures_flights;
  }

  /**
   * Definition time for all flights.
   *
   * Return array with time for flights.
   * @return array
   */
  public function getTime() {
    $current_time = $this->current_timestamp->getRequestTime();
    $current_day = $current_time - ($current_time % 86400);
    $time = [
      'tomorrow' => [
        'begin' => $current_day - 86400,
        'end' => $current_day - 1,
      ],
      'today' => [
        'begin' => $current_day - (86400*2),
        'end' => $current_day - 86401,
      ],
      'current' => [
        'begin' => $current_time - (86400*2),
        'end' => $current_day - 86401,
      ]
    ];
    return $time;
  }

  /**
   * @param  string  $route
   *   What way need for flights.
   *
   * @param  string  $day
   *   String for definition what day needed.
   *
   * @return string
   *   String for connect to api.
   */
  public function getUri($route, $day) {
    $time = $this->getTime();
    $airport = 'OBBI';
    $data = [
      'airport' => $airport,
      'begin' => $time[$day]['begin'],
      'end' => $time[$day]['end'],
    ];

    $uri = http_build_query($data);
    $uri = 'https://opensky-network.org/api/flights/'. $route .'?' .$uri;
    return $uri;
  }

}