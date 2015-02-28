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
    public function addOrUpdate( $post )
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
        
        // if new, check for dupe name being created
        if ( ! $this->getId() > 0 ) {
            $dupeFish = FishQuery::create()
                ->filterByName( $this->getName() )
                ->findOne();
            if ( $dupeFish !== null ) {
                $result['failures'][] = array(
                    'property' => 'name',
                    'message' => 'id - ' . $this->getId() . ' This name already exists.',
                    'code' => null
                );
            }
        }
        
        // make sure aquariums are valid
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
            // don't want to mess with image files if image didn't change or any failures
            if ( $post['imageDataUrl'] != 'current' && empty($result['failures']) ) {
                $pathPrefix = \Duelist101\APP_DIR . 'public_html/images/w101_fish/';
                $oldImage = $this->getImage();
                $image = sanitize_file_name( $this->getName() );
                if ( $oldImage !== null && file_exists($pathPrefix . $oldImage) ) {
                    if ( !rename($pathPrefix . $oldImage, $pathPrefix . $image . '.bak') ) {
                        // TODO: log the fact that couldn't rename
                    }
                }
                $this->setImage( $image . '.jpg' );
                file_put_contents( $pathPrefix . $image . '.jpg', file_get_contents( $post['imageDataUrl'] ) );
                $imageInfo = getimagesize( $imageFile );
                if ( $imageInfo[2] != IMG_JPG ) {
                    // TODO: log the fact that not jpg
                    if ( !rename($pathPrefix . $image . '.bak', $pathPrefix . $oldImage) ) {
                        // TODO: log the fact that couldn't rename
                    }
                    $result['failures'][] = array(
                        'property' => 'image',
                        'message' => 'The cropped image is not a valid JPG.  Please refresh and try again.',
                        'code' => null
                    );
                }
            }
        } else if ( strlen($this->getImage()) == 0 ) {
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
            FishHousingitemQuery::updateAquariums($this->getId(), $post['aquariums']);
        } else {
            $result['status'] = 'failures';
        }
            
        return $result;
    }

}
