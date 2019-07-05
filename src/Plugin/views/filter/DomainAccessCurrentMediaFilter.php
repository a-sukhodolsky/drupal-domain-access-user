<?php

namespace Drupal\custom_module\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;
/**
 * Handles matching of current domain for entities.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("custom_module_domain_access_current_media_filter")
 */
class DomainAccessCurrentMediaFilter extends DomainAccessCurrentFilterBase {

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, 'media');
  }

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->value_value = t('Media is available on current domain');
  }

}
