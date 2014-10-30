<?php
/**
 * CContentDecorator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CContentDecorator decorates the content it encloses with the specified views.
 *
 * CContentDecorator is mostly used to implement nested layouts, i.e., a layout
 * is embedded within another layout. {@link CBaseController} defines a pair of
 * convenient methods to use CContentDecorator:
 * <pre>
 * $this->beginContent('path/to/views');
 * // ... content to be decorated
 * $this->endContent();
 * </pre>
 *
 * The property {@link views} specifies the name of the views that is used to
 * decorate the content. In the views, the content being decorated may be
 * accessed with variable <code>$content</code>.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web.widgets
 * @since 1.0
 */
class CContentDecorator extends COutputProcessor
{
	/**
	 * @var mixed the name of the views that will be used to decorate the captured content.
	 * If this property is null (default value), the default layout will be used as
	 * the decorative views. Note that if the current controller does not belong to
	 * any module, the default layout refers to the application's {@link CWebApplication::layout default layout};
	 * If the controller belongs to a module, the default layout refers to the module's
	 * {@link CWebModule::layout default layout}.
	 */
	public $view;
	/**
	 * @var array the variables (name=>value) to be extracted and made available in the decorative views.
	 */
	public $data=array();

	/**
	 * Processes the captured output.
     * This method decorates the output with the specified {@link views}.
	 * @param string $output the captured output to be processed
	 */
	public function processOutput($output)
	{
		$output=$this->decorate($output);
		parent::processOutput($output);
	}

	/**
	 * Decorates the content by rendering a views and embedding the content in it.
	 * The content being embedded can be accessed in the views using variable <code>$content</code>
	 * The decorated content will be displayed directly.
	 * @param string $content the content to be decorated
	 * @return string the decorated content
	 */
	protected function decorate($content)
	{
		$owner=$this->getOwner();
		if($this->view===null)
			$viewFile=Yii::app()->getController()->getLayoutFile(null);
		else
			$viewFile=$owner->getViewFile($this->view);
		if($viewFile!==false)
		{
			$data=$this->data;
			$data['content']=$content;
			return $owner->renderFile($viewFile,$data,true);
		}
		else
			return $content;
	}
}
