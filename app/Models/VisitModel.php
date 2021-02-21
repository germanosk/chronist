<?php
namespace App\Models;

use CodeIgniter\Model;

class VisitModel extends Model {

    protected $table = 'sitevisit';
    public $primaryKey = 'idSiteVisit';
    protected $returnType = 'array';
    protected $allowedFields = ['ip', 'userAgent', 'pageView'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

}
