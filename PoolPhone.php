<?php

class PoolPhone{

	private static $_dbConfig = array('host'=>'localhost', 'dbname'=>'', 'user'=>'', 'password'=>'' );
	private static $_instance = null;
    private static  $_connection;

    private  static $tableNameBrend ='catalog_brend';
    private static $tableNameDevice ='catalog_device';

    public  $brends = array();

	private function __construct() {
        try {
            $dsn = 'mysql:host='.self::$_dbConfig['host'].';dbname='.self::$_dbConfig['dbname'].';charset=UTF8';
            self::$_connection  = new PDO($dsn, self::$_dbConfig['user'], self::$_dbConfig['password']);

            $sqlAnswerBrends =  self::$_connection->query("SELECT `folder`, `brendID` FROM ". self::$tableNameBrend);
            $this->brends = $sqlAnswerBrends->fetchAll(PDO::FETCH_KEY_PAIR);


        } catch (PDOException $e) {
           print "Error!: " . $e->getMessage() . "<br/>";
           die();
        }
	}

	public function __destruct(){
	}

	public function __clone(){
        return false;
    }


	static public function getInstance(array $config) {

		if(is_null(self::$_instance) || self::$_dbConfig!==$config)
		{
		    self::$_dbConfig = $config;
			self::$_instance = new self();
		}

		return self::$_instance;
	}


 
	public function __get($brendsNeed) {
	    return $this->getDeviceForBrends($brendsNeed);
	}


    public function getDeviceForBrends($brends){
        $tableDevice = self::$tableNameDevice;

       $brends =  $this->normalizeRequestBrends($brends);

        $idBrendsSelect = implode(',', $brends);
        $sqlResDevice = self::$_connection->query("SELECT `brendID`,`name` FROM {$tableDevice} WHERE `brendID` IN ({$idBrendsSelect})");


        return $sqlResDevice ? $sqlResDevice->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC) : array();
    }


    private function normalizeRequestBrends($mixedValue)
    {
        $idBrends =array();

        if (is_string($mixedValue)){
            $mixedValue =  explode(" ", $mixedValue);
        }

        foreach ((array)$mixedValue as $brendsNeed)
            if (key_exists($brendsNeed, $this->brends)) {
                $idBrends[] = $this->brends[$brendsNeed];
        }

        return $idBrends;
    }
}