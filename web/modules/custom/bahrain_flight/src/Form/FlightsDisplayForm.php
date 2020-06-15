<?php

/**
 * @file
 * Contains Drupal\bahrain_flight\Plugin\Form\FlightsDisplayForm.
 */

namespace Drupal\bahrain_flight\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FlightsDisplayForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'tabs_block';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions']['arrivals'] = [
      '#type'  => 'button',
      '#value' => $this->t('Arrivals'),
      '#attributes' => [
        'class' => ['active-btn'],
      ],
      '#ajax'  => [
        'event'    => 'click',
        'progress' => [
          'type' => 'none',
        ],
        'disable-refocus' => TRUE,
      ],
    ];
    $form['actions']['departures'] = [
      '#type'  => 'button',
      '#value' => $this->t('Departures'),
      '#attributes' => [
        'class' => ['no-active-btn'],
      ],
      '#ajax'  => [
        'event'    => 'click',
        'progress' => [
          'type' => 'none',
        ],
        'disable-refocus' => TRUE,
      ],
    ];
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function ajaxSubmitCallback(
    array &$form,
    FormStateInterface $form_state
  ) {

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}