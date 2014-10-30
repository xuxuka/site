<?php
class UserIdentity extends CUserIdentity
{
    private $_id;

    /**
     * @return bool
     */
    public function authenticate()
    {

        $record = Users::model()->findByAttributes(array('username'=>$this->username));

        if($record === null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!CPasswordHelper::verifyPassword($this->password,$record->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id = $record->user_id;
            $this->setState('username', $record->username);
            $this->setState('user_id', $this->_id);
            $this->errorCode = self::ERROR_NONE;

        }

        return !$this->errorCode;
    }

    /**
     * @return mixed|string
     */
    public function getId()
    {
        return $this->_id;
    }
}