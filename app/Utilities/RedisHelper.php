<?php

namespace App\Utilities;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Redis;

class RedisHelper implements RedisHelperInterface
{
    private const KEY_EMAIL = 'email';

    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void
    {
        $key = self::KEY_EMAIL . ':' . $id;
        Redis::set($key, json_encode([
            'id' => $id,
            'message_subject' => $messageSubject,
            'to_email_address' => $toEmailAddress,
        ]));
    }
}
