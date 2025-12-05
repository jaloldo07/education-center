<?php
/**
 * RESTORE ALL USERS - RUN THIS SCRIPT!
 * Usage: php restore_users.php
 */

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/common/config/bootstrap.php');
require(__DIR__ . '/frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common/config/main.php'),
    require(__DIR__ . '/common/config/main-local.php'),
    require(__DIR__ . '/frontend/config/main.php'),
    require(__DIR__ . '/frontend/config/main-local.php')
);

new yii\web\Application($config);

use common\models\User;
use common\models\Teacher;
use common\models\Student;

echo "🔧 RESTORING ALL USERS...\n\n";

// Password for all users
$defaultPassword = 'password123';

// ============== 1. CREATE ADMIN ==============
echo "1️⃣ Creating Admin...\n";
$admin = User::findOne(['username' => 'admin']);
if (!$admin) {
    $admin = new User();
    $admin->username = 'admin';
    $admin->email = 'admin@education.com';
    $admin->setPassword('admin123');
    $admin->generateAuthKey();
    $admin->role = 'admin';
    $admin->status = 10;
    if ($admin->save()) {
        echo "✅ Admin created: admin / admin123\n";
    } else {
        echo "❌ Admin failed: " . json_encode($admin->errors) . "\n";
    }
} else {
    echo "✅ Admin already exists\n";
}

// ============== 2. CREATE TEACHERS ==============
echo "\n2️⃣ Creating Teachers...\n";
$teachers = [
    ['username' => 'teacher1', 'email' => 'john@education.com', 'name' => 'John Smith', 'subject' => 'Mathematics'],
    ['username' => 'teacher2', 'email' => 'sarah@education.com', 'name' => 'Sarah Johnson', 'subject' => 'English'],
    ['username' => 'teacher3', 'email' => 'michael@education.com', 'name' => 'Michael Brown', 'subject' => 'Physics'],
    ['username' => 'teacher4', 'email' => 'emma@education.com', 'name' => 'Emma Wilson', 'subject' => 'Chemistry'],
    ['username' => 'teacher5', 'email' => 'david@education.com', 'name' => 'David Lee', 'subject' => 'Computer Science'],
];

foreach ($teachers as $t) {
    $user = User::findOne(['username' => $t['username']]);
    if (!$user) {
        $user = new User();
        $user->username = $t['username'];
        $user->email = $t['email'];
        $user->setPassword($defaultPassword);
        $user->generateAuthKey();
        $user->role = 'teacher';
        $user->status = 10;
        
        if ($user->save()) {
            echo "✅ User created: {$t['username']} / $defaultPassword\n";
            
            // Create teacher profile
            $teacher = Teacher::findOne(['email' => $t['email']]);
            if (!$teacher) {
                $teacher = new Teacher();
                $teacher->full_name = $t['name'];
                $teacher->subject = $t['subject'];
                $teacher->email = $t['email'];
                $teacher->phone = '+998901234567';
                $teacher->experience_years = 5;
                $teacher->bio = 'Experienced teacher';
                $teacher->rating = 4.5;
                if ($teacher->save()) {
                    echo "   ✅ Teacher profile created\n";
                } else {
                    echo "   ❌ Teacher profile failed: " . json_encode($teacher->errors) . "\n";
                }
            }
        } else {
            echo "❌ Failed {$t['username']}: " . json_encode($user->errors) . "\n";
        }
    } else {
        echo "✅ {$t['username']} already exists\n";
    }
}

// ============== 3. CREATE STUDENTS ==============
echo "\n3️⃣ Creating Students...\n";
$students = [
    ['username' => 'student1', 'email' => 'ali@student.com', 'name' => 'Ali Valiyev'],
    ['username' => 'student2', 'email' => 'fatima@student.com', 'name' => 'Fatima Karimova'],
    ['username' => 'student3', 'email' => 'aziz@student.com', 'name' => 'Aziz Tursunov'],
    ['username' => 'student4', 'email' => 'zarina@student.com', 'name' => 'Zarina Salimova'],
    ['username' => 'student5', 'email' => 'bobur@student.com', 'name' => 'Bobur Rahimov'],
];

foreach ($students as $s) {
    $user = User::findOne(['username' => $s['username']]);
    if (!$user) {
        $user = new User();
        $user->username = $s['username'];
        $user->email = $s['email'];
        $user->setPassword($defaultPassword);
        $user->generateAuthKey();
        $user->role = 'student';
        $user->status = 10;
        
        if ($user->save()) {
            echo "✅ User created: {$s['username']} / $defaultPassword\n";
            
            // Create student profile
            $student = Student::findOne(['email' => $s['email']]);
            if (!$student) {
                $student = new Student();
                $student->full_name = $s['name'];
                $student->email = $s['email'];
                $student->phone = '+998901234567';
                $student->birth_date = '2000-01-01';
                $student->enrolled_date = date('Y-m-d');
                $student->address = 'Tashkent, Uzbekistan';
                
                echo "   📝 Attempting to save student: {$s['name']}\n";
                echo "   📝 Data: " . json_encode($student->attributes) . "\n";
                
                if ($student->save()) {
                    echo "   ✅ Student profile created\n";
                } else {
                    echo "   ❌ Student profile failed!\n";
                    echo "   ❌ Errors: " . json_encode($student->errors) . "\n";
                    foreach ($student->errors as $field => $errors) {
                        echo "   ❌ Field '$field': " . implode(', ', $errors) . "\n";
                    }
                }
            }
        } else {
            echo "❌ Failed {$s['username']}: " . json_encode($user->errors) . "\n";
        }
    } else {
        echo "✅ {$s['username']} already exists\n";
    }
}

// ============== 4. CREATE DIRECTOR ==============
echo "\n4️⃣ Creating Director...\n";
$director = User::findOne(['username' => 'director']);
if (!$director) {
    $director = new User();
    $director->username = 'director';
    $director->email = 'director@education.com';
    $director->setPassword('director123');
    $director->generateAuthKey();
    $director->role = 'director';
    $director->status = 10;
    if ($director->save()) {
        echo "✅ Director created: director / director123\n";
    } else {
        echo "❌ Director failed: " . json_encode($director->errors) . "\n";
    }
} else {
    echo "✅ Director already exists\n";
}

echo "\n✅ ✅ ✅ RESTORATION COMPLETE! ✅ ✅ ✅\n\n";
echo "📋 LOGIN CREDENTIALS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "ADMIN:\n";
echo "  Username: admin\n";
echo "  Password: admin123\n\n";
echo "DIRECTOR:\n";
echo "  Username: director\n";
echo "  Password: director123\n\n";
echo "TEACHERS:\n";
echo "  teacher1, teacher2, teacher3, teacher4, teacher5\n";
echo "  Password: password123 (for all)\n\n";
echo "STUDENTS:\n";
echo "  student1, student2, student3, student4, student5\n";
echo "  Password: password123 (for all)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";