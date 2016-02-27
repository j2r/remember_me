<?php

namespace Drupal\remember_me\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Database\Query;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RememberMeEventSubscriber implements EventSubscriberInterface {

  /**
   * Execute some code.
   */
  public function rememberMeInit() {
    $account = \Drupal::currentUser();
    if ($account->isAnonymous()) {
      return;
    }

  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('rememberMeInit');
    return $events;
  }
}