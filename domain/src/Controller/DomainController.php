<?php
/**
 * @file
 * Contains \Drupal\domain\Controller\DomainController.
 */

namespace Drupal\domain\Controller;

use Drupal\domain\DomainInterface;
use Drupal\Core\Routing\UrlGeneratorTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Controller routines for AJAX callbacks for domain actions.
 */
class DomainController {

  use StringTranslationTrait;
  use UrlGeneratorTrait;

  /**
   * Handles AJAX operations from the overview form.
   *
   * @param \Drupal\domain\DomainInterface
   *   A domain record object.
   * @param $op
   *   The operation being performed, either 'default' to make the domain record
   *   the default, 'enable' to enable the domain record, or 'disable' to
   *   disable the domain record.
   *
   *   Note: The delete action is handled by the entity form system.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to redirect back to the domain record list.
   *   Supported by the UrlGeneratorTrait.
   *
   * @see \Drupal\domain\DomainListBuilder
   */
  public function ajaxOperation(DomainInterface $domain, $op = NULL) {
    $success = FALSE;
    switch($op) {
      case 'default':
        $domain->saveDefault();
        $message = $this->t('Domain record set as default');
        if ($domain->isDefault()) {
          $success = TRUE;
        }
        break;
      case 'enable':
        $domain->enable();
        $message = $this->t('Domain record has been enabled.');
        if ($domain->status()) {
          $success = TRUE;
        }
        break;
      case 'disable':
        $domain->disable();
        $message = $this->t('Domain record has been disabled.');
        if (!$domain->status()) {
          $success = TRUE;
        }
        break;
    }

    // Set a message.
    if ($success) {
      drupal_set_message($message);
    }
    else {
      drupal_set_message($this->t('The operation failed.'));
    }

    // Return to the invoking page.
    return $this->redirect('domain.admin');
  }

}
