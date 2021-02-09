<?php
namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model {

    protected $table = 'report';
    public $primaryKey = 'idReport';
    protected $returnType = 'array';
    protected $allowedFields = ['idChronicleSheet', 'eventName', 'creationDate', 'lastUpdate', 'eventDate'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
