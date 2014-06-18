<?php
# Expects $url and $title from data.

		$cutDisqus = $template->getDisqus();
		
		$cutDisqus->setUrl($url);
		$cutDisqus->setTitle($title);
		$template->add($cutDisqus);

