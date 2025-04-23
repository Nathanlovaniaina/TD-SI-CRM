<?php
namespace app\service;

use Flight;
use PDO;

class GenericDAO
{
    private $conn;
    private $table;
    private $primaryKey;
    private $fields;

    public function __construct($table, $fields, $primaryKey)
    {
        $this->conn       = Flight::db();
        $this->table      = $table;
        $this->primaryKey = $primaryKey; // Clé primaire
        $this->fields     = $fields;     // Champs de la table
    }

    // Créer un enregistrement
    public function create($data)
{
    // On s'assure que l'on n'inclut pas la clé primaire dans les colonnes
    if (isset($data[$this->primaryKey])) {
        unset($data[$this->primaryKey]);
    }

    // Création des colonnes (noms des champs)
    $columns = implode(", ", array_keys($data));

    // Création des valeurs à insérer (en s'assurant de bien échapper les valeurs)
    $values = "'" . implode("', '", array_map(function ($value) {
        return addslashes($value); // On échappe les valeurs pour éviter les injections SQL
    }, array_values($data))) . "'";

    // Préparation de la requête d'insertion
    $query = "INSERT INTO {$this->table} ($columns) VALUES ($values)";

    // Exécution de la requête
    $this->conn->query($query);

    // Retourne l'ID du dernier enregistrement inséré
    return $this->conn->lastInsertId();
}

    // Récupérer un enregistrement par la clé primaire
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = '$id'";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les enregistrements
    public function getAll()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un enregistrement
    public function update($id, $data)
    {
        // On s'assure que l'on n'inclut pas la clé primaire dans les colonnes
        if (isset($data[$this->primaryKey])) {
            unset($data[$this->primaryKey]);
        }

        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = '$value', ";
        }
        $setClause = rtrim($setClause, ", ");

        $query = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = $id";
        $stmt  = $this->conn->prepare($query);

        return $stmt->execute();
    }

    // Supprimer un enregistrement
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
        $stmt  = $this->conn->prepare($query);
        return $stmt->execute();
    }

    public function getByCriteria($criteria)
    {
        $query = "SELECT * FROM {$this->table} WHERE $criteria";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
