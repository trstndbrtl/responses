<?php

namespace Drupal\gm_response\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ParticipantsRedirect implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => [
        ['redirectionParticipants', 27],
      ]
    ];
  }

  /**
   * Redirection des contenus de type media vers la page du media dans l'app.
   */
  public function redirectionParticipants(GetResponseEvent $event) {

    $request = $event->getRequest();

    $tempstore = \Drupal::service('tempstore.private')->get('gm_response');
    $uuid_data = $tempstore->get('participants_uuid');

    // if not a node return
    if ($request->attributes->get('_route') !== 'entity.node.canonical') {
      return;
    }

    // get current user
    $current_user_id = \Drupal::currentUser()->id();
    $current_user = \Drupal\user\Entity\User::load($current_user_id);

    // if is a admin return
    if ($current_user->hasRole('administrator') || $current_user->hasRole('director') || $current_user->hasRole('developper')) {
      return;
    }

    // Redirect to front each node if not a questions
    if ($request->attributes->get('node')->getType() !== 'questions') {
      return;
    }

    // if not have a uuid return
    if (!empty($uuid_data)) {
      return;
    }

    // Redirect
    $response = new RedirectResponse(Url::fromRoute('<front>')->toString(), 301);
    $event->setResponse($response);

  }

}
