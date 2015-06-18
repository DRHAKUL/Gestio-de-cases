<?php

//Patron de diseny Singleton, esta disenyat per evitar la creació d'objectes de una clase determinada.

class Core {

    public $db;
    private static $instance;

    //Definim el constructor.
    private function __construct() { // privat, nomes es pot instanciar amb getInstance
        $dsn = 'mysql:host=localhost;dbname=proves_db;charset=utf8';
        $user = 'user-proves';
        $password = '12345';

        $this->db = new PDO($dsn, $user, $password); //Instanciam l'ojecte de la bbdd.
        // Establim atributs per el manetjador de  la base de dades.
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); //Estableix el resultat com una fila.
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Mostra errors.
    }

    // La funcio get instance fa la creacio de l'objecte de conexió.
    public static function getInstance() {
        if (!isset(self::$instance)) { // si no existeix el crea
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

}
