<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\Student;
use common\models\Teacher;
use common\models\Course;
use common\models\Payment;
use common\models\Enrollment;

class DashboardController extends Controller
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
                            // ✅ FIXED: Added null check for identity
                            if (Yii::$app->user->isGuest || !Yii::$app->user->identity) {
                                return false;
                            }
                            $role = Yii::$app->user->identity->role;
                            return in_array($role, ['director', 'admin']);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        try {
            $stats = [
                'totalStudents' => Student::find()->count(),
                'totalTeachers' => Teacher::find()->count(),
                'totalCourses' => Course::find()->count(),
                'activeEnrollments' => Enrollment::find()->where(['status' => 'active'])->count(),
                'yearlyIncome' => Payment::find()
                    ->where(['>=', 'payment_date', date('Y-01-01')])
                    ->sum('amount') ?? 0,
                'monthlyIncome' => Payment::find()
                    ->where(['>=', 'payment_date', date('Y-m-01')])
                    ->sum('amount') ?? 0,
            ];

            // ✅ SAFE VERSION: Monthly income data (original method)
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                
                try {
                    $income = Payment::find()
                        ->where(['like', 'payment_date', $month])
                        ->sum('amount') ?? 0;
                } catch (\Exception $e) {
                    Yii::warning("Month query error: {$month} - " . $e->getMessage(), __METHOD__);
                    $income = 0;
                }
                
                $monthlyData[] = [
                    'month' => date('M Y', strtotime($month . '-01')),
                    'income' => $income,
                ];
            }

            $topTeachers = Teacher::find()
                ->orderBy(['rating' => SORT_DESC])
                ->limit(5)
                ->all();

            // ✅ FIXED: Added proper eager loading
            $recentPayments = Payment::find()
                ->with(['student', 'course'])
                ->orderBy(['payment_date' => SORT_DESC])
                ->limit(10)
                ->all();

            return $this->render('index', [
                'stats' => $stats,
                'monthlyData' => $monthlyData,
                'topTeachers' => $topTeachers,
                'recentPayments' => $recentPayments,
            ]);

        } catch (\Exception $e) {
            // ✅ Log error for debugging
            Yii::error('Dashboard error: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), __METHOD__);
            
            // ✅ Show friendly error to user
            Yii::$app->session->setFlash('error', 'Error loading dashboard data. Please check logs.');
            
            // Return with empty data so page still renders
            return $this->render('index', [
                'stats' => [
                    'totalStudents' => 0,
                    'totalTeachers' => 0,
                    'totalCourses' => 0,
                    'activeEnrollments' => 0,
                    'yearlyIncome' => 0,
                    'monthlyIncome' => 0,
                ],
                'monthlyData' => [],
                'topTeachers' => [],
                'recentPayments' => [],
            ]);
        }
    }
}