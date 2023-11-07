<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Mail\Mail;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;

class EmailController extends Controller
{
    // TODO: finish implementing send method
    public function send(EmailRequest $request)
    {
        $i = 0;
        foreach ($request->emails as $email) {
            $i++;
            $mail = new Mail($email['email'], $email['subject'], $email['body']);

            /** @var ElasticsearchHelperInterface $elasticsearchHelper */
            $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
            $elasticsearchHelper->storeEmail($mail->body, $mail->subject, $mail->email);

            /** @var RedisHelperInterface $redisHelper */
            // $redisHelper = app()->make(RedisHelperInterface::class);
            // TODO: Create implementation for storeRecentMessage and uncomment the following line
            // $redisHelper->storeRecentMessage(...);
        }

        return response()->json(['message' => 1 === $i ? 'Email stored successfully.' : $i.' emails stored successfully.']);
    }

    //  TODO - BONUS: implement list method
    public function list()
    {

    }
}
