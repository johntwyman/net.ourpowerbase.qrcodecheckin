<?php

use CRM_Qrcodecheckin_ExtensionUtil as E;

/**
 * Qrcodecheckin.CleanupImages API spec.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/      
 */
function _civicrm_api3_qrcodecheckin_Cleanupimages_spec(&$spec) {
  $spec['days'] = [
    'title' => E::ts('Days since event end'),
    'description' => E::ts('Delete images for events that ended at least this many days ago.'),
    'type' => CRM_Utils_Type::T_INT,
    'api.default' => 30,
  ];
}

/**
 * Qrcodecheckin.CleanupImages API
 *
 * @param array $params
 * @return array
 */
function civicrm_api3_qrcodecheckin_Cleanupimages($params) {
  $days = (int) ($params['days'] ?? 30);
  $count = CRM_Qrcodecheckin_ImageCleanup::cleanup($days);
  return civicrm_api3_create_success(['count' => $count], $params);
}
