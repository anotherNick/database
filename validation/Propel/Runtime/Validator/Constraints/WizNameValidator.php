<?php
 // /myConstraints/Propel/Runtime/Validator/Constraints/WizNameValidator.php

 namespace Propel\Runtime\Validator\Constraints;

 use Symfony\Component\Validator\Constraint;
 use Symfony\Component\Validator\ConstraintValidator;

 class WizNameValidator extends ConstraintValidator
 {
     public function isValid($value, Constraint $constraint)
     {
         if ('propelorm.org' === strstr($value, 'propelorm.org')) {
             return false;
         } else {
             $this->setMessage($constraint->message);

             return true;
         }
     }
 }


