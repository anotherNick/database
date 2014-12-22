<?php
// /myConstraints/Propel/Runtime/Validator/Constraints/WizName.php

namespace Propel\Runtime\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class WizName extends Constraint
{
    public $message = 'Names may only contain letters, numbers, spaces, and \'.';
    public $column = '';
}