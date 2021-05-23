<?php
namespace App\Models;

use CodeIgniter\Model;

class IncidentModel extends Model {

    protected $table = 'incident';
    public $primaryKey = 'idIncident';
    protected $returnType = 'array';
    protected $allowedFields = ['ip', 'date', 'type'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

}
