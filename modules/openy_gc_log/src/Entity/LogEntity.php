<?php

namespace Drupal\openy_gc_log\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\openy_gc_log\Field\PayloadFieldItemList;

/**
 * Defines the Log entity entity.
 *
 * @ingroup openy_gc_log
 *
 * @ContentEntityType(
 *   id = "log_entity",
 *   label = @Translation("Virtual Y Log entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\openy_gc_log\LogEntityListBuilder",
 *     "views_data" = "Drupal\openy_gc_log\Entity\LogEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\openy_gc_log\Form\LogEntityForm",
 *       "add" = "Drupal\openy_gc_log\Form\LogEntityForm",
 *       "edit" = "Drupal\openy_gc_log\Form\LogEntityForm",
 *       "delete" = "Drupal\openy_gc_log\Form\LogEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\openy_gc_log\LogEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\openy_gc_log\LogEntityAccessControlHandler",
 *   },
 *   base_table = "log_entity",
 *   translatable = FALSE,
 *   admin_permission = "administer log entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "email" = "email",
 *     "event_type" = "event_type",
 *     "entity_type" = "entity_type",
 *     "bundle" = "bundle",
 *     "entity_id" = "entity_id",
 *     "payload" = "payload",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/log_entity/{log_entity}",
 *     "add-form" = "/admin/structure/log_entity/add",
 *     "edit-form" = "/admin/structure/log_entity/{log_entity}/edit",
 *     "delete-form" = "/admin/structure/log_entity/{log_entity}/delete",
 *     "collection" = "/admin/structure/log_entity",
 *   },
 *   field_ui_base_route = "log_entity.settings"
 * )
 */
class LogEntity extends ContentEntityBase implements LogEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    self::defineEmailField($fields);
    self::defineEventTypeField($fields);
    self::defineEntityTypeField($fields);
    self::defineBundleField($fields);
    self::defineEntityIdField($fields);
    self::definePayloadField($fields);
    self::defineCreatedField($fields);
    return $fields;
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function defineEmailField(array &$fields) {
    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email'))
      ->setDescription(t('The name of the Log entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function defineEventTypeField(array &$fields) {
    $fields['event_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Event Type'))
      ->setDescription(t('The name of the Log entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function defineEntityTypeField(array &$fields) {
    $fields['entity_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity Type'))
      ->setDescription(t('The name of the Log entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function defineBundleField(array &$fields) {
    $fields['bundle'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bundle'))
      ->setDescription(t('The name of the Log entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function defineEntityIdField(array &$fields) {
    $fields['entity_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity Id'))
      ->setDescription(t('The name of the Log entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function definePayloadField(array &$fields) {
    $fields['payload'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Event Payload'))
      ->setComputed(TRUE)
      ->setClass(PayloadFieldItemList::class)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 30,
      ]);
  }

  /**
   * @param $fields
   *
   * @return mixed
   */
  public static function defineCreatedField(array &$fields) {
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

}
