<?php

/**
 * This is the model class for table "tbl_users".
 *
 * The followings are the available columns in table 'tbl_users':
 */
class Users extends CActiveRecord
{
    public $password_repeat;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

        return array(

            array('username', 'required', 'on' => 'register', 'message'  => 'Username cannot be blank'),
            array('username', 'match',   'pattern' => '/^[A-Za-z0-9_-А-Яа-я\s,]+$/u', 'message' => 'Username contains invalid characters'),
            array('password', 'required', 'on'=>  'register', 'message'  => 'Password cannot be blank'),
            array('password_repeat', 'required', 'on' => 'register', 'message'  => 'Password Repeat cannot be blank'),
            array('password_repeat', 'compare', 'compareAttribute' => 'password', 'on' => 'register'),
            array('username, password', 'required'),
            array('username', 'unique'),
            array('username', 'length', 'min' => 4, 'max' => 255),
            array('username', 'match', 'pattern' => '/^[A-z][\w]+$/'),
            array('password', 'length', 'min' => 4, 'max' => 255),
            array('password_repeat', 'length', 'min' => 4, 'max' => 255),
            array('created_at', 'numerical', 'integerOnly' => true),
            array('user_id, username, password', 'safe', 'on' => 'search'),

        );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'posts' => array(self::HAS_MANY, 'Posts', 'user_id')
		);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
			)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'password_repeat' => 'Password Repeat',
			'created_at' => 'Created At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}