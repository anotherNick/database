<?php

namespace W101;

use W101\Base\Fish as BaseFish;

/**
 * Skeleton subclass for representing a row from the 'fish' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Fish extends BaseFish
{
    public function addNew( $post )
    {
        if ( !empty($post['name']) ) $this->setName( $post['name'] );
        if ( !empty($post['rank']) ) $this->setRank( $post['rank'] );
        if ( !empty($post['description']) ) $this->setDescription( $post['description'] );
        if ( !empty($post['initial-xp']) ) $this->setInitialXp( $post['initial-xp'] );
        if ( !empty($post['rarity-id']) ) $this->setRarityId( $post['rarity-id'] );
        if ( !empty($post['class-id']) ) $this->setClassId( $post['class-id'] );

        if ( !$this->validate() ) {
            foreach( $this->getValidationFailures() as $failure ) {
                $result['failures'][] = array(
                    'property' => str_replace('_', '-', $failure->getPropertyPath()),
                    'message' => $failure->getMessage(),
                    'code' => $failure->getCode()
                );
            }
        }
        
        if ( !empty($post['aquariums']) && !is_array($post['aquariums']) ) {
            $post['aquariums'] = array( $post['aquariums'] );
        }
        if ( is_array( $post['aquariums'] ) ) {
            foreach($post['aquariums'] as $aquarium_id) {
                if ( null === HousingitemQuery::create()
                        ->filterById( $aquarium_id )
                        ->filterByCanHoldFish( true )
                        ->findOne() ) {
                    $result['failures'][] = array(
                        'property' => 'aquariums',
                        'message' => ' The selected aquarium is no longer available to hold fish.  Please refresh your browser to get a new list.',
                        'code' => null
                    );
                }
            }
        } else {
            $result['failures'][] = array(
                'property' => 'aquariums',
                'message' => 'Please select at least one aquarium.',
                'code' => null
            );
        }

        if ( !empty( $post['imageDataUrl'] ) ) {
            $imageName = sanitize_file_name( $this->getName() );
            $imagePath = \Duelist101\APP_DIR . 'public_html/images/w101_fish/';
            if ( file_exists($imagePath . $imageName . '.jpg') ) {
                $i = 1;
                while ( file_exists($imagePath . $imageName . $i . '.jpg') ) {
                    $i++;
                }
                $imageName = $imageName . $i;
            }
            $this->setImage( $imageName . '.jpg' );
            $imageFile = $imagePath . $this->getImage();
            file_put_contents( $imageFile, file_get_contents( $post['imageDataUrl'] ) );
            $imageInfo = getimagesize( $imageFile );
            if ( $imageInfo[2] != IMG_JPG ) {
                // TODO: log the fact that not jpg
                unlink( $imageFile );
                $result['failures'][] = array(
                    'property' => 'image',
                    'message' => 'The cropped image is not a valid JPG.  Please refresh and try again.',
                    'code' => null
                );
            }
        } else {
            $result['failures'][] = array(
                'property' => 'image',
                'message' => 'Please upload a picture and then crop the area to use.',
                'code' => null
            );
        }

        if ( empty($result['failures']) ) {
            $result['status'] = 'success';
            $result['redirect'] = \Duelist101\BASE_URL . 'fish/' . urlencode( $this->getName() );
            $this->save();
            foreach($post['aquariums'] as $aquarium_id) {
                FishHousingitemQuery::addIfNew( $aquarium_id, $this->getId() );
            }
        } else {
            $result['status'] = 'failures';
        }
            
        return $result;
    }

}
