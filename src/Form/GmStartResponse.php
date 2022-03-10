<?php

namespace Drupal\gm_response\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\gm_response\ResponseHelperTrait;

class GmStartResponse extends FormBase {

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
    return 'gm_start_response_form';
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

    $form['description'] = array(
      '#markup' => '<strong>Pour participer, merci de repondre aux questions suivantes.</strong>',
      '#prefix' => '<div id="gm-about">',
      '#suffix' => '</div>',
    );

    $form['genre'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Je suis'),
      '#options' => array(
        'femme' => $this->t('une femme'),
        'homme' => $this->t('un homme'),
        // 'sans_reponse' => $this->t("Je préfére ne pas répondre"),
      ),
      '#prefix' => '<div id="gm-genre">',
      '#suffix' => '</div>',
      // '#default_value' => 'sans_reponse',
    );

    $form['age'] = array(
      '#type' => 'radios',
      '#title' => $this->t('j\'ai'),
      '#options' => array(
        '20' => $this->t('Moins de 20 ans'),
        '20_40' => $this->t('Entre 20 et 40 ans'),
        '40_60' => $this->t('Entre 40 et 60 ans'),
        '60' => $this->t('Plus de 60 ans'),
        // 'sans_reponse' => $this->t("Je préfére ne pas répondre"),
      ),
      '#prefix' => '<div id="gm-age">',
      '#suffix' => '</div>',
      // '#default_value' => 'sans_reponse',
    );

    $form['activite'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Je suis'),
      '#options' => array(
        'sans_activite' => $this->t('Sans activité'),
        'etudiant' => $this->t('Étudiant.e'),
        'actif' => $this->t('Actif'),
        'retraite' => $this->t('Retraité.e'),
        // 'sans_reponse' => $this->t("Je préfére ne pas répondre"),
      ),
      '#prefix' => '<div id="gm-activite">',
      '#suffix' => '</div>',
      // '#default_value' => 'sans_reponse',
    );

    $form['code_postal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Code postal'),
      '#autocomplete_route_name' => 'gm_response.find_code_postal',
      '#prefix' => '<div id="gm-postal">',
      '#suffix' => '</div>',
      '#description' => '<div class="ripple">Veuillez taper les 3 premiere lettres de votre ville et selectionner votre code postal dans la liste proposée</div>',
      // '#required' => TRUE,
      '#attributes' => array(
        'autocomplete' => 'off',
        'autocorrect' => 'off',
        'autocapitalize' => 'none',
        'spellcheck' => 'off',
      ),
    ];

    // $form['#attached']['library'][] = 'gm_response/gm-design-section';

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit_new'] = [
      '#type' => 'submit',
      '#value' => $this->t('Paticiper'),
      '#prefix' => '<div id="gm-go">',
      '#suffix' => '</div>',
    ];

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
    // Get data field
    $gender = !empty($form_state->getValue('genre')) ? $form_state->getValue('genre') : NULL;
    $age = !empty($form_state->getValue('age')) ? $form_state->getValue('age') : NULL;
    $activity = !empty($form_state->getValue('activite')) ? $form_state->getValue('activite') : NULL;
    $postal_code = !empty($form_state->getValue('code_postal')) ? $form_state->getValue('code_postal') : 00000;
    // Generate uuid
    $uuid_service = \Drupal::service('uuid');
    $uuid = $uuid_service->generate();
    // Set uuid session
    $tempstore = \Drupal::service('tempstore.private')->get('gm_response');
    $tempstore->set('participants_uuid', $uuid);
    // Store session on db
    $store_in_db = ResponseHelperTrait::storeParticipants($uuid, $gender, $activity, $age, $postal_code, NULL, NULL, NULL);

    // Prepareredirection
    $response = Url::fromUserInput('/questions');
    // redirect user
    $form_state->setRedirectUrl($response);
  }

}
