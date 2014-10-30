<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
    * Session key used to store flash messages.
    */
    const FLASH_MESSAGE_KEY = 'flash:message:';
	
	/**
	 * @var string the default layout for the controller views. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = 'main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	/**
    * External PHPTAL template repository.
    */
    public $staticUrl = '/static';

    /**
     * External PHPTAL template repository.
     */
    public $templatePath = '/srv/sites/gmatclub.com/htdocs/static';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('denny',
                'actions'=>array('index'),
                'users'=>array('?'),
            ),
            array('allow',
                'controllers'=>array('user'),
                'actions'=>array('register'),
                'users'=>array('*'),
            ),
        );
    }

	public function init()
	{
		parent::init();

        $request = Yii::app()->getRequest();

        if (Yii::app()->getUser()->isGuest) {
            if (!($request->isAjaxRequest || strpos($request->getUrl(), 'login') !== false)) {
                Yii::app()->user->setReturnUrl($request->getUrl());
            }
        }

		Yii::app()->theme = 'users';
	}

	/**
    * Define template constacts.
    */
    protected function beforeRender($view)
    {
        define('PUBLIC_URL', Yii::app()->getBaseUrl(true) . '/resources');
        define('ASSETS_URL', Yii::app()->getBaseUrl().'/templates/'.Yii::app()->theme->getName().'/assets');
        define('STATIC_URL', $this->staticUrl);

        return true;
    }

	/**
    * Sets a flash message.
    */
    public function setFlashMessage($message)
    {
        Yii::app()->getSession()->add(self::FLASH_MESSAGE_KEY, $message);
    }

    /**
    * Gets a flash message or null.
    */
    public function getFlashMessage()
    {
        return Yii::app()->getSession()->remove(self::FLASH_MESSAGE_KEY);
    }

	/**
    * Renders an external website template.
    *
    * @param string Template file name.
    * @param array Template parameters.
    */
    protected function renderExternal($name, $params = array())
    {
        require_once 'PHPTAL.php';

        $t = new PHPTAL($name);
        $t->setTemplateRepository($this->templatePath);

        foreach ($params as $k => $v) $t->{$k} = $v;

        return $t->execute();
    }

	 /**
    * Compares specified URL with the current.
    */
    public function checkUrl($url)
    {

		$thisUrl = $this->createUrl($this->getAction()->getId());
        $testUrl = CHtml::normalizeUrl($this->createUrl($url));

        return (strcasecmp($thisUrl, $testUrl) === 0);
    }

	public function renderJson($data, $cache = false)
	{
		if(!headers_sent())
		{
			header('Content-type: application/json');
			if(!$cache)
			{
				// Cache off for ajax request
				header('Cache-Control: no-store, no-cache, must-revalidate');
				header('Expires: '.date('r'));
			}
		}
		die(json_encode($data));
	}

	/**
	 * Дана функця призначена для передачі даних на візуалізацію
	 */
	public function displayData( $in_str_layout, $in_array_data, $in_str_render_option )
	{
		# Відповідно до типу формуємо відносний шлях
		switch ( $in_str_render_option )
		{
			case 'ajax':
			{
				echo json_encode( array( 'html' => $this->renderPartial( $in_str_layout, $in_array_data, true ) ) );

			} break;
			
			case 'json':
			{
				echo $this->renderJson( $in_array_data );

			} break;

			case 'partial':
			{
				$this->renderPartial( $in_str_layout, $in_array_data );

			} break;

			default:
			{
				$this->render( $in_str_layout, $in_array_data );
			}
		}
	}

	/**
	 * Renders a views with a layout.
	 *
	 * This method first calls {@link renderPartial} to render the views (called content views).
	 * It then renders the layout views which may embed the content views at appropriate place.
	 * In the layout views, the content views rendering result can be accessed via variable
	 * <code>$content</code>. At the end, it calls {@link processOutput} to insert scripts
	 * and dynamic contents if they are available.
	 *
	 * By default, the layout views script is "protected/views/layouts/main.php".
	 * This may be customized by changing {@link layout}.
	 *
	 * @param string $view name of the views to be rendered. See {@link getViewFile} for details
	 * about how the views script is resolved.
	 * @param array $data data to be extracted into PHP variables and made available to the views script
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see renderPartial
	 * @see getLayoutFile
	 */
	public function render($view,$data=null,$return=false)
	{
		if($this->beforeRender($view))
		{
			$output=$this->renderPartial($view,$data,true);
			if(($layoutFile=$this->getLayoutFile($this->layout))!==false)
				$output=$this->renderFile($layoutFile,array(
					'content'=>$output,
					//'is_authorize' => Yii::app()->getUser()->checkUserStatus(WebUser::USER_IS_AUTH),
					'template' => $this->getAction()->getId(),
				),true);

			$this->afterRender($view,$output);

			$output=$this->processOutput($output);

			if($return)
				return $output;
			else
				echo $output;
		}
	}

}