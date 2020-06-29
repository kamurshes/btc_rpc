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

// テーブルにアクセスするためのレジストリの使用
use Cake\ORM\TableRegistry;

/**
 * Simple console wrapper around Psy\Shell.
 */
class CheckBTCCommand extends Command
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

	/*
	unset($Data);
	$Data=self::fGetUserInfo("sales3");
	self::fCheckData($Data);
	*/

	unset($Data);
	$table="sales3";
	$Data=self::fGetUserInfo($table);
	self::fCheckData($Data,$table);

	unset($Data);
	$table="sales6";
	$Data=self::fGetUserInfo($table);
	self::fCheckData($Data,$table);

	unset($Data);
	$table="sales7";
	$Data=self::fGetUserInfo($table);
	self::fCheckData($Data,$table);
    }

    public function fGetUserInfo($table)
    {
	    $Table = TableRegistry::get($table);
	    $Data=$Table->find()->where(["txid IS NULL"])->toArray();
	    return $Data;
    }
    public function fCheckData($Data,$table)
    {
	foreach($Data as $A)
	{
		$userid_timestamp=$A->userid_timestamp;

		debug($userid_timestamp);
		try{
			$txid=self::fGetTxid($userid_timestamp);
		}catch(Exception $ex){
			debug($ex);
			$txid=null;
		}

		if(is_null($txid))
		{
			continue;
		}
		else
		{
			self::fUpdateUserInfo($userid_timestamp,$txid,$table);
		}

	}

    }

    public function fGetTxid($userid_timestamp)
    {
	    $base_url="https://btcnode.btc-lotters.com/userinfo/".$userid_timestamp;
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $base_url);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す
	    $response = curl_exec($curl);
	    $result = json_decode($response, true);
	    curl_close($curl);
	    return $result['txid'];
    }

    public function fUpdateUserInfo($userid_timestamp,$txid,$table)
    {
	    $Table = TableRegistry::get($table);
	    $Data=$Table->find()->where(["userid_timestamp"=>$userid_timestamp,"txid is null"])->first();
	    debug($Data);
	    $Data->txid=$txid;
	    $Table->save($Data);
	    // PUSH通知を流す
	    self::fPushMsg($Data['userid'],$txid);
	    // メールを送付する
	    self::fPushEmail($Data['userid'],$txid);
    }


    public function fPushMsg($userid,$txid)
    {
	    $base_url="https://btc-lotters.com/line/".$userid."/".$txid;
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $base_url);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す
	    $response = curl_exec($curl);
	    $result = json_decode($response, true);
	    curl_close($curl);
	    return $result;
    }

    public function fPushEmail($userid,$txid)
    {
	    $base_url="https://btc-lotters.com/email/".$userid."/".$txid;
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $base_url);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す
	    $response = curl_exec($curl);
	    $result = json_decode($response, true);
	    curl_close($curl);
	    return $result;
    }

}
