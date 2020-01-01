<?php
namespace App\Controller;

use App\Controller\AppController;
use Denpa\Bitcoin\Client as BitcoinClient;

class UserinfoController extends AppController
{

    public function index($userid_timestamp)
    {

	$bitcoind = new BitcoinClient([
	    'scheme'        => 'http',                 // optional, default http
	    'host'          => '13.230.125.114',            // optional, default localhost
	    'port'          => 8332,                   // optional, default 8332
	    'user'          => 'kamurshes',              // required
	    'password'      => 'hh28CsU7FjB3',          // required
	    //'ca'            => '/etc/ssl/ca-cert.pem',  // optional, for use with https scheme
	    'preserve_case' => false,                  // optional, send method names as defined instead of lowercasing them
	]);	    

	$info=$bitcoind->getaddressesbylabel($userid_timestamp)->result();
	$address=array_keys($info)[0];

	//debug($address);
	//$address="32FvdGmucBncAvbB1C8QemCofRgR6m9YAT";
	$address_info=$bitcoind->listtransactions($userid_timestamp)->result();
	foreach($address_info as $A)
	{
		if($A['address']===$address)
		{
				echo json_encode([
					'address'=>$A['address'],
					'amount'=>$A['amount'],
					'confirmations'=>$A['confirmations'],
					'txid'=>$A['txid']
			]);
		}
	}

	 
    }
}
