<?php
#
#  Poloniex API. Copyright (C) 2017, Grigor Gatchev. All rights reserved.
#
#  The query() method is based on the Poloniex API wrapper by CompCentral.
#
# ---------------------------------------------------------------------------- #



class Poloniex_API
{

    public $max_calls_per_sec = 5;  // Poloniex allows 6, but let's be nice.
    public $max_call_retries = 10;
    public $usecs_delay_between_call_retries = 1000000;


    protected $calltimes = array();

    private $trading_url = "https://poloniex.com/tradingApi";
    private $public_url  = "https://poloniex.com/public";


    # ----- Constructor ----- #


    function __construct( $api_key, $api_secret )
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }


    # ----- Querying the API ----- #


    private function query( $req )
    {
        $microtime = explode( ' ', microtime() );
        $req['nonce'] = $microtime[1] . substr( $microtime[0], 2, 6 );

        $post_data = http_build_query( $req, '', '&' );
        $sign = hash_hmac( 'sha512', $post_data, $this->api_secret );

        $headers = array(
            'Key: ' . $this->api_key,
            'Sign: ' . $sign,
        );

        static $ch = null;
        if ( is_null( $ch ) )
        {
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_USERAGENT,
                'Mozilla/4.0 (compatible; Poloniex PHP bot; ' . php_uname('a') .
                '; PHP/' . phpversion() . ')'
            );
        }
        curl_setopt( $ch, CURLOPT_URL, $this->trading_url );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

        // ensure no more than $this->max_calls_per_sec
        if ( count( $this->calltimes ) == $this->max_calls_per_sec )
        {
            $deadline = reset( $this->calltimes ) + 1000000;

            while ( $deadline < microtime( true ) )
                usleep( 100000 );
        }


        $retries_count = 0;
        while ( ! isset( $res ) || ( $res === false ) )
        {

            $res = curl_exec($ch);

            if ($res !== false)
            {
                $dec = json_decode($res, true);
                if ( $dec !== false )
                {
                    // add the last call time to the call rate limiting queue
                    if ( count( $this->calltimes ) == $this->max_calls_per_sec )
                        array_shift( $this->calltimes );
                    $this->calltimes[] = microtime( true );

                    return $dec;
                }
            }

            $retries_count++;
            if ( $retries_count == $this->max_call_retries )
                break;
        }

        return false;
    }


    protected function retrieveJSON ( $req )
    {
        $opts = array(
          'http' =>
            array(
                'method'  => 'GET',
                'timeout' => 10
            )
        );
        $context = stream_context_create($opts);

        $POST = array();
        foreach ( $req as $key => $value )
            $POST[] = $key . '=' . $value;
        $URL = $this->public_url . '?' . implode( $POST, '&' );

        $retries_count = 0;
        while ( ! isset( $result ) || ( $result === false ) )
        {
            $feed = file_get_contents( $URL, false, $context );

            $result = json_decode( $feed, true );

            $retries_count++;
            if ( $retries_count == $this->max_call_retries )
                break;
        }

        return $result;
    }


    # ----- Public API calls ----- #


    public function get_ticker()
    {
        $req = array(
            'command' => 'returnTicker'
        );

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }
public function get_trad( $currencyPair )
    {
            $req = array(
            'command' => 'returnTradeHistory'
        );
            $date = new DateTime();
            $req['start'] = $date->getTimestamp()-18000000;
            $req['end'] = $date->getTimestamp();
            $req['currencyPair'] = strtoupper( $currencyPair );

        return $this->query( $req );
    
      
    }

    public function get_24_volume()
    {
        $req = array(
            'command' => 'return24Volume'
        );

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }


    public function get_order_book()
    {
        $req = array(
            'command' => 'returnOrderBook'
        );

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }

/* duplicated and superceded in the trading API calls
    public function get_trade_history()
    {
        $req = array(
            'command' => 'returnTradeHistory'
        );

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }
*/

    public function get_chart_data( $currencyPair )
    {
        $req = array(
            'command' => 'returnChartData'
        );

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }
    
    public function get_chart($currencyPair)
    {
            $req = array(
            'command' => 'returnChartData'
        );
            $currencyPair = 'USDT_BTC';
            $req['currencyPair'] = strtoupper( $currencyPair );
            $date = new DateTime();
            $req['start'] = $date->getTimestamp()-86400;
            $req['end'] = $date->getTimestamp();
            $req['period'] = 300;
            

        return $this->query( $req );
    
      
    }

    public function get_currencies()
    {
        $req = array(
            'command' => 'returnCurrencies'
        );

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }


    public function get_loan_orders( $currency, $limit = NULL )
    {
        $req = array(
            'command' => 'returnLoanOrders'
        );
        $req['currency'] = strtoupper( $currency );
        if ( ! is_null( $limit ) )
            $req['limit'] = $limit;

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }



    # ----- Trading API calls ----- #


    public function get_balances()  // as of 2017-05-08, returns only zeroes even if you have a deposit!
    {
        $req = array(
            'command' => 'returnBalances'
        );

        return $this->query( $req );
    }


    public function get_complete_balances( $add_margin_and_loans = false )
    {
        $req = array(
            'command' => 'returnCompleteBalances'
        );
        if ( $add_margin_and_loans )
            $req['account'] = "all";

        return $this->query( $req );
    }


    public function get_deposit_addresses()
    {
        $req = array(
            'command' => 'returnDepositAddresses'
        );

        return $this->query( $req );
    }


    public function generate_new_address( $currency )
    {
        $req = array(
            'command' => 'generateNewAddress'
        );
        $req['currency'] = strtoupper( $currency );

        return $this->query( $req );
    }


    public function get_deposits_withdrawals( $start, $end )  // both must be UNIX timestamps
    {
        $req = array(
            'command' => 'returnDepositsWithdrawals'
        );
        $req['start'] = $start;
        $req['end'] = $end;

        return $this->query( $req );
    }


    public function get_open_orders( $currencyPair = 'all' )
    {
        $req = array(
            'command' => 'returnOpenOrders'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );

        return $this->query( $req );
    }


    public function get_trade_history( $currencyPair = 'all',
      $start = NULL, $end = NULL )  // either set both $start and $end, or none
    {
        $req = array(
            'command' => 'returnTradeHistory'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );
        if ( ! is_null( $start ) )
            $req['start'] = $start;
        if ( ! is_null( $end ) )
            $req['end'] = $end;

        // return $this->query( $req );
        return $this->retrieveJSON( $req );
    }


    public function get_order_trades( $orderNumber )
    {
        $req = array(
            'command' => 'returnOrderTrades'
        );
        $req['orderNumber'] = $orderNumber;

        return $this->query( $req );
    }


    public function buy( $currencyPair, $rate, $amount )
    {
        $req = array(
            'command' => 'buy'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );
        $req['rate'] = $rate;
        $req['amount'] = $amount;

        return $this->query( $req );
    }


    public function sell( $currencyPair, $rate, $amount )
    {
        $req = array(
            'command' => 'sell'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );
        $req['rate'] = $rate;
        $req['amount'] = $amount;

        return $this->query( $req );
    }


    public function cancel_order( $orderNumber )
    {
        $req = array(
            'command' => 'cancelOrder'
        );
        $req['orderNumber'] = $orderNumber;

        return $this->query( $req );
    }


    public function move_order( $orderNumber, $rate, $amount = NULL,
      $postOnly = false, $immediateOrCancel = false )
    {
        $req = array(
            'command' => 'moveOrder'
        );
        $req['orderNumber'] = $orderNumber;
        $req['rate'] = $rate;
        if ( ! is_null( $amount ) )
            $req['amount'] = $amount;
        if ( $postOnly )
            $req['postOnly'] = 1;
        if ( $immediateOrCancel )
            $req['immediateOrCancel'] = 1;

        return $this->query( $req );
    }


    public function withdraw( $currency, $amount, $address, $paymentId = NULL )
    {
        $req = array(
            'command' => 'withdraw'
        );
        $req['currency'] = strtoupper( $currency );
        $req['amount'] = $amount;
        $req['address'] = $address;
        if ( ! is_null( $paymentId ) )  // valid only for XMR withdrawals
            $req['paymentId'] = $paymentId;

        return $this->query( $req );
    }


    public function get_fee_info()
    {
        $req = array(
            'command' => 'returnFeeInfo'
        );

        return $this->query( $req );
    }


    public function get_available_account_balances( $account = NULL )
    {
        $req = array(
            'command' => 'returnAvailableAccountBalances'
        );
        if ( ! is_null( $account ) )
            $req['account'] = $account;

        return $this->query( $req );
    }


    public function get_tradable_balances()
    {
        $req = array(
            'command' => 'returnTradableBalances'
        );

        return $this->query( $req );
    }


    public function transfer_balance( $currency, $amount,
      $fromAddress, $toAddress )
    {
        $req = array(
            'command' => 'transferBalance'
        );
        $req['currency'] = strtoupper( $currency );
        $req['amount'] = $amount;
        $req['fromAddress'] = $fromAddress;
        $req['toAddress'] = $toAddress;

        return $this->query( $req );
    }


    public function get_margin_account_summary()
    {
        $req = array(
            'command' => 'returnMarginAccountSummary'
        );

        return $this->query( $req );
    }


    public function margin_buy( $currencyPair, $rate, $amount,
      $lengingRate = NULL )
    {
        $req = array(
            'command' => 'marginBuy'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );
        $req['rate'] = $rate;
        $req['amount'] = $amount;
        if ( ! is_null( $lendingRate ) )
            $req['lendingRate'] = $lendingRate;

        return $this->query( $req );
    }


    public function margin_sell( $currencyPair, $rate, $amount,
      $lengingRate = NULL )
    {
        $req = array(
            'command' => 'marginSell'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );
        $req['rate'] = $rate;
        $req['amount'] = $amount;
        if ( ! is_null( $lendingRate ) )
            $req['lendingRate'] = $lendingRate;

        return $this->query( $req );
    }


    public function get_margin_position( $currencyPair )
    {
        $req = array(
            'command' => 'getMarginPosition'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );

        return $this->query( $req );
    }


    public function close_margin_position( $currencyPair )
    {
        $req = array(
            'command' => 'closeMarginPosition'
        );
        $req['currencyPair'] = strtoupper( $currencyPair );

        return $this->query( $req );
    }


    public function create_loan_offer( $currency, $amount, $duration,
      $lendingRate, $autoRenew = true )
    {
        $req = array(
            'command' => 'createLoanOffer'
        );
        $req['currency'] = strtoupper( $currency );
        $req['amount'] = $amount;
        $req['duration'] = $duration;
        $req['lendingRate'] = $lendingRate;
        $req['autoRenew'] = ( $autoRenew ? 1 : 0 );

        return $this->query( $req );
    }


    public function cancel_loan_offer( $orderNumber )
    {
        $req = array(
            'command' => 'cancelLoanOffer'
        );
        $req['orderNumber'] = $orderNumber;

        return $this->query( $req );
    }


    public function get_open_loan_offers()
    {
        $req = array(
            'command' => 'returnOpenLoanOffers'
        );

        return $this->query( $req );
    }


    public function get_active_loans()
    {
        $req = array(
            'command' => 'returnActiveLoans'
        );

        return $this->query( $req );
    }


    public function get_lending_history( $start = NULL, $end = NULL )  // both must be UNIX timestamps
    {
        $req = array(
            'command' => 'returnLendingHistory'
        );
        if ( ! is_null( $start ) )
            $req['start'] = $start;
        if ( ! is_null( $end ) )
            $req['end'] = $end;

        return $this->query( $req );
    }


    public function toggle_auto_renew( $orderNumber )
    {
        $req = array(
            'command' => 'toggleAutoRenew'
        );
        $req['orderNumber'] = $orderNumber;

        return $this->query( $req );
    }


}


if ( ! function_exists( 'curl_exec' ) )
    die( "To work, the Poloniex API needs the PHP CURL extension. Please install it." );
