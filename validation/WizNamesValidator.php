<?php
 // /myConstraints/Propel/Runtime/Validator/Constraints/WizNameValidator.php

 namespace Propel\Runtime\Validator\Constraints;

 use Symfony\Component\Validator\Constraint;
 use Symfony\Component\Validator\ConstraintValidator;

 class WizNamesValidator extends ConstraintValidator
 {
     public function validate($value, Constraint $constraint)
     {
         if ('propelorm.org' === strstr($value, 'propelorm.org')) {
             return false;
         } else {
             return true;
         }
     }
 }


