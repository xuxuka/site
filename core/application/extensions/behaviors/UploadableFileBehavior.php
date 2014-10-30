<?php
	
	/**
	 * Даний клас поведінки для AR містить у собі функціонал по завантаженню файлу
	 */
	class UploadableFileBehavior extends CActiveRecordBehavior
	{
		/**
		 * @var string назва атрибута, що зберігає в собі ім'я файлу і файл
		 */
		public $attributeName = 'document';

		/**
		 * @var string аліас директорії, куди будемо зберігати файли
		 */
		public $savePathAlias = 'webroot.protected.data';
		
		/**
		 * @var array сценарії валідації до яких будуть додані правила валідації завантаження файлів
		 */
		public $scenarios = array( 'insert', 'update' );
		
		/**
		 * @var string типи файлів, які можна завантажувати
		 */
		public $fileTypes = 'txt, csv, doc, docx, xls, xlsx, pdf';
	 
		/**
		 * Повертає шлях до директорії, в якій будуть зберігатися файли.
		 * @return string шлях до директорії, в якій зберігаємо файли
		 */
		public function getSavePath()
		{
			$path = Yii::getPathOfAlias( $this->savePathAlias ) . DIRECTORY_SEPARATOR;

			#
			if( !is_dir( $path ) )
			{
				mkdir( $path, 0755, true );
			}
			
			return $path;
		}
		
		/**
		 * При привязці функціоналу додаємо валідатор файлу
		 */
		public function attach( $owner )
		{
			parent::attach( $owner );
	 
			if( in_array( $owner->scenario, $this->scenarios ) )
			{
				#
				$fileValidator = CValidator::createValidator( 'file', $owner, $this->attributeName, array( 'types' => $this->fileTypes, 'allowEmpty' => true ) );
				
				#
				$owner->validatorList->add( $fileValidator );
			}
		}
		
		/**
		 * При збереженні старий файл видалимо, бо завантажуємо новий
		 */
		public function beforeSave( $event )
		{
			if( in_array( $this->owner->scenario, $this->scenarios ) && ( $file = CUploadedFile::getInstance( $this->owner, $this->attributeName ) ) )
			{	
				$this->deleteFile();
				$this->owner->setAttribute( $this->attributeName, md5( time() . '_' . $file->name ) . '.' . end( explode( '.', $file->name) ) );
				$file->saveAs( $this->getSavePath() . $this->owner->getAttribute( $this->attributeName ) );
			}
			
			return true;
		}
	 
		/**
		 * При видалені запису видаляємо і файл, пов'язаний з нею
		 */
		public function afterDelete( $event )
		{
			$this->deleteFile();
		}

		/**
		 * Даний метод видаляє файл
		 */
		public function deleteFile()
		{
			$filePath = $this->getSavePath() . $this->owner->getAttribute( $this->attributeName );
			
			if( @is_file( $filePath ) )
			{
				@unlink( $filePath );
			}
		}
	}