<?php

namespace App\Utilities;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\Client;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    private const INDEX = 'emails';
    private const TYPE = 'email';

    public function __construct(protected Client $elasticsearch)
    {
    }

    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
        $params = [
            'body' => [
                'message_body' => $messageBody,
                'message_subject' => $messageSubject,
                'to_email_address' => $toEmailAddress,
            ],
            'index' => self::INDEX,
        ];

        $response = $this->elasticsearch->index($params);

        return $response['_id'];
    }
}
