<?php

/**
 * @file
 * Contains Drupal\bahrain_flight\Plugin\Form\SearchDeparturesForm.
 */

namespace Drupal\bahrain_flight\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SearchDeparturesForm extends FormBase {

  /**
   * @inheritdoc
   */
  public function getFormId() {
    return 'search_departures';
  }

  /**
   * @inheritdoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if ($language == 'ar') {
      $form['#action'] = '/ar/flight-details/flight-departures';
    }
    else {
      $form['#action'] = '/flight-details/flight-departures';
    }
    $form['#attributes'] = [
      'class' => [
            'tab',
            'tab2',
            'no-active',
      ],
      'method' => 'GET',
    ];
    $form['flight_number'] = [
      '#type'        => 'search',
      '#placeholder' => $this->t('Number'),
    ];
    $form['origin'] = [
      '#type'        => 'search',
      '#placeholder' => $this->t('Destination'),
    ];
    $form['button'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Find Flights'),
    ];
    return $form;
  }

  /**
   * @inheritdoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}