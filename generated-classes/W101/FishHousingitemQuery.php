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
    public static function updateAquariums($fishId, $aquariums)
    {
        // delete any removed aquariums
        $currentAquariums = FishHousingitemQuery::create()
            ->filterByFishId( $fishId )
            ->find();
        foreach ( $currentAquariums as $aquarium ) {
            if ( ! in_array( $aquarium->getHousingitemId(), $aquariums ) ) {
                $aquarium->delete();
            }
        }

        // add any new aquariums
        foreach( $aquariums as $aquariumId) {
            if ( null === FishHousingitemQuery::create()
                    ->filterByHousingitemId( $aquariumId )
                    ->filterByFishId( $fishId )
                    ->findOne() ) {
                $fishHousingitem = new FishHousingitem();
                $fishHousingitem->setHousingitemId( $aquariumId );
                $fishHousingitem->setFishId( $fishId );
                $fishHousingitem->save();
            }
        }
    }

}
