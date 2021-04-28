<?php

namespace Drupal\openy_gc_personal_training\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\link\LinkItemInterface;

/**
 * Defines the Personal training entity.
 *
 * @ingroup openy_gc_personal_training
 *
 * @ContentEntityType(
 *   id = "personal_training",
 *   label = @Translation("Personal training"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\openy_gc_personal_training\PersonalTrainingListBuilder",
 *     "views_data" = "Drupal\openy_gc_personal_training\Entity\PersonalTrainingViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\openy_gc_personal_training\Form\PersonalTrainingForm",
 *       "add" = "Drupal\openy_gc_personal_training\Form\PersonalTrainingForm",
 *       "edit" = "Drupal\openy_gc_personal_training\Form\PersonalTrainingForm",
 *       "delete" = "Drupal\openy_gc_personal_training\Form\PersonalTrainingDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\openy_gc_personal_training\PersonalTrainingHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\openy_gc_personal_training\PersonalTrainingAccessControlHandler",
 *   },
 *   base_table = "personal_training",
 *   translatable = FALSE,
 *   admin_permission = "administer personal training entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/virtual-y/personal_training/{personal_training}",
 *     "add-form" = "/admin/virtual-y/personal_training/add",
 *     "edit-form" = "/admin/virtual-y/personal_training/{personal_training}/edit",
 *     "delete-form" = "/admin/virtual-y/personal_training/{personal_training}/delete",
 *     "collection" = "/admin/virtual-y/personal_training",
 *   },
 *   field_ui_base_route = "personal_training.settings"
 * )
 */
class PersonalTraining extends ContentEntityBase implements PersonalTrainingInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {
    return 'Personal training #' . $this->id();
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

  /**
   * {@inheritdoc}
   */
  public function setCustomerPeerId($peerId) {
    $this->set('customer_peer_id', $peerId);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCustomerId() {
    return $this->get('customer_id')->entity->id();
  }

  /**
   * {@inheritdoc}
   */
  public function getInstructorId() {
    return $this->get('instructor_id')->entity->id();
  }

  /**
   * {@inheritdoc}
   */
  public function getCustomerPeerId() {
    return $this->get('customer_peer_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['customer_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Customer'))
      ->setDescription(t('The user ID of client of the Personal training entity.'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['instructor_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Instructor'))
      ->setDescription(t('The user ID of instructor of the Personal training entity. This user should have Virtual Trainer role'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'author',
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['customer_metadata'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Customer metadata'))
      ->setDescription(t('The metadata for the customer from CRM systems (It could be user id, email, or something else).'))
      ->setRequired(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['instructor_metadata'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Instructor metadata'))
      ->setDescription(t('The metadata for the instructor from CRM systems (It could be user id, email, or something else).'))
      ->setRequired(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['training_type'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Training type'))
      ->setDescription(t('One to one call on the Virtual YMCA platform or link to remote platform (zoom, google meet, etc).'))
      ->setDefaultValue('1-1')
      ->setSettings([
        'allowed_values' => [
          '1-1' => 'One to one chat',
          'link' => 'Remote link',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'list_default',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
      ])
      ->setRequired(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['remote_link'] = BaseFieldDefinition::create('link')
      ->setLabel(t('Remote Link'))
      ->setDescription(t('The link to remote platform (zoom, google meet, etc).'))
      ->setSettings([
        'link_type' => LinkItemInterface::LINK_GENERIC,
        'title' => DRUPAL_DISABLED,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'link',
      ])
      ->setDisplayOptions('form', [
        'type' => 'link_default',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    $fields['date'] = BaseFieldDefinition::create('daterange')
      ->setLabel(t('Training Date'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'settings' => [
          'date_format' => 'F jS, Y h:iA',
          'separator' => '-',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'daterange_default',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'text_default',
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'rows' => 6,
      ])
      ->setRequired(FALSE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['customer_peer_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Customer Peer Id'))
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pt_equipment'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Equipment'))
      ->setCardinality(-1)
      ->setRequired(FALSE)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default:taxonomy_term')
      ->setSetting('handler_settings',
        [
          'target_bundles' => [
            'gc_equipment' => 'gc_equipment',
          ],
        ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'match_limit' => '10',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
