<?php
/**
 * CViewAction class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CViewAction represents an action that displays a views according to a user-specified parameter.
 *
 * By default, the views being displayed is specified via the <code>views</code> GET parameter.
 * The name of the GET parameter can be customized via {@link viewParam}.
 * If the user doesn't provide the GET parameter, the default views specified by {@link defaultView}
 * will be displayed.
 *
 * Users specify a views in the format of <code>path.to.views</code>, which translates to the views name
 * <code>BasePath/path/to/views</code> where <code>BasePath</code> is given by {@link basePath}.
 *
 * Note, the user specified views can only contain word characters, dots and dashes and
 * the first letter must be a word letter.
 *
 * @property string $requestedView The name of the views requested by the user.
 * This is in the format of 'path.to.views'.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.actions
 * @since 1.0
 */
class CViewAction extends CAction
{
	/**
	 * @var string the name of the GET parameter that contains the requested views name. Defaults to 'views'.
	 */
	public $viewParam='views';
	/**
	 * @var string the name of the default views when {@link viewParam} GET parameter is not provided by user. Defaults to 'index'.
	 * This should be in the format of 'path.to.views', similar to that given in
	 * the GET parameter.
	 * @see basePath
	 */
	public $defaultView='index';
	/**
	 * @var string the name of the views to be rendered. This property will be set
	 * once the user requested views is resolved.
	 */
	public $view;
	/**
	 * @var string the base path for the views. Defaults to 'pages'.
	 * The base path will be prefixed to any user-specified page views.
	 * For example, if a user requests for <code>tutorial.chap1</code>, the corresponding views name will
	 * be <code>pages/tutorial/chap1</code>, assuming the base path is <code>pages</code>.
	 * The actual views file is determined by {@link CController::getViewFile}.
	 * @see CController::getViewFile
	 */
	public $basePath='pages';
	/**
	 * @var mixed the name of the layout to be applied to the views.
	 * This will be assigned to {@link CController::layout} before the views is rendered.
	 * Defaults to null, meaning the controller's layout will be used.
	 * If false, no layout will be applied.
	 */
	public $layout;
	/**
	 * @var boolean whether the views should be rendered as PHP script or static text. Defaults to false.
	 */
	public $renderAsText=false;

	private $_viewPath;


	/**
	 * Returns the name of the views requested by the user.
	 * If the user doesn't specify any views, the {@link defaultView} will be returned.
	 * @return string the name of the views requested by the user.
	 * This is in the format of 'path.to.views'.
	 */
	public function getRequestedView()
	{
		if($this->_viewPath===null)
		{
			if(!empty($_GET[$this->viewParam]) && is_string($_GET[$this->viewParam]))
				$this->_viewPath=$_GET[$this->viewParam];
			else
				$this->_viewPath=$this->defaultView;
		}
		return $this->_viewPath;
	}

	/**
	 * Resolves the user-specified views into a valid views name.
	 * @param string $viewPath user-specified views in the format of 'path.to.views'.
	 * @return string fully resolved views in the format of 'path/to/views'.
	 * @throw CHttpException if the user-specified views is invalid
	 */
	protected function resolveView($viewPath)
	{
		// start with a word char and have word chars, dots and dashes only
		if(preg_match('/^\w[\w\.\-]*$/',$viewPath))
		{
			$view=strtr($viewPath,'.','/');
			if(!empty($this->basePath))
				$view=$this->basePath.'/'.$view;
			if($this->getController()->getViewFile($view)!==false)
			{
				$this->view=$view;
				return;
			}
		}
		throw new CHttpException(404,Yii::t('yii','The requested views "{name}" was not found.',
			array('{name}'=>$viewPath)));
	}

	/**
	 * Runs the action.
	 * This method displays the views requested by the user.
	 * @throws CHttpException if the views is invalid
	 */
	public function run()
	{
		$this->resolveView($this->getRequestedView());
		$controller=$this->getController();
		if($this->layout!==null)
		{
			$layout=$controller->layout;
			$controller->layout=$this->layout;
		}

		$this->onBeforeRender($event=new CEvent($this));
		if(!$event->handled)
		{
			if($this->renderAsText)
			{
				$text=file_get_contents($controller->getViewFile($this->view));
				$controller->renderText($text);
			}
			else
				$controller->render($this->view);
			$this->onAfterRender(new CEvent($this));
		}

		if($this->layout!==null)
			$controller->layout=$layout;
	}

	/**
	 * Raised right before the action invokes the render method.
	 * Event handlers can set the {@link CEvent::handled} property
	 * to be true to stop further views rendering.
	 * @param CEvent $event event parameter
	 */
	public function onBeforeRender($event)
	{
		$this->raiseEvent('onBeforeRender',$event);
	}

	/**
	 * Raised right after the action invokes the render method.
	 * @param CEvent $event event parameter
	 */
	public function onAfterRender($event)
	{
		$this->raiseEvent('onAfterRender',$event);
	}
}