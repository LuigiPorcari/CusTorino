<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as HttpClient;

class BrevoMailer
{
    protected $apiInstance;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
        $this->apiInstance = new TransactionalEmailsApi(new HttpClient(), $config);
    }

    public function sendEmail($toEmail, $subject, $view, $data = [])
    {
        // Renderizza la vista Blade in HTML
        $htmlContent = view($view, $data)->render();

        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $subject,
            'sender' => ['name' => 'CUSTORINO', 'email' => config('mail.from.address')],
            'to' => [['email' => $toEmail]],
            'htmlContent' => $htmlContent
        ]);

        return $this->apiInstance->sendTransacEmail($sendSmtpEmail);
    }
}
