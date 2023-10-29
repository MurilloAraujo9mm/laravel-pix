<?php

namespace App\Http\Controllers;

use App\Services\AMQP\RabbitMQ;
use Illuminate\Http\JsonResponse;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class QueueController
 * Controller for queue-related operations.
 */
class QueueController extends Controller
{
    /**
     * Checks the queue for pending messages and returns the content of the next message.
     * If messages exist in the queue, it peeks the next message, acknowledges it, and returns its content.
     * If no messages are found, a simple message indicating an empty queue is returned.
     * @param RabbitMQ $rabbitMQService The service responsible for handling AMQP operations.
     * @return JsonResponse
     */
    public function checkQueue(RabbitMQ $rabbitMQService): JsonResponse
    {
        $messageCount = $rabbitMQService->getMessageCount(env('RABBITMQ_QUEUE', 'default_queue_name'));
    
        if ($messageCount > 0) {

            $message = $rabbitMQService->peekMessage(env('RABBITMQ_QUEUE', 'default_queue_name'));

            if ($message instanceof AMQPMessage) {

                $rabbitMQService->acknowledgeMessage($message);
                $messageContent = json_decode($message->body, true);

                return response()->json([
                    'message' => 'Há mensagens na fila',
                    'count' => $messageCount,
                    'next_message_content' => $messageContent
                ]);
            }
        }
    
        return response()->json(['message' => 'Não há mensagens na fila'], Response::HTTP_NOT_FOUND);
    }
}
