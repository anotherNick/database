<?php
namespace Duelist101\Db\View;

Class DisqusFooter extends \Duelist101\Stamp
{
    public function parse( $title, $url ) {

		$cutDisqus = $this->getDisqus();
		
		$cutDisqus->setUrl($url);
		$cutDisqus->setTitle($title);
		$this->add($cutDisqus);
    }
}
