<?php

namespace Drupal\tableform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Node;
use Drupal\Core\Url;

class Unpublish extends ControllerBase {

	public function unpublish() {
		
		$nid = \Drupal::routeMatch()->getParameters('node');
		$nid = $nid->get('node');
        $node = \Drupal\node\Entity\Node::load($nid);
        $node->setPublished(false);
        $node->save();
        $url = Url::fromRoute('tableform.table');
        return $this->redirect($url->getRouteName());

	}
	
}