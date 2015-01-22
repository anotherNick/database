<?php
// /myConstraints/Propel/Runtime/Validator/Constraints/WizName.php

namespace Propel\Runtime\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class PkExists extends Constraint
{
    public $message = "Please select one.";
    public $className;
}