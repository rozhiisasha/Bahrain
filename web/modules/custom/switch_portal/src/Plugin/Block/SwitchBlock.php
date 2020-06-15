<?php

/**
 * @file
 * Contains \Drupal\switch_portal\Plugin\Block\SwitchBlock.
 */
namespace Drupal\switch_portal\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\switch_portal\GetCurrentPortal;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "switch_portal_block",
 *   admin_label = @Translation("Switch portal block"),
 * )
 */
class SwitchBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $portal;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    GetCurrentPortal $portal
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->portal = $portal;
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
      $container->get('switch_portal.get_current_portal')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'corporate' => $this->t('switch to traveller portal'),
      'travel' => $this->t('switch to corporate portal'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['corporate'] = [
      '#type' => 'textfield',
      '#title' => $this->t('For corporate portal'),
    ];
    $form['travel'] = [
      '#type' => 'textfield',
      '#title' => $this->t('For travel portal'),
    ];
    $form['#cache'] = [
      'max-age' => 0,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['corporate']
      = $form_state->getValue('corporate');
    $this->configuration['travel']
      = $form_state->getValue('travel');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $result = $this->portal->getPortal();
    $uri = $this->getUri($result);
    $block = [];
    if ($result[1] !== 'career') {
      if ($this->configuration) {
        if ($result[1] == 'corporate') {
          $block['#title'] = $this->configuration['corporate'];
        }
        else {
          $block['#title'] = $this->configuration['travel'];
        }
        $block['#type'] = 'link';
        $block['#url'] = Url::fromUri($uri);
      }
    }
   return $block;
  }

  function getCacheMaxAge() {
    return 0;
  }

  public function getUri($result) {
    if ($result['second']) {
      $uri = 'internal:' . '/' . $result['second'];
    }
    else {
      $uri = 'internal:' . '/';
    }
    return $uri;
  }

}
