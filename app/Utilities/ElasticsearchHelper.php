<?php

namespace App\Utilities;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\Client;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    private const INDEX = 'emails';

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

    public function search(int $page = 1, int $perPage = 10): array
    {
        $search = $this->elasticsearch->search([
            'index' => self::INDEX,
            'body' => [
                "from" => ($page - 1) * $perPage,
                "size" => $perPage,
            ]
        ]);

        $results = $search['hits']['hits'];
        return [
            'total' => $search['hits']['total']['value'],
            'page' => $page,
            'per_page' => $perPage,
            'results' => array_map(function (array $item) {
                return [
                    'body' => $item['_source']['message_body'],
                    'subject' => $item['_source']['message_subject'],
                    'email' => $item['_source']['to_email_address'],
                ];
            }, $results)
        ];
    }
}
