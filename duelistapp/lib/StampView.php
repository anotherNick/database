<?php
namespace Slim\Views;

/**
 * Stamp View
 *
 * Custom View class that renders templates using StampTE.
 *
 * The render method will look for two files in the templates dir:
 * - <template name>.html - HTML markup with stamps
 * - <template name>.php - presentation logic
 */
class Stamp extends \Slim\View
{
    protected function render($filePrefix, $data = null)
    {
        $logicPathname = $this->getTemplatePathname($filePrefix . '.php');
        $htmlPathname = $this->getTemplatePathname($filePrefix . '.html');
        if ( !is_file($logicPathname) || !is_file($htmlPathname) ) {
            throw new \RuntimeException("View cannot render `$filePrefix` because the template PHP or HTML files do not exist");
        }

        $data = array_merge($this->data->all(), (array) $data);
        extract($data);

	$template = new \StampTemplateEngine\StampTE( file_get_contents($htmlPathname) ); 
        require $logicPathname;
	
	ob_start();
        echo $template;
        return ob_get_clean();
    }
}
