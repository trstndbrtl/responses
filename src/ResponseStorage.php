<?php

namespace Drupal\gm_response;

use Drupal\Core\Database\Connection;
use Drupal\Core\State\StateInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides the default database storage backend for statistics.
 *
 * @package Drupal\gm_response
 */
class ResponseStorage implements ResponseStorageInterface {

  /**
  * The database connection used.
  *
  * @var \Drupal\Core\Database\Connection
  */
  protected $connection;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs the statistics storage.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection for the node view storage.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(Connection $connection, StateInterface $state, RequestStack $request_stack) {
    $this->connection = $connection;
    $this->state = $state;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function storeSubscriber($uuid, $gender, $activity, $age, $postal_code, $more1 = NULL, $more2 = NULL, $more3 = NULL) {
    return (bool) $this->connection
      ->merge('users_participants_questions')
      ->key('uuid', $uuid)
      ->insertFields(array(
        'uuid' => $uuid,
        'gender' => $gender,
        'activity' => $activity,
        'age' => $age,
        'postal_code' => $postal_code,
        'more1' => $more1,
        'more2' => $more2,
        'more3' => $more3,
      ))
      ->updateFields(array(
        'uuid' => $uuid,
        'gender' => $gender,
        'activity' => $activity,
        'age' => $age,
        'postal_code' => $postal_code,
        'more1' => $more1,
        'more2' => $more2,
        'more3' => $more3,
      ))->execute();
    }

}
