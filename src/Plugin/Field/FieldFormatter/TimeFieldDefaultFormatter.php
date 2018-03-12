<?php /**
 * @file
 * Contains \Drupal\timefield\Plugin\Field\FieldFormatter\TimeFieldDefaultFormatter.
 */

namespace Drupal\timefield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldFormatter(
 *  id = "timefield_default_formatter",
 *  label = @Translation("Timefield formatter"),
 *  field_types = {"timefield"}
 * )
 */
class TimeFieldDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {

    return array(
      'display_format' => array(
        'hour' => 'g',
        'minute' => 'i',
        'separator' => ':',
        'period' => 'a',
        'period_separator' => ''
      ),
    ) + parent::defaultSettings();
  }


  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state); // TODO: Change the autogenerated stub
    $settings = $this->getSettings();
    $element += _timefield_display_format_form('display_format', "Individual Time Display Settings", $settings);
    return $element;
  }

  public function settingsSummary() {
    $summart = parent::settingsSummary(); // TODO: Change the autogenerated stub
    $settings = $this->getSettings();
    $current_time = timefield_time_to_integer(date('g:ia', strtotime("now")));
    $summary[] = $this::t('Current Format') . ': ' . timefield_integer_to_time($settings['display_format'], $current_time);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $settings = $this->getSettings();
    $field_settings = $this->getFieldSettings();
    $element = array();
    foreach ($items as $delta => $item) {
      if (!empty($item->value)) {
        $element[$delta]['#markup'] = trim(timefield_integer_to_time($settings['display_format'], $item->value));
      }
      if (!empty($item->value2)) {
        $element[$delta]['#markup'] = (!empty($item->value)) ?
          $element[$delta]['#markup'].'-'.trim(timefield_integer_to_time($settings['display_format'], $item->value2)) :
          trim(timefield_integer_to_time($settings['display_format'], $item->value2));
      }
    }


    return $element;
  }
}
