<?php

namespace Drupal\gm_response\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'GmResponseBlock' block.
 *
 * @Block(
 *  id = "gm_response_start_block",
 *  admin_label = @Translation("gm Start response block"),
 * )
 */
class GmResponseStartBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Get uuid
    $tempstore = \Drupal::service('tempstore.private')->get('gm_response');
    $uuid_data = $tempstore->get('participants_uuid');

    // Show form only if not uuid
    if (empty(trim($uuid_data))) {
      $form_to_load = '\Drupal\gm_response\Form\GmStartResponse';

      $form = \Drupal::formBuilder()->getForm($form_to_load);
      return $form;
    }else {

      return [
        '#markup' => '<a class="gm-go" href="/questions">Consulter les questions</a>',
      ];

    }

  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
