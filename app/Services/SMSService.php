<?php

namespace App\Services;

use Xenon\LaravelBDSms\Sender;
use Xenon\LaravelBDSms\Provider\BulkSmsBD;


class SMSService
{
    public function sendSMS($contact_no, $message)
    {
        $sender = Sender::getInstance();

        $sender->setProvider(BulkSmsBD::class);
        
        $sender->setConfig([
            'api_key' => config('sms.providers.Xenon\LaravelBDSms\Provider\BulkSmsBD.api_key'),
            'senderid' => config('sms.providers.Xenon\LaravelBDSms\Provider\BulkSmsBD.senderid'),
        ]);

        $sender->setMobile($contact_no);

        $sender->setMessage($message);

        $status = $sender->send();

        return $status;
    }
} 