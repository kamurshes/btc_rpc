<?php
namespace App\Controller;

use App\Controller\AppController;
use Denpa\Bitcoin\Client as BitcoinClient;

class NewaddressController extends AppController
{

    public function index($userid)
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

	$address=$bitcoind->getnewaddress($userid);
	
	
	echo json_encode(
                    [
                            "address"=>$address,
                    ]
	    );
	 
    }
}
