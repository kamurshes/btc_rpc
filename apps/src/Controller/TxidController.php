<?php
namespace App\Controller;

use App\Controller\AppController;
use Denpa\Bitcoin\Client as BitcoinClient;

class TxidController extends AppController
{

    public function index($txid)
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
/*
	$balance=$bitcoind->getbalance()->result();
	if($balance>0.0001)
	{
		$amount=0.0001;
	}
	$to="1MVy31PwGCYarDi9ZgUHpaDVys87RBVmcL";
	$to="3Lz3dQgVj3zWAvntUue3TH5rjYWYpU8N7s";
	$result=$bitcoind->sendtoaddress($to,$amount,"TEST SENDING","K2I")->result();
	debug($result);
 */


	//debug($address);
	//$address="32FvdGmucBncAvbB1C8QemCofRgR6m9YAT";
	$txid_info=$bitcoind->gettransaction($txid)->result();
	echo json_encode($txid_info);
	 
    }
}
