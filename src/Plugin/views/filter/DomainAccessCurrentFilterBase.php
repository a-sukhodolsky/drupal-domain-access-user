<?php

namespace Drupal\custom_module\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\BooleanOperator;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;

/**
 * Handles matching of current domain for entities.
 * Based on Drupal\domain_access\Plugin\views\filter\DomainAccessCurrentAllFilter
 */
class DomainAccessCurrentFilterBase extends BooleanOperator {

  protected $entityType;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, $entity_type) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityType = $entity_type;
  }

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->value_value = t('Entity is available on current domain');
  }

  /**
   * {@inheritdoc}
   */
  public function getValueOptions() {
    $this->valueOptions = array(1 => $this->t('Yes'), 0 => $this->t('No'));
  }

  /**
   * {@inheritdoc}
   */
  protected function operators() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();
    $current_domain = \Drupal::service('domain.negotiator')->getActiveId();
    if (empty($this->value)) {
      // @TODO proper handling of NULL?
      $where = "$this->tableAlias.$this->realField <> '$current_domain'";
      $where = "($where OR $this->tableAlias.$this->realField IS NULL)";
    }
    else {
      $where = "($this->tableAlias.$this->realField = '$current_domain')";
    }
    $this->query->addWhereExpression($this->options['group'], $where);
    if ($this->getEntityType() == 'user') {
      // This filter causes duplicates.
      $this->query->options['distinct'] = TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = parent::getCacheContexts();

    $contexts[] = 'url.site';

    return $contexts;
  }

}
