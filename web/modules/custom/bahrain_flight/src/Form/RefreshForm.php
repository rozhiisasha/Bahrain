<?php

/**
 * @file
 * Contains Drupal\bahrain_flight\Plugin\Form\RefreshForm.
 */

namespace Drupal\bahrain_flight\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RefreshForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'refresh_button';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions'] = [
      '#type'  => 'button',
      '#value' => $this->t('Refresh'),
    ];
    return $form;
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