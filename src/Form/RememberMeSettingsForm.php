<?php
/**
 * @file
 * Contains \Drupal\remember_me\RememberMeSettingsForm
 */

namespace Drupal\remember_me\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Query;

/**
 * Configure hello settings for this site.
 */
class RememberMeSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'remember_me_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'remember_me.settings_form',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('remember_me.settings_form');
    $lifetime = array(3600, 10800, 21600, 43200, 86400, 172800, 259200, 604800, 1209600, 2592000, 5184000, 7776000);
    $options = array();
    foreach ($lifetime as $value) {
      $options[$value] = \Drupal::service('date.formatter')->formatInterval($value);
    }
    $form['remember_me_managed'] = array(
      '#type' => 'checkbox',
      '#title' => t('Manage session lifetime'),
      '#description' => t('Choose to manually overwrite the configuration value from settings.php.'),
      '#default_value' => $config->get('remember_me_managed'),
    );
    $form['remember_me_lifetime'] = array(
      '#type' => 'select',
      '#title' => t('Lifetime'),
      '#default_value' => $config->get('remember_me_lifetime') ?: 604800,
      '#options' => $options,
      '#description' => t('Duration a user will be remembered for. This setting is ignored if Manage session lifetime (above) is disabled.'),
    );
    $form['remember_me_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Remember me field'),
      '#default_value' => $config->get('remember_me_checkbox'),
      '#description' => t('Default state of the "Remember me" field on the login forms.'),
    );
    $form['remember_me_checkbox_visible'] = array(
      '#type' => 'checkbox',
      '#title' => t('Remember me field visible'),
      '#default_value' => $config->get('remember_me_checkbox_visible'),
      '#description' => t('Should the "Remember me" field be visible on the login forms.'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('remember_me.settings_form')
      ->set('remember_me_managed', $form_state->getValue('remember_me_managed'))
      ->set('remember_me_lifetime', $form_state->getValue('remember_me_lifetime'))
      ->set('remember_me_checkbox', $form_state->getValue('remember_me_checkbox'))
      ->set('remember_me_checkbox_visible', $form_state->getValue('remember_me_checkbox_visible'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}