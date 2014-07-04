<?php
namespace Duelist101\Db\View;

Class AreaSingle extends \Duelist101\Stamp
{
    public function parse( $reagent )
    {
        $cut = $this->getArea();
        $cut->setName($reagent->name);
        $cut->setImage($reagent->image);
        $this->add($cut);

        // TODO: add circleCircle and circleButton
    }
}
