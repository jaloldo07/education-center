<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use common\models\SupportTicket;

class SupportController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            return in_array(Yii::$app->user->identity->role, ['admin', 'director']);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * List all support tickets
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SupportTicket::find()->with('user')->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $stats = [
            'total' => SupportTicket::find()->count(),
            'open' => SupportTicket::find()->where(['status' => SupportTicket::STATUS_OPEN])->count(),
            'replied' => SupportTicket::find()->where(['status' => SupportTicket::STATUS_REPLIED])->count(),
            'closed' => SupportTicket::find()->where(['status' => SupportTicket::STATUS_CLOSED])->count(),
        ];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'stats' => $stats,
        ]);
    }

    /**
     * View ticket details
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Reply to ticket
     */
    public function actionReply($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->admin_reply = Yii::$app->request->post('admin_reply');
            $model->status = Yii::$app->request->post('status', SupportTicket::STATUS_REPLIED);
            $model->admin_replied_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Reply sent successfully');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('reply', [
            'model' => $model,
        ]);
    }

    /**
     * Update ticket status
     */
    public function actionUpdateStatus($id)
    {
        $model = $this->findModel($id);

        // POST dan status olish (agar yo'q bo'lsa 'closed')
        if (Yii::$app->request->isPost) {
            $model->status = Yii::$app->request->post('status', 'closed');
        } else {
            $model->status = Yii::$app->request->get('status', 'closed');
        }

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Ticket status updated successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update status.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Delete ticket
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Ticket deleted');

        return $this->redirect(['index']);
    }

    /**
     * Find model
     */
    protected function findModel($id)
    {
        if (($model = SupportTicket::findOne($id)) !== null) {
            return $model;
        }

        throw new \yii\web\NotFoundHttpException('Ticket not found');
    }
}
