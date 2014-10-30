<?php

/**
 * This is the model class for table "tbl_posts".
 *
 * The followings are the available columns in table 'tbl_posts':
 */
class Posts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Posts the static model class
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
		return 'tbl_products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        return array(
            array('message, users_id', 'required'),
            array('message', 'length', 'min'=>0, 'max'=>1000),
            array('created_at', 'numerical', 'integerOnly'=>true),
            array('post_id, message, user_id', 'safe', 'on'=>'search'),

        );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'users' => array(self::BELONGS_TO, 'Users', 'users_id')
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
            'post_id' => 'PostId',
            'user_id' => 'UserId',
            'message' => 'Message',
            'created_at' => 'Created At',
        );
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{

		$criteria=new CDbCriteria;

        $criteria->compare('post_id',$this->post_id,true);
        $criteria->compare('message',$this->message,true);
        $criteria->compare('created_at',$this->created_at);
        $criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}