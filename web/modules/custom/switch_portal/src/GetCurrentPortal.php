<?php


namespace Drupal\switch_portal;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GetCurrentPortal
 *
 * @package Drupal\switch_portal
 */
class GetCurrentPortal {

  protected $current_path;

  protected $alias;

  public $portal;

  public $language;

  /**
   * GetCurrentPortal constructor.
   *
   * @param  \Drupal\Core\Path\CurrentPathStack  $current_path
   * @param  \Drupal\path_alias\AliasManager  $alias
   */
  public function __construct(\Drupal\Core\Path\CurrentPathStack $current_path, \Drupal\path_alias\AliasManager $alias) {
    $this->current_path = $current_path;
    $this->alias = $alias;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('path.current'),
      $container->get('path_alias.manager')
    );
  }

  /**
   * @return array
   */
  public function getPortal() {
    $this->portal = $this->alias->getAliasByPath($this->current_path->getPath());
    $this->language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $this->portal = explode('/', $this->portal);
    foreach ($this->portal as $key => $value) {
      if ($value == 'ar') {
        unset($this->portal[$key]);
      }
    }
    $this->portal = array_values($this->portal);
    $this->portal['lang'] = $this->language;
    $this->portal['second'] = $this->portal[1] == 'corporate' ? '' : 'corporate';
    return $this->portal;
  }
}