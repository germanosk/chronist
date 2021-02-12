<?php
namespace App\Models;

use CodeIgniter\Model;

class SheetFieldsModel extends Model {

    protected $table = 'sheetfield';
    public $primaryKey = 'idAdventureField';
    protected $returnType = 'array';
    protected $allowedFields = ['posX', 'posY', 'height', 'width', 'idChronicleSheet','type','fieldName','fontSize'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
