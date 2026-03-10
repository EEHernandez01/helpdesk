<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use App\Services\GraphMailer;

class GraphTransport implements TransportInterface
{
    protected GraphMailer $mailer;

    public function __construct(GraphMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send the given message.
     *
     * @param  RawMessage  $message
     * @param  Envelope|null  $envelope
     * @return SentMessage|null
     */
    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        if ($message instanceof Email) {
            // From
            $from = '';
            $fromAddrs = $message->getFrom();
            if (!empty($fromAddrs)) {
                $first = array_values($fromAddrs)[0];
                $from = method_exists($first, 'getAddress') ? $first->getAddress() : (string)$first;
            }

            // To
            $to = [];
            foreach ($message->getTo() ?? [] as $addr) {
                $to[] = method_exists($addr, 'getAddress') ? $addr->getAddress() : (string)$addr;
            }

            // Cc
            $cc = [];
            foreach ($message->getCc() ?? [] as $addr) {
                $cc[] = method_exists($addr, 'getAddress') ? $addr->getAddress() : (string)$addr;
            }

            // Bcc
            $bcc = [];
            foreach ($message->getBcc() ?? [] as $addr) {
                $bcc[] = method_exists($addr, 'getAddress') ? $addr->getAddress() : (string)$addr;
            }

            $subject = $message->getSubject();

            // Body: prefer HTML
            $body = '';
            $isHtml = false;
            if (method_exists($message, 'getHtmlBody') && $message->getHtmlBody()) {
                $body = $message->getHtmlBody();
                $isHtml = true;
            } elseif (method_exists($message, 'getTextBody') && $message->getTextBody()) {
                $body = $message->getTextBody();
            } else {
                // Fallback to string cast
                $body = (string) $message->getBody();
                $isHtml = false;
            }

            // Use sender argument or from
            $sender = $from ?: config('mail.from.address');

            // Send via GraphMailer
            $this->mailer->send($subject, $to, $body, $isHtml, $sender, $cc, $bcc);
        }

        // Return SentMessage as required by TransportInterface
        return new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    public function __toString(): string
    {
        return 'graph';
    }
}
