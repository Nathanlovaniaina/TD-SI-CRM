<?php
namespace app\service;

// Classe pour la table des Caisses
class CaisseDAO extends GenericDAO
{
    public function __construct()
    {
        parent::__construct('Caisse', ['NumeroCaisse', 'Emplacement', 'EstActive', 'DateMiseEnService'], 'CaisseID');
    }
}
