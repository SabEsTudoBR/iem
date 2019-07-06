<?php
/**
 * Require Db base object
 */
require_once IEM_PATH . '/ext/database/db.php';

/**
 * DataBase Factory static object
 * Factory object that will encapsulate database instantiation procedure.
 *
 * @package interspire.iem.lib.iem
 */
class IEM_DBFACTORY
{
	/**
	 * Cache of available implementation
	 * @var array Cache of available implementation
	 */
	static private $_cacheLists = null;

	private function __construct() {}

	/**
	 * manufacture
	 * Manufacture a concrete implementation of the Db object
	 *
	 * @param String $host The host where the database server is located
	 * @param String $user Username to login to the database server
	 * @param String $password Password to authenticate the username
	 * @param String $name Database password
     * @param array $options
	 *
	 * @return MySQLDb Returns a concrete implementation of the Db object
	 *
	 * @throws exception_IEM_DBFACTORY
	 */
	static public function manufacture($host = 'localhost', $user = null, $password = null, $name = null, $options = [])
	{
		require_once IEM_PATH . "/ext/database/mysql.php";
     
		$db = new MySQLDb();
  
		if (array_key_exists('charset', $options)) {
		    $db->charset = $options['charset'];
        }
		if (array_key_exists('collate', $options)) {
		    $db->collate = $options['collate'];
        }
		if (array_key_exists('timezone', $options)) {
		    $db->timezone = $options['timezone'];
        }
		if (array_key_exists('tablePrefix', $options)) {
		    $db->TablePrefix = $options['tablePrefix'];
        }

		if (!$db->Connect($host, $user, $password, $name)) {
		    throw new exception_IEM_DBFACTORY($db->GetErrorMsg());
        }

        return $db;
	}

	/**
	 * lists
	 * List all available Db implementations in the system
	 *
	 * @return array Return an array of string of the available Db implementation
	 */
	static public function lists()
	{
		if (is_null(self::$_cacheLists)) {
			$dircontents = scandir(IEM_PATH . '/ext/database/');

			$lists = array();
			foreach ($dircontents as $each) {
				if (in_array($each, array('.', '..'. 'db.php'))) {
					continue;
				}

				list($filename, $ext) = explode('.', $each);
				array_push($lists, $filename);
			}

			self::$_cacheLists = $lists;
		}

		return self::$_cacheLists;
	}

	/**
	 * exists
	 * Check whether or not an implementation exists
	 *
	 * @param String $type Database type
	 * @return Boolean Returns TRUE if implementation exists, FALSE otherwise
	 */
	static public function exists($type)
	{
		$type = preg_replace('~[\W]~', '_', $type);
		return (is_readable(IEM_PATH . '/ext/database/' . $type . '.php'));
	}
}


/**
 * DBFactory exception class
 * @package interspire.iem.lib.iem
 */
class exception_IEM_DBFACTORY extends Exception
{
	const IMPLEMENTATION_DOES_NOT_EXISTS	= 1;
	const INVALID_IMPLEMENTATION			= 2;
}
