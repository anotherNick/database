<?php
namespace Duelist101;

Class Stamp extends \StampTE
{
	public function __construct( $tpl=null, $id='root' )
	{
		if( !isSet( $tpl ) ){
            $reflector = new \ReflectionClass( get_class( $this ) );
            $filename = str_replace('.php', '.html', $reflector->getFileName() );
            if ( !file_exists( $filename ) ) throw new StampTEException( '[S001] Could not find file: '.$filename );
            $tpl = file_get_contents( $filename );
        }
		parent::__construct( $tpl, $id );
	}

    public function getScripts() {
        return array();
    }
    
    public function getStyles() {
        return array();
    }
    
}