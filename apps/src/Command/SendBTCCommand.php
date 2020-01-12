<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Log\Log;
use Psy\Shell as PsyShell;
use Denpa\Bitcoin\Client as BitcoinClient;

/**
 * Simple console wrapper around Psy\Shell.
 */
class SendBTCCommand extends Command
{
    /**
     * Start the Command and interactive console.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
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
	//外部のアドレス
	//$to="3Lz3dQgVj3zWAvntUue3TH5rjYWYpU8N7s";

	//井上さんのBTCアドレス
	$to="1MVy31PwGCYarDi9ZgUHpaDVys87RBVmcL";

	//内部のアドレス
	//$to="37ZD7hu8gcjA7HqCDYmWiXGZBp5CytL9Qv";
        $amount=$bitcoind->getbalance()->result();
	debug("現在の残高：".$amount." BTC");
	if($amount>0.05)
	{
		debug("残高が0.05以上だったので、手数料を引いて全額送付する");
		$fees=$bitcoind->estimatesmartfee(1)['feerate'];
		debug("手数料：".$fees."BTC");
		$calc_amount=$amount-$fees*2;
		debug("送付する額面：".$calc_amount);
		//return;
	}else{
		debug($amount);
		debug("額面に到達していなかったので送付しません");
		return;
	}

	try
	{
	        $result=$bitcoind->sendtoaddress($to,$calc_amount,"EVISU Mining","K System")->result();
		$io->out($result);
	}
	catch(Exception $e)
	{
		debug($e);
	}
    
    }

}
