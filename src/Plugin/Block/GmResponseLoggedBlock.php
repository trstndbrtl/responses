<?php

namespace Drupal\gm_response\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\gm_response\ResponseHelperTrait;

/**
 * Provides a 'GmResponseLoggedBlock' block.
 *
 * @Block(
 *  id = "gm_response_logged_block",
 *  admin_label = @Translation("gm response logged block"),
 * )
 */
class GmResponseLoggedBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function build() {

    $participant = NULL;
    // Get uuid
    $tempstore = \Drupal::service('tempstore.private')->get('gm_response');
    $uuid_data = empty($tempstore->get('participants_uuid')) ? 'Ouvrir une session' : $tempstore->get('participants_uuid');

    if ($tempstore->get('participants_uuid')) {
      $participant = ResponseHelperTrait::getParticipant($tempstore->get('participants_uuid'));
    }

    var_dump($participant);

    return [
      '#theme' => 'response_logged_block',
      '#participant' => $participant,
    ];

  }

    /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
