<?php
namespace App\Controller;

use App\Controller\AppController;
//use chillerlan\QRCode\QRcode;
use chillerlan\QRCode\{QRCode, QROptions};

class QrController extends AppController
{

    public function index($address,$amount)
    {


	/*
	    // Bitflyerの価格を参照する
	    $xChange="bitflyer";
	    $CCXT_A = '\ccxt\\'.$xChange ;
	    try{
		    //$xChangeA = new $CCXT_A(['apiKey' => $apiKey,'secret' => $secret ,'enableRateLimit' => true]);
		    $xChangeA = new $CCXT_A(['enableRateLimit' => true]);
		    $xChangeA_Fee = $xChangeA->describe()['fees']['trading']['maker'];
		    $BTC_price=$xChangeA->fetch_ticker('BTC/JPY',array())['ask'];
	    }catch(Exception $e){
		    //continue;
		    debug($e);
	    }

	    //--- ビットコインの金額を計算する  ---
	    // 3800円を変数に入れる
	    $price=3800;
	    $BTC=$price/$BTC_price;

	    //echo "価格は：".$BTC;
	 */
	    $this->viewBuilder()->setLayout('default');
	    //$data="hogehoge";
	    // 生のQRコードデータ
	    $rawqr=(new QRCode)->render($address);
	    // Bitcoinプロトコルを載せたQR
	    $btcqr=(new QRCode)->render("bitcoin:".$address."?amount=".$amount);
	    echo json_encode(
		    [
			    "address"=>$address,
			    "rawqr"=>$rawqr,
			    "btcqr"=>$btcqr
		    ]
	    );
	    //echo("url(".(new QRCode)->render($address).")");
	    //debug((new QRCode)->render($address));
    }
}
