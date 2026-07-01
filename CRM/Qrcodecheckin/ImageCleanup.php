<?php

use CRM_Qrcodecheckin_ExtensionUtil as E;
use Civi\Api4\Participant;

class CRM_Qrcodecheckin_ImageCleanup {

  /**
   * Delete QR code images for participants whose event ended at least $days ago.
   *
   * Events without an end_date are always skipped.
   *
   * @param int $days
   * @return int Number of images deleted.
   */
  public static function cleanup(int $days = 30): int {
    $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));

    $participants = Participant::get(FALSE)
      ->addSelect('id', 'contact_id.hash', 'QRCode.QRCode_Public_link')
      ->addWhere('QRCode.QRCode_Public_link', 'IS NOT NULL')
      ->addWhere('event_id.end_date', 'IS NOT NULL')
      ->addWhere('event_id.end_date', '<', $cutoff)
      ->setLimit(0)
      ->execute();

    $deleted = [];
    foreach ($participants as $participant) {
      $code = hash('sha256', $participant['id'] . $participant['contact_id.hash'] . CIVICRM_SITE_KEY);
      qrcodecheckin_delete_image($code);
      $deleted[] = $participant['id'];
    }

    if (!empty($deleted)) {
      Participant::update(FALSE)
        ->addValue('QRCode.QRCode_Public_link', NULL)
        ->addWhere('id', 'IN', $deleted)
        ->execute();
    }

    return count($deleted);
  }

}
