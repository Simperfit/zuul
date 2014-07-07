<?php

namespace Oblady\ZuulBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Oblady\ZuulBundle\Form\Type\RequestConnectType;


/**
 * @Route("/request")
 * @todo Ajouter un flash message lors de l'envoi du mail
 *       j'hésite a mettre la methode sendRequestMail en protected
 * 
 */
class RequestController extends Controller {

    /**
     * @Route("/")
     */
    public function listAction() {

        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }


        $form = $this->createForm(new RequestConnectType(), array());

        if ('post' == strtolower($this->getRestMethod())) {

            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {

                $this->sendRequestMail($form->getData());

                return $this->redirect($this->generateUrl('request_list'));
            }
        }

        return $this->render('ObladyZuulBundle:Default:request.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    private function sendRequestMail($req) {

        $user = $this->getUser();
        if (!empty($user->getEmail())) {
            $from = array($user->getEmail() => $user->getName());
        } else {
            $from = array(
                $this->container->getParameter("requestconnect.from_email")
                => $this->container->getParameter("requestconnect.from_name"));
        }

        // on envoie la demande par mail
        $message = \Swift_Message::newInstance()
                ->setSubject('[Zuul] Request Connection to a server')
                ->setFrom($from)
                ->setTo($this->container->getParameter("requestconnect.email"))
                ->setBody($this->renderView('ObladyZuulBundle:mail:requestconnect.txt.twig', array('req' => $req)))
        ;
        $this->get('mailer')->send($message);
    }

}
