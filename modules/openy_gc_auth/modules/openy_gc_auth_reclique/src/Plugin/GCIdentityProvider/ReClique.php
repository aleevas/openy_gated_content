<?php

namespace Drupal\openy_gc_auth_reclique\Plugin\GCIdentityProvider;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormStateInterface;
use Drupal\openy_gc_auth\GCIdentityProviderPluginBase;

/**
 * ReClique identity provider plugin.
 *
 * @GCIdentityProvider(
 *   id="reclique",
 *   label = @Translation("ReClique provider"),
 *   config="openy_gc_auth.provider.reclique"
 * )
 */
class ReClique extends GCIdentityProviderPluginBase {

  use DependencySerializationTrait;

  const DEFAULT_LINK_LIFE_TIME = 14400;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration():array {
    return [
      'enable_recaptcha' => TRUE,
      'enable_email_verification' => TRUE,
      'email_verification_link_life_time' => self::DEFAULT_LINK_LIFE_TIME,
      'email_verification_text' => 'Hello! <br> You’re just one step away from accessing your Virtual YMCA. Please open the link below to begin enjoying YMCA content made exclusively for members like you.',
      'verification_message' => 'We have sent a verification link to the email address you provided. Please open this link and activate your account. If you do not receive an email, please try again or contact us at XXX-XXX-XXXX to ensure we have the correct email on file for your membership.',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['#tree'] = TRUE;
    $form['permissions_mapping'] = [
      '#title' => $this->t('Permissions mapping'),
      '#type' => 'details',
      '#open' => TRUE,
      '#prefix' => '<div id="permissions-mapping-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];
    $permissions_mapping = explode(';', $config['permissions_mapping']);
    if (!$form_state->has('permissions_mapping_items_count')) {
      $form_state->set('permissions_mapping_items_count', count($permissions_mapping));
    }
    $permissions_mapping_items = $form_state->get('permissions_mapping_items_count');
    $roles = $this->gcUserService->getRoles();
    for ($i = 0; $i < $permissions_mapping_items; $i++) {
      $role = isset($permissions_mapping[$i]) ? explode(':', $permissions_mapping[$i]) : '';
      $form['permissions_mapping'][$i]['permissions_mapping_reclique_role'] = [
        '#title' => $this->t('ReClique membership'),
        '#type' => 'textfield',
        '#default_value' => isset($role[0]) ? $role[0] : '',
        '#size' => 30,
        '#prefix' => '<div class="container-inline">',
      ];

      $form['permissions_mapping'][$i]['permissions_mapping_role'] = [
        '#title' => $this->t('Virtual Y role'),
        '#type' => 'select',
        '#options' => ['' => $this->t('None')] + $roles,
        '#default_value' => isset($role[1]) ? $role[1] : '',
        '#suffix' => '</div>',
      ];
    }

    $form['permissions_mapping']['actions'] = [
      '#type' => 'actions',
    ];

    $form['permissions_mapping']['actions']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => [[get_class($this), 'addOne']],
      '#ajax' => [
        'callback' => [get_class($this), 'addmoreCallback'],
        'wrapper' => 'permissions-mapping-fieldset-wrapper',
      ],
    ];

    $form['verification_url'] = [
      '#title' => $this->t('Verification URL'),
      '#type' => 'textfield',
      '#default_value' => $config['verification_url'],
      '#required' => TRUE,
    ];

    $form['auth_login'] = [
      '#title' => $this->t('Authentication login'),
      '#type' => 'textfield',
      '#default_value' => $config['auth_login'],
      '#required' => TRUE,
    ];

    $form['auth_pass'] = [
      '#title' => $this->t('Authentication password'),
      '#type' => 'textfield',
      '#default_value' => $config['auth_pass'],
      '#required' => TRUE,
    ];

    $form['id_field_text'] = [
      '#title' => $this->t('ID field text'),
      '#type' => 'textfield',
      '#default_value' => $config['id_field_text'],
      '#required' => TRUE,
    ];

    $form['enable_recaptcha'] = [
      '#title' => $this->t('Enable ReCaptcha'),
      '#description' => $this->t('Set to TRUE if you want ReCaptcha validation on login form.'),
      '#type' => 'checkbox',
      '#default_value' => $config['enable_recaptcha'],
    ];

    $form['verification'] = [
      '#type' => 'details',
      '#title' => $this->t('Email verification'),
      '#open' => TRUE,
    ];

    $form['verification']['enable_email_verification'] = [
      '#title' => $this->t('Enable Email verification'),
      '#description' => $this->t('Set to TRUE if you want enable one-time login link sending to user email for verification.'),
      '#type' => 'checkbox',
      '#default_value' => $config['enable_email_verification'],
    ];

    $form['verification']['email_verification_link_life_time'] = [
      '#type' => 'select',
      '#title' => $this->t('Login link life time'),
      '#description' => $this->t('Select the time period after which the verification link will expire.'),
      '#default_value' => $config['email_verification_link_life_time'],
      '#required' => TRUE,
      '#options' => [
        3600 => $this->t('1 hour'),
        7200 => $this->t('2 hours'),
        10800 => $this->t('3 hours'),
        14400 => $this->t('4 hours'),
        18000 => $this->t('5 hours'),
        86400 => $this->t('1 day'),
        172800 => $this->t('2 days'),
      ],
    ];

    $form['verification']['email_verification_text'] = [
      '#title' => $this->t('Email verification text'),
      '#description' => $this->t('This text will be used in the email.'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#default_value' => $config['email_verification_text'],
      '#required' => TRUE,
    ];

    $form['verification']['verification_message'] = [
      '#title' => $this->t('Verification message'),
      '#description' => $this->t('This text will be displayed in the application after unverified user tried to login.'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#default_value' => $config['verification_message'],
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * Add more item.
   *
   * @param array $form
   *   Form data.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public static function addOne(array &$form, FormStateInterface $form_state) {
    $permissions_mapping_items = $form_state->get('permissions_mapping_items_count');
    $add_button = $permissions_mapping_items + 1;
    $form_state->set('permissions_mapping_items_count', $add_button);
    $form_state->setRebuild();
  }

  /**
   * Add more callback.
   *
   * @param array $form
   *   Form data.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return mixed
   *   Return array with data.
   */
  public static function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['settings']['permissions_mapping'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->getErrors()) {
      $this->configuration['enable_recaptcha'] = $form_state->getValue('settings')['enable_recaptcha'];
      $this->configuration['verification_url'] = $form_state->getValue('settings')['verification_url'];
      $this->configuration['auth_login'] = $form_state->getValue('settings')['auth_login'];
      $this->configuration['auth_pass'] = $form_state->getValue('settings')['auth_pass'];
      $this->configuration['id_field_text'] = $form_state->getValue('settings')['id_field_text'];
      $this->configuration['enable_email_verification'] = $form_state->getValue('settings')['verification']['enable_email_verification'];
      $this->configuration['email_verification_link_life_time'] = $form_state->getValue('settings')['verification']['email_verification_link_life_time'];
      $this->configuration['email_verification_text'] = !empty($form_state->getValue('settings')['verification']['email_verification_text']) ? $form_state->getValue('settings')['verification']['email_verification_text']['value'] : '';
      $this->configuration['verification_message'] = !empty($form_state->getValue('settings')['verification']['verification_message']) ? $form_state->getValue('settings')['verification']['verification_message']['value'] : '';
      foreach ($form_state->getValue('settings')['permissions_mapping'] as $mapping) {
        if (!empty($mapping['permissions_mapping_reclique_role'])) {
          $permissions_mapping[] = $mapping['permissions_mapping_reclique_role'] . ':' . $mapping['permissions_mapping_role'];
        }
      }
      $this->configuration['permissions_mapping'] = !empty($permissions_mapping) ? implode(';', $permissions_mapping) : '';
      parent::submitConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getLoginForm() {
    return $this->formBuilder->getForm('Drupal\openy_gc_auth_reclique\Form\VirtualYReCliqueLoginForm');
  }

}
