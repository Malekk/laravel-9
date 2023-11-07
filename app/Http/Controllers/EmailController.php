<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Jobs\SendEmailJob;
use App\Mail\Mail;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;

class EmailController extends Controller
{
    public function send(EmailRequest $request)
    {
        $i = 0;
        foreach ($request->emails as $email) {
            $i++;
            $mail = new Mail($email['email'], $email['subject'], $email['body']);

            /** @var ElasticsearchHelperInterface $elasticsearchHelper */
            $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
            $idEmail = $elasticsearchHelper->storeEmail($mail->body, $mail->subject, $mail->email);

            /** @var RedisHelperInterface $redisHelper */
            $redisHelper = app()->make(RedisHelperInterface::class);
            $redisHelper->storeRecentMessage($idEmail, $mail->subject, $mail->email);

            SendEmailJob::dispatch($mail);
        }

        return response()->json(['message' => 1 === $i ? 'Email stored successfully.' : $i.' emails stored successfully.']);
    }

    //  TODO - BONUS: implement list method
    public function list()
    {

    }
}
