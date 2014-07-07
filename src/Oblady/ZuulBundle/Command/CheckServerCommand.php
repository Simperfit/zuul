<?php

namespace Oblady\ZuulBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Oblady\ZuulBundle\Entity\Key;

/**
 * Description of CheckServer
 *
 * @author sebastien
 */
class CheckServerCommand  extends ContainerAwareCommand {
    protected function configure()
    {
        $this
            ->setName('zuul:check:server')
            ->setDescription('Check server status and config')

            ;
    }
    
     protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('updating keys ');
        $this->updatekeyFingerprints() ;
        $output->writeln('Checking servers ');
        $em = $this->getDoctrineManager();
        $keyManager =  $this->getContainer()->get('oblady_zuul.key_manager');
        $repository = $em->getRepository('ObladyZuulBundle:Server');
        $keyRepository = $em->getRepository('ObladyZuulBundle:Key');
        $servers = $repository->findBy(array('enabled'=>true));
        $nbCheck = 0;
        if(!empty($servers)) {
           foreach($servers as $server)  {
               $output->writeln('checking  : '.$server->getName());
                if(!$server->isAlive()) {
                  $server->setReachable(false);
                      continue;
                }
               //$server->getUsers();
               $keyManager->setServer($server);
               if($keyManager->hasMasterkey()) {
                 $server->setMasterKey(true) ;
                 
                 
                  // on recup les clefs et on creer celle qui n'existe pas en base 
                   $remoteKeys = $keyManager->getRemotekeys();
                   if(!empty($remoteKeys)) {
                       foreach($remoteKeys as $rkey ) {
                          
                           $fg = $rkey->getFingerprint();
                           $tmp = $keyRepository->findOneBy(array('fingerprint'=>$fg));
                          
                           if(!$tmp) { // la clef n'est pas en base on la creer
                               $obj = new Key();
                               $obj->setContent($rkey->fullString());
                               $obj->generateFingerprint();
                               $obj->setName($rkey->getComment() ? $rkey->getComment(): 'unknow key');
                               $em->persist($obj);
                               $output->writeln('adding key : '. $obj->getName());
                               $em->flush(); 
                               $server->addKey($obj);
                               $obj->addServer($server);
                               $em->flush(); 
                           } else {
                              if(!$server->getKeys()->contains($tmp))  {
                                 $server->addKey($tmp) ;
                                 $tmp->addServer($server);
                                 $output->writeln('addding  relation between key '.$tmp->getName().' and server '.$server->getName()."\n"); 
                                 $em->flush();
                                  
                              }
                                 
                              if(false !=($u = $tmp->getUser())) {
                                  if(!$server->getUsers()->contains($u)) {
                                      $output->writeln('addding  relation between user '.$u->getUsername().' and server '.$server->getName()."\n"); 
                                      $server->addUser($u);
                                      $em->flush(); 
                                  }
                                  
                              }
                               
                           }
                          
                           
                       }
                   }
                   
                 //$em->persist($server);    
               } else {
                     $server->setMasterKey(false) ;
               }
               $nbCheck++;
           }
           
        }
        $output->writeln('checked : '.$nbCheck);
        $em->flush(); 

    }
     public function getDoctrineManager() {
        
      return  $this->getContainer()->get('doctrine')->getManager();
    }
    
    public function updatekeyFingerprints()
    {
        
        $em = $this->getDoctrineManager();
        $keyRepository = $em->getRepository('ObladyZuulBundle:Key');
        $localKeys = $tmp = $keyRepository->findBy(array('fingerprint'=>''));
        if(!empty($localKeys)) {
            foreach($localKeys as $lkey) {
                $lkey->generateFingerprint();
               
                
            }
        }
        $em->flush();
    }
    
}

?>
