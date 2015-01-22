<?php
namespace Propel\Runtime\Validator\Constraints;

use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\ColumnMap;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PkExistsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $queryClass = $constraint->className . 'Query';
        
        if ( null === $queryClass::create()->findPK($value) ) {
            $this->context->addViolation($constraint->message);
        }
    }

}
