<?php
namespace App\Interfaces;

interface OwnershipCheckable
{
    public function onTooManyAttempts();
    public function onWrongCode();
}
