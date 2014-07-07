<?php
// fichier a transformer en vrai test unitraire tant qu'as faire 


require_once dirname(__FILE__).'/AuthorizedKey.php';

$key1 = new AuthorizedKey('ssh-rsa AAAAB3NzaLiPk== user@example.net');
echo $key1;


$key1 = new AuthorizedKey('from="*.sales.example.net,!pc.sales.example.net" ssh-rsa AAAAB2...19Q== john@example.net');
echo $key1;

$key1 = new AuthorizedKey(' command="dump /home",no-pty,no-port-forwarding ssh-dss AAAAC3...51R== example.net');
echo $key1;

$key1 = new AuthorizedKey('permitopen="192.0.2.1:80",permitopen="192.0.2.2:25" ssh-dss AAAAB5...21S==');
echo $key1;
     
$key1 = new AuthorizedKey('  tunnel="0",command="sh /etc/netstart tun0" ssh-rsa AAAA...== jane@example.net');
echo $key1;
        
      
$key1 = new AuthorizedKey('tunnel="0",command="sh \"/etc/netstart\" tun0" ssh-rsa AAAA...== jane@example.net');
echo $key1;
     