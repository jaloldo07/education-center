<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

class TeacherApplication extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public $cvFileUpload;

    public static function tableName()
    {
        return '{{%teacher_application}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['full_name', 'email', 'phone', 'subject', 'experience_years', 'education', 'bio'], 'required'],
            [['experience_years', 'reviewed_by', 'reviewed_at'], 'integer'],
            [['education', 'bio', 'admin_comment'], 'string'],
            [['full_name', 'email', 'subject', 'cv_file'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED]],
            [['cvFileUpload'], 'file', 'extensions' => 'pdf, doc, docx', 'maxSize' => 1024 * 1024 * 5], // 5MB
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'subject' => 'Subject/Specialization',
            'experience_years' => 'Years of Experience',
            'education' => 'Education Background',
            'bio' => 'About Yourself',
            'cv_file' => 'CV File',
            'cvFileUpload' => 'Upload CV (PDF/DOC)',
            'status' => 'Status',
            'admin_comment' => 'Admin Comment',
            'created_at' => 'Applied At',
            'reviewed_at' => 'Reviewed At',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $filename = 'cv_' . time() . '_' . uniqid() . '.' . $this->cvFileUpload->extension;
            $path = Yii::getAlias('@frontend/web/uploads/cv/') . $filename;

            if (!is_dir(Yii::getAlias('@frontend/web/uploads/cv/'))) {
                mkdir(Yii::getAlias('@frontend/web/uploads/cv/'), 0777, true);
            }

            if ($this->cvFileUpload->saveAs($path)) {
                $this->cv_file = $filename;
                return true;
            }
        }
        return false;
    }

    public function getCvUrl()
    {
        return $this->cv_file ? Yii::getAlias('@web/uploads/cv/') . $this->cv_file : null;
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public static function getStatusBadgeClass($status)
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
        ][$status] ?? 'secondary';
    }
}
