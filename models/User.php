<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use app\components\extend\Html;
use yii\rbac\Item;
use app\modules\admin\components\rbac\ItemHelper;
use app\models\File;
use settings\UserSettings;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $country
 * @property string $city
 * @property integer $gender
 * @property integer $skype
 * @property integer $about
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $avatar
 */
class User extends \app\components\extend\Model implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_DISABLED = 1;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 1;
    const ROLE_ADMIN = 99;

    public $rbacRole;

    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    /**
     * 
     * @param mixed $gender
     */
    public function getGenderLabels($gender = null)
    {
        $ar = [
            self::GENDER_MALE => yii::$app->l->t('male'),
            self::GENDER_FEMALE => yii::$app->l->t('female'),
        ];

        return $gender !== null ? $ar[$gender] : $ar;
    }

    public function init()
    {
        parent::init();
    }

    /**
     * @param integer/boolean $status
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return type
     */
    public function getStatusLabels($status = false, $withLiveEdit = true)
    {
        $ar = [
            self::STATUS_ACTIVE => yii::$app->l->t('user active', ['update' => $withLiveEdit]),
            self::STATUS_DISABLED => yii::$app->l->t('user disabled', ['update' => $withLiveEdit]),
            self::STATUS_DELETED => yii::$app->l->t('user deleted', ['update' => $withLiveEdit]),
        ];
        return $status !== false ? $ar[$status] : $ar;
    }

    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'settings' => [
                'class' => settings\UserSettings::className(),
            ],
            'saveFiles' => [
                'class' => behaviors\FileSaveBehavior::className(),
                'fileAttributes' => ['avatar']
            ],
                ] + parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['avatar'], 'file', 'extensions' => File::imageExtensions(true), 'skipOnEmpty' => true],
                [['first_name', 'last_name', 'email'], 'required'],
                [['password'], 'checkEmptyPassword', 'skipOnEmpty' => false],
                [['status', 'created_at', 'updated_at'], 'integer'],
                [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
                [['auth_key', 'password'], 'string', 'max' => 32],
                ['status', 'default', 'value' => self::STATUS_ACTIVE],
                ['role', 'default', 'value' => self::ROLE_USER],
                [['username', 'email'], 'filter', 'filter' => 'trim'],
                [['email'], 'unique', 'targetClass' => 'app\models\User'],
                ['username', 'string', 'min' => 2, 'max' => 255],
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'unique', 'targetClass' => 'app\models\User'],
                ['password', 'string', 'min' => 6],
                [['first_name', 'last_name', 'country', 'city', 'skype', 'gender', 'about'], 'string', 'max' => 200],
                ['rbacRole', 'fakeRule'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['profile'] = array_keys($this->attributeLabels(['status', 'role', 'rbacRole']));
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels($except = false)
    {
        $ar = [
            'username' => yii::$app->l->t('username'),
            'first_name' => yii::$app->l->t('first name'),
            'last_name' => yii::$app->l->t('last name'),
            'country' => yii::$app->l->t('country'),
            'city' => yii::$app->l->t('city'),
            'skype' => yii::$app->l->t('skype account'),
            'gender' => yii::$app->l->t('gender'),
            'about' => yii::$app->l->t('about me'),
            'password' => yii::$app->l->t('password'),
            'email' => yii::$app->l->t('email'),
            'avatar' => yii::$app->l->t('avatar'),
            'role' => yii::$app->l->t('role'),
            'rbacRole' => yii::$app->l->t('role'),
            'status' => yii::$app->l->t('status'),
        ];
        if ($except !== false && is_array($except))
            foreach ($except as $k)
                unset($ar[$k]);

        return $ar;
    }

    /* relations */

    public function getAvatarFile()
    {
        return $this->hasOne(File::className(), ['name' => 'avatar']);
    }

    public function getAuthAssignment()
    {
        return $this->hasOne('\backend\models\AuthAssignment', ['user_id' => 'id']);
    }

    /* relations end */

    public function checkEmptyPassword($attribute, $param)
    {
        if (($this->isNewRecord || !$this->password_hash || !$this->auth_key) && trim($this->$attribute) == '') {
            $this->addError($attribute, yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel($attribute)]));
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => trim($username), 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => trim($email), 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * encode password
     */
    public function encodePassword()
    {
        if ($this->password && trim($this->password) != '') {
            $this->setPassword($this->password);
            $this->generateAuthKey();
        }
    }

    /**
     * 
     * @param type $insert
     * @return type
     */
    public function beforeSave($insert)
    {
        $this->encodePassword();
        if ($this->role != self::ROLE_ADMIN)
            $this->role = self::ROLE_USER;
        return parent::beforeSave($insert);
    }

    /**
     * button that opens role asignment modal
     * @param array $options html attributes (yii format)
     * @return string
     */
    public function getRolesButton($options = [])
    {
        $item = new ItemHelper();
        $assignments = [];
        foreach ($item->getAssignments($this->primaryKey) as $k => $v) {
            $assignments[] = $k;
        }
        $options['title'] = yii::$app->l->t('roles', ['update' => false]);
        if (!array_key_exists('class', $options)) {
            $options['class'] = 'user-assignments-modal-button';
        } else {
            $options['class'] .= ' user-assignments-modal-button';
        }
        $options['onclick'] = 'showUserRolesModal($(this));return false;';
        $options['data'] = [
            'user-name' => $this->username,
            'user-id' => $this->primaryKey,
            'assignments' => $assignments,
        ];
        return Html::a(Html::ico('key'), '#', $options);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->rbacRole = $this->assignedRolesArray();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveRbacRoles();
    }

    public function saveRbacRoles()
    {
        $defaultRole = $this->getSetting('default_user_role');
        if (!$this->rbacRole && $defaultRole) {
            $this->rbacRole = $defaultRole;
        }
        if ($this->rbacRole && (yii::$app->user->can('rbac-assignment') || $this->rbacRole == $defaultRole)) {
            (new ItemHelper())->revokeAll($this->primaryKey);
            foreach ($this->rbacRole as $k => $v) {
                $item = new ItemHelper(['name' => $v]);
                $item->assign($item, (int) $this->primaryKey);
            }
        }
    }

    /**
     * get roles assigned to this user
     * @return array
     */
    public function assignedRolesArray()
    {
        $tmp = [];
        $item = new ItemHelper();
        $item->getRolesByUser($this->primaryKey);
        if (count($item->items) > 0) {
            foreach ($item->items[Item::TYPE_ROLE] as $k) {
                $tmp[] = $k->name;
            }
        }
        return $tmp;
    }

    /**
     * get all titles of roles assigned to this user
     * @param array $options html attributes (yii format)
     * @return string html tag
     */
    public function assignedRoles($options = [])
    {
        $tmp = '';
        $item = new ItemHelper();
        $item->getRolesByUser($this->primaryKey);
        $elementClass = 'text-success';
        array_key_exists('class', $options) ? $elementClass = $options['class'] : $options['class'] = $elementClass;
        if (count($item->items) > 0) {
            foreach ($item->items[Item::TYPE_ROLE] as $k) {
                $options['data'] = ['name' => $k->name];
                $tmp .= Html::tag('div', $k->getTitle(), $options);
            }
        }
        return Html::tag('div', ($tmp === '' ? Html::tag('div', '', ['class' => 'text-danger']) : $tmp), [
                    'class' => 'user-assigned-roles',
                    'data' => [
                        'user-id' => $this->primaryKey,
                        'element' => 'div',
                        'element-class' => $elementClass,
                    ]
        ]);
    }

    /**
     * return available roles
     * @return array
     */
    public function getAvailableRoles()
    {
        $item = new ItemHelper();
        $item->getRoles();
        $ar = [];
        if (count($item->items) > 0) {
            foreach ($item->items[Item::TYPE_ROLE] as $k) {
                $ar[$k->name] = $k->getTitle();
            }
        }
        return $ar;
    }

    /**
     * get user full name
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * render image
     * @param type $options
     * @return string
     */
    public function renderAvatar($options = [])
    {
        if (!array_key_exists('size', $options))
            $options['size'] = File::SIZE_SM;
        if (!array_key_exists('title', $options))
            $options['title'] = $this->username;
        if (!array_key_exists('alt', $options))
            $options['alt'] = $this->username;
        if ($image = $this->getFile('avatar'))
            return $image->renderImage($options);
    }

    /**
     * get friends query
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        $friends = UserFriends::find()->where('(user_id=:uid OR sender_id=:uid) AND status=:stat', [
                    'uid' => $this->id,
                    'stat' => UserFriends::STATUS_IS_FRIEND,
        ]);
        return $friends;
    }

    /**
     * check if user is my friend (by id)
     * @param type $id
     * @return type
     */
    public function getIsFriendToMe($id)
    {
        $f = $this->getFriends()->one();
        /* @var $f UserFriends */
        if ($f) {
            return $f->getFriendOf($id);
        }
    }

}
