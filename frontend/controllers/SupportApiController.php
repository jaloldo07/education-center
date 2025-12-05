<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use common\models\SupportTicket;

class SupportApiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Get user's tickets
     */
    public function actionGetTickets()
    {
        $userId = Yii::$app->user->id;
        $tickets = SupportTicket::getUserTickets($userId);

        $result = [];
        foreach ($tickets as $ticket) {
            $result[] = [
                'id' => $ticket->id,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'has_reply' => !empty($ticket->admin_reply),
                'created_at' => Yii::$app->formatter->asRelativeTime($ticket->created_at),
            ];
        }

        return ['success' => true, 'tickets' => $result];
    }

    /**
     * Get ticket details
     */
    public function actionGetTicket($id)
    {
        $ticket = SupportTicket::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (!$ticket) {
            return ['success' => false, 'error' => 'Not found'];
        }

        return [
            'success' => true,
            'ticket' => [
                'id' => $ticket->id,
                'subject' => $ticket->subject,
                'message' => $ticket->message,
                'status' => $ticket->status,
                'admin_reply' => $ticket->admin_reply,
                'created_at' => Yii::$app->formatter->asDatetime($ticket->created_at),
                'admin_replied_at' => $ticket->admin_replied_at ? Yii::$app->formatter->asDatetime($ticket->admin_replied_at) : null,
            ]
        ];
    }

    /**
     * Create ticket
     */
    public function actionCreate()
    {
        $subject = Yii::$app->request->post('subject');
        $message = Yii::$app->request->post('message');

        if (empty($subject) || empty($message)) {
            return ['success' => false, 'error' => 'Subject and message required'];
        }

        $ticket = new SupportTicket();
        $ticket->user_id = Yii::$app->user->id;
        $ticket->subject = $subject;
        $ticket->message = $message;
        $ticket->status = SupportTicket::STATUS_OPEN;

        if ($ticket->save()) {
            return [
                'success' => true,
                'message' => 'Ticket created successfully',
                'ticket' => [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                ]
            ];
        }

        return ['success' => false, 'error' => 'Failed to create ticket'];
    }
}
