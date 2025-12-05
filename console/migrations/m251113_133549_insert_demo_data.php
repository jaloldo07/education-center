<?php
use yii\db\Migration;

class m251113_133549_insert_demo_data extends Migration
{
    public function up()
    {
        $time = time();
        
        // Insert admin user
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'email' => 'admin@education.com',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin123'),
            'role' => 'admin',
            'status' => 10,
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Insert teachers
        $teachers = [
            ['John Smith', 'Mathematics', 10, '+998901234567', 'john@education.com', 'Expert in Advanced Mathematics', 4.8],
            ['Sarah Johnson', 'English', 8, '+998901234568', 'sarah@education.com', 'IELTS & TOEFL Specialist', 4.9],
            ['Michael Brown', 'Physics', 12, '+998901234569', 'michael@education.com', 'PhD in Quantum Physics', 4.7],
            ['Emma Wilson', 'Chemistry', 6, '+998901234570', 'emma@education.com', 'Organic Chemistry Expert', 4.6],
            ['David Lee', 'Computer Science', 15, '+998901234571', 'david@education.com', 'Software Engineering Professional', 5.0],
        ];

        foreach ($teachers as $teacher) {
            $this->insert('{{%teacher}}', [
                'full_name' => $teacher[0],
                'subject' => $teacher[1],
                'experience_years' => $teacher[2],
                'phone' => $teacher[3],
                'email' => $teacher[4],
                'bio' => $teacher[5],
                'rating' => $teacher[6],
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }

        // Insert courses
        $courses = [
            ['Advanced Mathematics', 'Calculus, Algebra, Geometry', 6, 1200000, 1],
            ['IELTS Preparation', 'Complete IELTS training course', 4, 800000, 2],
            ['Physics Fundamentals', 'Classical and Modern Physics', 8, 1500000, 3],
            ['Organic Chemistry', 'Complete organic chemistry course', 6, 1000000, 4],
            ['Web Development', 'Full-stack web development', 10, 2000000, 5],
            ['English Speaking Club', 'Improve your speaking skills', 3, 500000, 2],
            ['SAT Preparation', 'Complete SAT preparation', 5, 1300000, 1],
        ];

        foreach ($courses as $course) {
            $this->insert('{{%course}}', [
                'name' => $course[0],
                'description' => $course[1],
                'duration' => $course[2],
                'price' => $course[3],
                'teacher_id' => $course[4],
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }
    }

    public function down()
    {
        $this->delete('{{%course}}');
        $this->delete('{{%teacher}}');
        $this->delete('{{%user}}', ['username' => 'admin']);
    }
}