<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int|null $role
 * @property string|null $date_start
 * @property string|null $date_end
 * @property string $email
 * @property string|null $name
 * @property string $auth_key
 * @property string|null $password_reset_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $referer_link
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['date_start', 'date_end'], 'safe'],
            [['username', 'password', 'email', 'name', 'password_reset_token', 'referer_link'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'role' => 'Role',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'email' => 'Email',
            'name' => 'Name',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'referer_link' => 'Referer Link',
        ];
    }
}
