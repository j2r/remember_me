<?php
/**
 * @file
 * Contains \Drupal\remember_me\EventSubscriber\RememberMeEventSubscriber
 */

namespace Drupal\remember_me\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Database\Query;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RememberMeEventSubscriber implements EventSubscriberInterface {
  /**
   * @var int
   */
  protected $default_lifetime;

  /**
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  public function __construct(array $session_storage_options, ConfigFactoryInterface $config_factory) {
    $this->default_lifetime = $session_storage_options['cookie_lifetime'];
    $this->config = $config_factory->get('remember_me.settings_form');
  }

  /**
   * Execute some code.
   */
  public function init() {
    $remember_me_managed = $this->config->get('remember_me_managed');
    $request = \Drupal::request();
    if ($remember_me_managed && $request->get('remember_me')) {
      $lifetime = $this->config->get('remember_me_lifetime') ?: $this->default_lifetime;
      ini_set('session.cookie_lifetime', $lifetime);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['init'];
    return $events;
  }
}