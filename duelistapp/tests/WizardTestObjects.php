<?php

Class W
{
    public function getStoneBlock()
    {
        $r = new StdClass();
        $r->id = 1;
        $r->name = 'Stone Block';
        $r->rank = 1;
        $r->image = 'Stone-Block.gif';
        $r->description = 'A Stone Block';
        $r->can_auction = 1;
        $r->is_crowns_only = 0;
        $r->is_retired = 0;
        $r->class_id = 4;
        $r->class = new StdClass();
        $r->class->name = 'Balance';

        return $r;
    }

}
