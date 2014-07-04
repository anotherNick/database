<?php
namespace Duelist101;

class WordpressView extends \Slim\View
{
    protected $content;
    protected $headerJavascript = array();
    protected $footerJavascript = array();
    
    public function add( $stamp )
    {
        $this->content = $this->content . (string) $stamp;
        if ( isset($stamp->headerJavaScript)) {
            $this->headerJavascript = array_merge( 
                $this->headerJavascript, 
                $stamp->headerJavascript
            );
        }
        if ( isset($stamp->footerJavaScript) ) {
            $this->footerJavascript = array_merge( 
                $this->footerJavascript, 
                $stamp->footerJavascript 
            );
        }
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
        echo '  <div class="post-wrapper post" style="padding:10px;">' . PHP_EOL;
        echo ( isset($content) ? $content : $this->content );
        echo '  </div>' . PHP_EOL;
        echo '  <div class="clear"></div>' . PHP_EOL;
        echo '</div>' . PHP_EOL;

		get_sidebar();
		get_footer();
	}
}
