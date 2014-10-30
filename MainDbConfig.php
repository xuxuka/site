<?php

Class MainDbConfig 
{
	const DEFAULT_DB = 'localhost';

	private static $config = Array(

		'site.local'		=>	Array(
			'host'	=>	'localhost',
			'user'	=>	'root',
			'pass'	=>	'',
			'db'	=>	Array(
				'site'	=>	'site',
				),
			),
        'localhost'		=>	Array(
            'host'	=>	'localhost',
            'user'	=>	'root',
            'pass'	=>	'1',
            'db'	=>	Array(
                'site'	=>	'site',
            ),
        ),

	);



	public static function getDbConfig($in_str_server)
    {

        if( !array_key_exists($in_str_server, self::$config) )
        {
            $in_str_server = self::DEFAULT_DB;
        }

        return self::$config[ $in_str_server ];

    }

}