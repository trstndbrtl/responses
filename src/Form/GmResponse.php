<?php

namespace Drupal\gm_response\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation;

class GmResponse extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'gm_response_form';
  }

	/**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Prepare messenger
    $messenger = \Drupal::messenger();

    // Get uuid
    $tempstore = \Drupal::service('tempstore.private')->get('gm_response');
    $uuid_data = $tempstore->get('participants_uuid');

    // Prepareredirection
    $response = Url::fromUserInput('/questions');
    $messenger->addMessage($uuid_data);


    // show form only on node
    if ($node = \Drupal::routeMatch()->getParameter('node')) {

      $form['la_question'] = [
        '#type' => 'hidden',
        '#required' => TRUE,
        '#default_value' => $node->id(),
      ];

      $form['la_response'] = array(
        '#type' => 'text_format',
        '#title' => t('Votre réponse'),
        '#format' => 'plain_text',
        // '#weight' => 0,
        '#required' => TRUE,
      );

      // $form['#attached']['library'][] = 'gm_response/gm-design-section';

      $form['actions'] = [
        '#type' => 'actions',
      ];

      $form['actions']['submit_new'] = [
        '#type' => 'submit',
        '#value' => $this->t('Envoyer votre réponse'),
      ];

    }

    $form['#cache'] = ['max-age' => 0];

    // If not uuid redirect it
    if (empty($uuid_data)) {
      $messenger->addMessage('Désolé, vous devez vous identifier pour participer.');
      $form_state->setRedirectUrl($response);
    }

    return $form;

  }

	/**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get data
    $la_question = !empty($form_state->getValue('la_question')) ? (int)$form_state->getValue('la_question') : NULL;
    $la_response = !empty($form_state->getValue('la_response')) ? $form_state->getValue('la_response')['value'] : NULL;

    // Prepare messenger
    $messenger = \Drupal::messenger();

    // Get uuid
    $tempstore = \Drupal::service('tempstore.private')->get('gm_response');
    $uuid_data = $tempstore->get('participants_uuid');

    // Prepareredirection
    $response = Url::fromUserInput('/questions');
    $messenger->addMessage($uuid_data);
    // If not uuid redirect it
    if (empty($uuid_data)) {
      $messenger->addMessage('Désolé, vous devez vous identifier pour participer.');
      $form_state->setRedirectUrl($response);
    }

    if ($la_question && $la_response && $uuid_data) {
      // Create node object with attached file.
      $node = Node::create([
        'type' => 'reponses',
        'title' => substr($la_response, 0, 45),
        'uid' => 1,
        'field_question' => [$la_question],
        'body' => $la_response,
        'field_uuid' => $uuid_data
      ]);
      // Save the response
      $node->save();
      // Show a message
      $messenger->addMessage('Merci, votre reponse à été sauvegarder.');
      // redirect user
      $form_state->setRedirectUrl($response);

    }

  }

}
