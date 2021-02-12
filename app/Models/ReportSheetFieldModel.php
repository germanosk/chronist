<?php
namespace App\Models;

use CodeIgniter\Model;

class ReportSheetFieldModel extends Model {

    protected $table = 'reportsheetfield';
    public $primaryKey = 'idReport';
    
    protected $useAutoIncrement = false;
    
    protected $returnType = 'array';
    protected $allowedFields = ['idAdventureField', 'idReport', 'value'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
