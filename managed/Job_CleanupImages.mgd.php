<?php

use CRM_Qrcodecheckin_ExtensionUtil as E;

return [
  [
    'name' => 'Job_QRCodeCleanupImages',
    'entity' => 'Job',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'run_frequency' => 'Weekly',
        'name' => E::ts('QR Code: Clean up old images'),
        'description' => E::ts('Deletes QR code image files for events that have ended. Events without an end date are never cleaned up.'),
        'api_entity' => 'Qrcodecheckin',
        'api_action' => 'cleanupimages',
        'parameters' => 'days=30',
        'is_active' => FALSE,
      ],
      'match' => ['name'],
    ],
  ],
];
