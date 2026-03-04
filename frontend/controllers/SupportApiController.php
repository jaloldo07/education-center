<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use common\models\SupportTicket;

class SupportApiController extends Controller
{
    // 1. Xavfsizlikni o'chiramiz (Chat uchun)
    public $enableCsrfValidation = false;

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
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Get user's tickets
     */
    public function actionGetTickets()
    {
        $userId = Yii::$app->user->id;
        $tickets = SupportTicket::find()
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

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
     * O'QILMAGANLAR SONI (Nomini o'zgartirdik!)
     * Oldingi 'get-unread-count' 403 xato berayotgan edi.
     */
    public function actionCount()
    {
        $userId = Yii::$app->user->id;
        // Status 'replied' bo'lgan xabarlarni sanaymiz (agar admin javob yozsa)
        // Yoki 'open' emasligini tekshiramiz
        $count = SupportTicket::find()
            ->where(['user_id' => $userId])
            ->andWhere(['not', ['admin_reply' => null]]) // Javob borlarini sanash
            ->andWhere(['status' => 'replied']) // Statusi replied bo'lsa
            ->count();

        return ['success' => true, 'count' => (int)$count];
    }

    /**
     * Create ticket
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $subject = $request->post('subject');
        $message = $request->post('message');

        if (empty($subject)) {
            $subject = $request->getBodyParam('subject');
            $message = $request->getBodyParam('message');
        }

        if (empty($subject) || empty($message)) {
            return ['success' => false, 'error' => 'Mavzu va xabar kiritilishi shart!'];
        }

        $ticket = new SupportTicket();
        $ticket->user_id = Yii::$app->user->id;
        $ticket->subject = $subject;
        $ticket->message = $message;

        // ----------------------------------------------------
        // MUHIM TUZATISH: 0 emas, 'open' deb yozamiz!
        // Chunki sizning bazangizda status so'z bilan yozilgan.
        // ----------------------------------------------------
        $ticket->status = 'open'; 
        
        $ticket->created_at = time();
        $ticket->updated_at = time();

        if ($ticket->save()) {
            return [
                'success' => true,
                'message' => 'Ticket created successfully',
                'ticket' => [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'created_at' => Yii::$app->formatter->asRelativeTime($ticket->created_at),
                ]
            ];
        }

        return ['success' => false, 'error' => $ticket->errors];
    }
    
    public function actionDeleteTicket()
    {
        $ticketId = Yii::$app->request->post('ticket_id');
        $ticket = SupportTicket::findOne(['id' => $ticketId, 'user_id' => Yii::$app->user->id]);
        
        if ($ticket && $ticket->delete()) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'O\'chirib bo\'lmadi'];
    }
}