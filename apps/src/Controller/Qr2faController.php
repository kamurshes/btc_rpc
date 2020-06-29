<?php
namespace App\Controller;

use App\Controller\AppController;
//use chillerlan\QRCode\QRcode;
use chillerlan\QRCode\{QRCode, QROptions};

class Qr2faController extends AppController
{

    public function index($secret)
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
	    $rawqr=(new QRCode)->render($secret);
	    // Bitcoinプロトコルを載せたQR
	    echo json_encode(
		    [
			    "2faqr"=>$rawqr
		    ]
	    );
	    //echo("url(".(new QRCode)->render($address).")");
	    //debug((new QRCode)->render($address));
	    exit();
    }
}
