<?php
namespace app\service;

// Classe pour la table RequeteClient
class RequeteClientDAO extends GenericDAO
{
    public function __construct()
    {
        parent::__construct(
            'RequeteClient', // Nom de la table
            [
                'id_client',
                'Sujet',
                'Description',
                'Date_creation',
                'FichierJoint',
                'id_etat'
            ], // Champs modifiables
            'id_requete' // Clé primaire
        );
    }

}