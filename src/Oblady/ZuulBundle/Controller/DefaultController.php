<?php

namespace Oblady\ZuulBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    public function indexAction()
    {
      return  $this->redirect($this->generateUrl('sonata_admin_dashboard'));
    }
}
