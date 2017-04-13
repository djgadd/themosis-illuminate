<?php

namespace KeltieCochrane\Illuminate\Mail\Transport;

use Swift_Mime_Message;
use Illuminate\Mail\Transport\Transport;

class WpMailTransport extends Transport
{
  /**
   * Send the given Message.
   *
   * Recipient/sender data will be retrieved from the Message API.
   * The return value is the number of recipients who were accepted for delivery.
   *
   * @param Swift_Mime_Message $msg
   * @param string[]           $failedRecipients An array of failures by-reference
   *
   * @return int
   */
  public function send(Swift_Mime_Message $msg, &$failedRecipients = null)
  {
    $this->beforeSendPerformed($msg);

    // Send the message using wp_mail
    if (wp_mail(array_keys($msg->getTo()), $msg->getSubject(), $msg->toString(), $msg->getHeaders()->toString(), [])) {
      $this->sendPerformed($msg);
    }

    return $this->numberOfRecipients($msg);
  }
}
