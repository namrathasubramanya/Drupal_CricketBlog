<?php

namespace Drupal\configform\Form;
 
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;
 
class ConfigFormController extends ConfigFormBase {
 
  public function getFormId() {
    return 'simple_config_form';
  }
 
  public function buildForm(array $form, FormStateInterface $form_state) {
 
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('configform.settings');
 
    $form['start_date'] = array(
      '#type' => 'datetime',
      '#title' => t('Start Date & Time'),
      '#default_value' => DrupalDateTime::createFromTimestamp(time()),
  	);

    $form['end_date'] = array(
      '#type' => 'datetime',
      '#title' => t('End Date & Time'),
      '#default_value' => DrupalDateTime::createFromTimestamp(time()),
    );
 	
 	$checkbox_options = array(
	  'enable' => t('Enable'),
	  'disable' => t('Disable')
	);

    $form['check_box'] = array(
	  '#title' => t('CheckBox'),
	  '#type' => 'radios',
	  '#description' => t('Select the option you like'),
	  '#options' => $checkbox_options,
	  '#default_value' => 'enable',
	);
 
    return $form;
 
  }

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
    $config = $this->config('configform.settings');
    $config->set('configform.email', $form_state->getValue('email'));
    $config->set('configform.node_types', $form_state->getValue('node_types'));
    $config->save();
    return parent::submitForm($form, $form_state);
 
  }

 
  protected function getEditableConfigNames() {
 
    return [
      'configform.settings',
    ];
 
  }
}