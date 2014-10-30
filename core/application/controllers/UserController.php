<?php
class UserController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index', array());
	}


	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionLogin()
	{
        if (Yii::app()->user->isGuest)
        {
            $request = Yii::app()->getRequest();
            $user = Yii::app()->getUser();

            $errorCode = 0;

            if($request->isPostRequest)
            {
                $username = $request->getPost('username');
                $password = $request->getPost('password');

                $identity = new UserIdentity($username,$password);

                if($identity->authenticate())
                {
                    $user->login($identity, time() + Yii::app()->params["timeout"]);
                    //echo '<br>' .__FILE__.':'.__LINE__. var_dump(Yii::app()->params["key"].Yii::app()->getSession()->getSessionId(), $user->getState('user_id'),$user->getId()) . '<br>';
                    Yii::app()->redis->getClient()->set(Yii::app()->params["key"].Yii::app()->getSession()->getSessionId(), $user->getState('user_id'));
                    Yii::app()->redis->getClient()->expire(Yii::app()->params["key"].Yii::app()->getSession()->getSessionId(), Yii::app()->params["timeout"]);

                    //$cookie = new CHttpCookie('user_id',$user->getState('user_id'));
                    //$cookie->expire = time() + Yii::app()->params["timeout"];
                    //Yii::app()->request->cookies['user_id'] = $cookie;

                    setcookie("user_id", $user->getState('user_id'), time() + Yii::app()->params["timeout"], "/");

                    $request->redirect('/');
                    //$request->redirect($user->returnUrl);
                } else {
                    $this->setFlashMessage('Enter the correct information');
                }

                $errorCode = $identity->errorCode;

            }

            $this->render('login', array('errorCode' => $errorCode));

        }
        else
        {
            //$this->redirect(Yii::app()->user->returnUrl);
            $this->redirect('/');
        }

	}

    public function actionRegister()
    {
        if (Yii::app()->user->isGuest)
        {

            $request = Yii::app()->getRequest();

            $user = new Users;

            $user->scenario = 'register';

            if($request->isPostRequest)
            {
                $users_array = $request->getPost('Users');
                $username = $users_array['username'];
                $password = $users_array['password'];
                $password_repeat = $users_array['password_repeat'];

                $user->attributes = $users_array;//array('username' => $username, 'password' => $password, 'password_repeat' => $password_repeat);

                if($user->validate())
                {

                    $user->setAttribute('password', CPasswordHelper::hashPassword($password));
                    $user->scenario = 'insert';

                    if($user->save())
                    {
                        $this->setFlashMessage('Registration was successful');
                        Yii::app()->clientScript->registerMetaTag("2;url=".Yii::app()->createUrl('user/login'), null, 'refresh');
                        $this->render('register');

                        //$this->redirect('/');
                    }

                } else {
                    $this->render('register', array('model' => $user));
                }

            } else {
                $this->render('register', array('model' => $user));
            }

        }
        else
        {
            $this->redirect('/');
        }
    }

	/**
	 * Logout user from forum
	 */
	public function actionLogout()
	{

		if(!headers_sent())
		{
			// Cache off
			/*header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Expires: '.date('r'));*/

			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
		}

        Yii::app()->redis->getClient()->del(Yii::app()->params["key"].Yii::app()->getSession()->getSessionId());
        Yii::app()->request->cookies->clear();
		Yii::app()->getUser()->logout();

		$this->redirect(array('user/login'));
	}

}