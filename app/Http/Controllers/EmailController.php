<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Jobs\SendEmailJob;
use App\Mail\Mail;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function send(EmailRequest $request): JsonResponse
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

    public function list(Request $request): JsonResponse
    {
        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        $results = $elasticsearchHelper->search(
            $request->query('page', 1),
            $request->query('per_page', 10)
        );

        return response()->json($results);
    }
}
