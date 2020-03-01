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
class SendBTCForServerCommand extends Command
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
	$to="3Bkt5KomPCsYgR3yzccs1NWqHMbEy4Q1ny";

	//井上さんのBTCアドレス
	//$to="1MVy31PwGCYarDi9ZgUHpaDVys87RBVmcL";

	//内部のアドレス
	//$to="37ZD7hu8gcjA7HqCDYmWiXGZBp5CytL9Qv";
        $_amount=$bitcoind->getbalance()->result();
	$amount=sprintf("%F",$_amount);
	debug("現在の残高：".$amount." BTC");
	if($amount>0.15)
	{
		debug("毎月のサーバ代金を徴収するためのプログラム");
		$fees=$bitcoind->estimatesmartfee(1)['feerate'];
		debug("手数料：".$fees."BTC");
		$calc_amount=0.1;
		debug("送付する額面：".$calc_amount);
		//return;
	}else{
		debug(sprintf("%F",$amount));
		debug("額面に到達していなかったので送付しません");
		//$result="fdd9a59efe22938978b8b54ce534c0265d5039d93f5f659bc0dba3458d82a1a4";
		//$calc_amount="0.999";
		//self::fDiscord($result,$calc_amount);
		return;
	}

	/**/
	try
	{
	        $result=$bitcoind->sendtoaddress($to,$calc_amount,"EVISU Mining","K System")->result();
		$io->out($result);
		self::fDiscord($result,$calc_amount);
	}
	catch(Exception $e)
	{
		debug($e);
	}

    }

    public function fDiscord($result,$amount)
    {
 	/**/

	// Discord
	//=======================================================================================================
	//// Create new webhook in your Discord channel settings and copy&paste URL
	////=======================================================================================================
	$webhookurl = "https://discordapp.com/api/webhooks/665940509944053778/8HfSbXTsJBtzlAbnsOhpYRhMQeRPh7fEGzczxVMgTnLeuLObk_i_107C7z14h9TUNdnH";
	//=======================================================================================================
	// Compose message. You can use Markdown
	// Message Formatting -- https://discordapp.com/developers/docs/reference#message-formatting
	//========================================================================================================
	$msg = "```BTCの自動送付完了↓```" ."\n" . "TXID：[".$result."](https://www.blockchain.com/btc/tx/".$result.")"."\n"."額面：".$amount." BTC";
	$json_data = array ('content'=>"$msg");
	$make_json = json_encode($json_data);
	$ch = curl_init( $webhookurl );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $make_json);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec( $ch );
	//If you need to debug, or find out why you can't send message uncomment line below, and execute script.
	debug($response);
	// Discord
    }

}
