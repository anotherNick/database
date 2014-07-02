<?php
namespace Duelist101;

class WordpressView extends \Slim\View
{
    protected $content;
    
    public function add( $content )
    {
        $this->content = $this->content . $content;
    }

    public function getTemplate( $name )
    {
        $file = $this->getTemplatePathname($name);
        if ( !is_file($file) ) {
            throw new \RuntimeException("Template file does not exist: $file");
        }
        return file_get_contents($file);
    }
    
	public function render($content = null, $data = null)
	{
		get_header();
        echo '<div id="content">' . PHP_EOL;
        echo ( isset($content) ? $content : $this->content );
        echo '</div>' . PHP_EOL;
		get_sidebar();
		get_footer();
	}
}
