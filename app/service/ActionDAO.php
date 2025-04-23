<?php
namespace app\service;

// Classe pour la table des Caisses
class ActionDAO extends GenericDAO
{
    public function __construct()
    {
        parent::__construct('ActionCommerciale', ['Campagne','Objectif','EstConvertie','Cout'], 'ActionCommercialeID');
    }
}
