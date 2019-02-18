<?php

namespace Drupal\tableform\Form;
 
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use \Drupal\Core\Entity\EntityInterface;
use \Drupal\Core\Entity\Query\EntityQueryInterface;
use Drupal\node\Entity\Node;
use Drupal\field\FieldConfigInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
 
class TableFormController extends FormBase {
 
  public function getFormId() {
    return 'simple_table_form';
  }
 
  public function buildForm(array $form, FormStateInterface $form_state) {

    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'notification');
    $entity_ids = $query->execute();

    $entities = (
          \Drupal::entityTypeManager()->getStorage('node')
              ->loadMultiple($entity_ids)
    );

    $form['action'] = [
      '#type' => 'select',
      '#default_value' => $this->t('Action'),
      '#options' => [
          '1' => $this
              ->t('View'),
          '2' => $this
              ->t('Publish '),
          '3' => $this
              ->t('Unpublish'),
          '4' => $this
              ->t('Delete'),
      ],
      '#empty_option' => '--',
    ];

    $header = [
      'title' => $this
          ->t('Title'),
      'created' => $this
          ->t('Created On'),
      'view' => $this
          ->t('View'),
      'delete' => $this
          ->t('Delete'),
      'publish' => $this
          ->t('Publish'),
      'unpublish' => $this
          ->t('Unpublish')
    ];

    $nodedata = array();
    foreach($entities as $key => $entity) {
      $createdDate = $entity->get('created')->value;
      $formattedDate = (
          \Drupal::service('date.formatter')
              ->format($createdDate, 'custom', 'M d Y')
      );
      $nodedata += array(
          $key => [
              'title' => $entity->get('title')->value,
              'created' => $formattedDate,
              'view' => Link::fromTextAndUrl('View', Url::fromRoute('entity.node.canonical', ['node' => $entity->get('nid')->value])),
              'delete' => Link::fromTextAndUrl('Delete', Url::fromRoute('entity.node.delete_form', ['node' => $entity->get('nid')->value])),
              'publish' => Link::fromTextAndUrl('Publish', Url::fromRoute('tableform.publish', ['node' => $entity->get('nid')->value])),
              'unpublish' => Link::fromTextAndUrl('Unpublish', Url::fromRoute('tableform.unpublish', ['node' => $entity->get('nid')->value])),
          ],
      );
    }

    $form['tableselect_element'] = array(
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $nodedata,
      '#empty' => t('No content available.'),
    );

    $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
    ];

    return $form;
  }

 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selectedIds = $form_state->getValue('table');
    $action = $form_state->getValue('action');
    return parent::submitForm($form, $form_state);
  }

 
  protected function getEditableConfigNames() {
 
    return [
      'tableform.settings',
    ];
 
  }
}