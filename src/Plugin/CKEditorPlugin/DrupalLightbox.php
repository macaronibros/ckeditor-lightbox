<?php

namespace Drupal\macaroni_ckeditor_lightbox\Plugin\CKEditorPlugin;

use Drupal\Core\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\ckeditor\CKEditorPluginCssInterface;

/**
 * Defines the "drupallightbox" plugin.
 *
 * @CKEditorPlugin(
 *   id = "drupallightbox",
 *   label = @Translation("Drupal lightbox widget"),
 *   module = "ckeditor"
 * )
 */
class DrupalLightbox extends PluginBase implements CKEditorPluginInterface, CKEditorPluginContextualInterface, CKEditorPluginCssInterface {

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return [
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'macaroni_ckeditor_lightbox') . '/js/plugins/drupallightbox/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    $format = $editor->getFilterFormat();
    return [
      // Only enable those parts of DrupalImageCaption for which the
      // corresponding Drupal text filters are enabled.
      'drupalLightbox_lightboxFilterEnabled' => $format->filters('filter_lightbox')->status,

    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCssFiles(Editor $editor) {
    return [
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
    if (!$editor->hasAssociatedFilterFormat()) {
      return FALSE;
    }

    // Automatically enable this plugin if the text format associated with this
    // text editor uses the filter_align or filter_caption filter and the
    // DrupalImage button is enabled.
    $format = $editor->getFilterFormat();
    if ($format->filters('filter_lightbox')->status) {
      $enabled = FALSE;
      $settings = $editor->getSettings();
      foreach ($settings['toolbar']['rows'] as $row) {
        foreach ($row as $group) {
          foreach ($group['items'] as $button) {
            if ($button === 'DrupalImage') {
              $enabled = TRUE;
            }
          }
        }
      }
      return $enabled;
    }

    return FALSE;
  }

}
