<?php
/**
 * IEM EmailBounceDebug Class
 *
 * This class will handle enable/disable email, bounce, triggers and autoresponders.
 *
 */
class EmailBounceDebug
{
	protected $db;

	/**
	 * Constructor
	 * initialize database object
	 *

	 */
	public function __construct()
	{  
		$this->db = IEM::getDatabase();
	}


}
