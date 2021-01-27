<?php

namespace Drupal\macaroni_ckeditor_lightbox\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a filter to align elements.
 *
 * @Filter(
 *   id = "filter_lightbox",
 *   title = @Translation("Apply Lightbox filter to images"),
 *   description = @Translation("Uses a <code>data-lightbox</code> attribute on <code>&lt;img&gt;</code> tags to enable lightbox on images. Note: if Macaroni Filter Image is enabled this filter needs to be placed before"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE
 * )
 */
class FilterLightbox extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (stristr($text, 'data-lightbox') !== FALSE) {
      $dom = Html::load($text);
      $xpath = new \DOMXPath($dom);
      $i=0;
      foreach ($xpath->query('//*[@data-lightbox]') as $node) {
        // Read the data-align attribute's value, then delete it.
        $hasLightbox = $node->getAttribute('data-lightbox') == 'true';
        $node->removeAttribute('data-lightbox');

        if($hasLightbox) {
          $src = $node->getAttribute('src');
          $title = $node->getAttribute('alt');

          $element = $dom->createElement('a');
          $element->setAttribute('href', $src);
          $element->setAttribute('data-lightbox', 'image-'.$i);
          $element->setAttribute('data-title', $title);
          $node->parentNode->replaceChild($element, $node);
          $element->appendChild($node);
          $i++;
        }
      }
      $result->setProcessedText(Html::serialize($dom));
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    if ($long) {
      return $this->t('Enable lightbox2 on images');
    }
    else {
      return $this->t('You can enable lightbox2 on images.');
    }
  }

}
