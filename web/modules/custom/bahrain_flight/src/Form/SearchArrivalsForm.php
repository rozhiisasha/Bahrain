<?php

/**
 * @file
 * Contains Drupal\bahrain_flight\Plugin\Form\SearchArrivalsForm.
 */

namespace Drupal\bahrain_flight\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SearchArrivalsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_arrival';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if ($language == 'ar') {
      $form['#action'] = '/ar/flight-details/flight-arrivals';

    }
    else {
      $form['#action'] = '/flight-details/flight-arrivals';
    }
    $form['#attributes'] = [
      'class' => [
            'tab',
            'tab1',
            'active',
      ],
      'method' => 'GET',
    ];
    $form['flight_number'] = [
      '#type'        => 'search',
      '#placeholder' => $this->t('Number'),
    ];
    $form['origin'] = [
      '#type'        => 'search',
      '#placeholder' => $this->t('Origin'),
    ];
    $form['button'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Find Flights'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}