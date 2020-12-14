<?php

namespace Drupal\openy_gc_auth_daxko_sso\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class for VirtualYDaxkoSSOLoginForm Form.
 *
 * @package Drupal\openy_gc_auth_daxko_sso\Form
 */
class VirtualYDaxkoSSOLoginForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'openy_gc_auth_daxko_sso_login_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['submit'] = [
      '#type' => 'link',
      '#url' => Url::fromRoute('openy_gc_auth_daxko_sso.daxko_link_controller_hello'),
      '#title' => t('Enter Virtual Y'),
      '#attributes' => [
        'class' => [
          'button',
          'form-submit'
        ]
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirectUrl(Url::fromRoute('openy_gc_auth_daxko_sso.daxko_link_controller_hello'));
  }

}
