<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Create permissions
        $manageTeachers = $auth->createPermission('manageTeachers');
        $manageTeachers->description = 'Manage teachers';
        $auth->add($manageTeachers);

        $manageStudents = $auth->createPermission('manageStudents');
        $manageStudents->description = 'Manage students';
        $auth->add($manageStudents);

        $manageCourses = $auth->createPermission('manageCourses');
        $manageCourses->description = 'Manage courses';
        $auth->add($manageCourses);

        $managePayments = $auth->createPermission('managePayments');
        $managePayments->description = 'Manage payments';
        $auth->add($managePayments);

        $viewOwnData = $auth->createPermission('viewOwnData');
        $viewOwnData->description = 'View own data';
        $auth->add($viewOwnData);

        // Create roles
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageTeachers);
        $auth->addChild($admin, $manageStudents);
        $auth->addChild($admin, $manageCourses);
        $auth->addChild($admin, $managePayments);

        $teacher = $auth->createRole('teacher');
        $auth->add($teacher);
        $auth->addChild($teacher, $viewOwnData);

        $student = $auth->createRole('student');
        $auth->add($student);
        $auth->addChild($student, $viewOwnData);

        echo "RBAC initialized successfully!\n";
    }
}