<?php
namespace App\Models;

use CodeIgniter\Model;

class ChronicleSheetModel extends Model {

    protected $table = 'chroniclesheet';
    public $primaryKey = 'idChronicleSheet';
    protected $returnType = 'array';
    protected $allowedFields = ['chronicleCode', 'chronicleName', 'pdfURL', 'language', 'herolabCode'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

}
