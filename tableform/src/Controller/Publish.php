<?php

namespace Drupal\tableform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Node;
use Drupal\Core\Url;

class Publish extends ControllerBase {

	public function publish() {
		
		$nid = \Drupal::routeMatch()->getParameters('node');
		$nid = $nid->get('node');
        $node = \Drupal\node\Entity\Node::load($nid);
        $node->setPublished(true);
        $node->save();
        $url = Url::fromRoute('tableform.table');
        return $this->redirect($url->getRouteName());

	}
	
}