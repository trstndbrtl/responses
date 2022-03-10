<?php
namespace Drupal\gm_response;

/**
 * Class gm_response.
 */
trait ResponseHelperTrait {

  /**
   * storeParticipants().
   *
   * @param string $uuid
   * @param string $gender
   * @param string $activity
   * @param string $age
   * @param int $postal_code
   * @param string $more1
   * @param string $more2
   * @param string $more3
   *
   * @return bool
   */
  public static function storeParticipants($uuid, $gender, $activity, $age, $postal_code, $more1 = NULL, $more2 = NULL, $more3 = NULL) {

    $connection = \Drupal::database();

    return (bool) $connection
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
        'timestamp' => \Drupal::time()->getRequestTime()
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
        'timestamp' => \Drupal::time()->getRequestTime()
      ))->execute();

  }



  public static function getParticipant($uuid) {

    $results = [];

    $query = \Drupal::database()->select('users_participants_questions', 'upq')
      ->fields('upq', ['id', 'uuid', 'gender', 'activity', 'age', 'postal_code', 'timestamp'])
      ->condition('uuid', $uuid)
      ->range(0, 1);

    $results = $query->execute()->fetchAll();

    // var_dump($results);

    if (!$results && !isset($results[0])) {
      return FALSE;
    }

    return $results[0];

  }

}
