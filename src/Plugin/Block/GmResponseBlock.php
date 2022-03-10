<?php

namespace Drupal\gm_response\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'GmResponseBlock' block.
 *
 * @Block(
 *  id = "gm_response_block",
 *  admin_label = @Translation("gm response block"),
 * )
 */
class GmResponseBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form_to_load = '\Drupal\gm_response\Form\GmResponse';

    $form = \Drupal::formBuilder()->getForm($form_to_load);
    return $form;

  }

    /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
