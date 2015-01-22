<?php

namespace W101;

use W101\Base\FishHousingitemQuery as BaseFishHousingitemQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'fish_housingitem' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class FishHousingitemQuery extends BaseFishHousingitemQuery
{
    public static function addIfNew( $housingitem_id, $fish_id )
    {
        if ( null === FishHousingitemQuery::create()
                ->filterByHousingitemId( $housingitem_id )
                ->filterByFishId( $fish_id )
                ->findOne() ) {
            $fishHousingitem = new FishHousingitem();
            $fishHousingitem->setHousingitemId( $housingitem_id );
            $fishHousingitem->setFishId( $fish_id );
            $fishHousingitem->save();
            return true;
        } else {
            return false;
        }
        
    }

}
