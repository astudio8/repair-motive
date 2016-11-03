<?php

namespace CarRepair\Lib;

// Configuration Class
class Config {
	static $confArray;

	public static function read($name) {
		return self::$confArray[$name];
	}

	public static function write($name, $value) {
		self::$confArray[$name] = $value;
	}
}

//Set our environment
//local, dev, prod
$platform = 'local';

if ($platform === 'local'){
	// DB Config
	Config::write('db.host', '127.0.0.1');
	Config::write('db.port', '');
	Config::write('db.basename', 'repairmotive');
	Config::write('db.user', 'user_name');
	Config::write('db.password', 'passsword123');
}