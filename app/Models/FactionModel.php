<?php
namespace App\Models;

use CodeIgniter\Model;

class FactionModel extends Model {

    protected $table = 'faction';
    public $primaryKey = 'idFaction';
    protected $returnType = 'array';
    protected $allowedFields = ['nameFaction', 'codeFaction'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

}
