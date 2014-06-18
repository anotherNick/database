<?php
# Expects $url and $title.

		$cutDisqus = $template->getDisqus();
		
		$cutDisqus->setUrl($url);
		$cutDisqus->setTitle($title);
		$template->add($cutDisqus);

