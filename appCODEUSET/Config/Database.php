<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    // ----- various database 
    public string $defaultGroup = 'ignitionbasic';
	public $ignitionbasic;                    // default ignition, myworld sql user
    public $ignition_taz;                     // security tracking database and daemon

	public function __construct()
	{

		$this->defaultGroup = $GLOBALS['DBGROUP'];

        // ----- general db groups
    	$this->ignitionbasic = $this->dbGroup();
    	$this->ignition_taz = $this->dbGroup();

		parent::__construct();
	}

    protected function dbGroup() {

        return [
    		'DSN'      => '',
    		'hostname' => '127.0.0.1',
    		'username' => '',
    		'password' => '',
    		'database' => '',
    		'DBDriver' => '',
    		'DBPrefix' => '',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE.
    		'pConnect' => false,
    		'DBDebug'  => (ENVIRONMENT !== 'production'),
    		'cacheOn'  => false,
    		'cacheDir' => '',
    		'charset'  => 'utf8',
    		'DBCollat' => 'utf8_general_ci',
    		'swapPre'  => '',
    		'encrypt'  => false,
    		'compress' => false,
    		'strictOn' => false,
    		'failover' => [],
    		'port'     => 3306,
            'socket'   => '127.0.0.1'
    	];

    } 

}
