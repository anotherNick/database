<?php
namespace Duelist101;

class WordpressView extends \Slim\View
{
    protected $content;
    protected $scripts = array();
    protected $styles = array();
    
    public function add( $stamp )
    {
        $this->content = $this->content . (string) $stamp;
        $this->scripts = array_merge($this->scripts, $stamp->getScripts());
        $this->styles = array_merge($this->styles, $stamp->getStyles());
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
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );
        // $this->enqueue_scripts();
		get_header();

        echo '<div id="content">' . PHP_EOL;
        echo ( isset($content) ? $content : $this->content );
        echo '  </div>' . PHP_EOL;

		get_sidebar();
		get_footer();
	}
	
	public function enqueue_scripts()
	{
        foreach( $this->scripts as $script ) {
            $handle = $script['handle'];
            $src = (isset($script['src'])) ? $script['src'] : false;
            $deps = (isset($script['deps'])) ? $script['deps'] : array();
            $ver = (isset($script['ver'])) ? $script['ver'] : false;
            $in_footer = (isset($script['in_footer'])) ? $script['in_footer'] : false;
            wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        }

        foreach( $this->styles as $style) {
            $handle = $style['handle'];
            $src = (isset($style['src'])) ? $style['src'] : false;
            $deps = (isset($style['deps'])) ? $style['deps'] : array();
            $ver = (isset($style['ver'])) ? $style['ver'] : false;
            $media = (isset($style['media'])) ? $style['media'] : 'all';
            wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		}
		
	}
}
