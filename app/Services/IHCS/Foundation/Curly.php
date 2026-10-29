<?php

namespace  App\Services\IHCS\Foundation;

use App\Jobs\TelegramJob;
use Log;

trait Curly {

    protected $url = "https://apisso.ptpn4.co.id/sso/";
	
	protected function curl($url, $post = '', $ref = '', $header = '', $proxy = '')
	{
		$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        // if($proxy) {
        //     curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
        //     curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
        // }
            
        if($header) 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if ($ref)
            curl_setopt($ch, CURLOPT_REFERER, $ref);
        else
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        //curl_setopt($ch, CURLOPT_VERBOSE, 1);

        $this->body = curl_exec($ch);
        //$this->curl_info = curl_getinfo($ch);
        if (curl_errno($ch)) {
            $this->handleCurlError($ch, $url, $post, $ref, $header, $proxy);
        }

        curl_close($ch);
    }

    protected function ssoCurl($postfixUrl, $post = '', $ref = '')
    {
        $ch = curl_init(env('SSO_API_URL', $this->url) . $postfixUrl);
        curl_setopt($ch, CURLOPT_PROXY, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);

        if ($ref)
            curl_setopt($ch, CURLOPT_REFERER, $ref);
        else
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        //curl_setopt($ch, CURLOPT_VERBOSE, 1);

        $this->body = curl_exec($ch);
        //$this->curl_info = curl_getinfo($ch);
        if (curl_errno($ch))
            throw new \Exception(curl_error($ch));

        curl_close($ch);
    }

    private function handleCurlError($ch, $url, $post, $ref, $header, $proxy)
    {
      switch (curl_errno($ch)) {
          case '6':
              //send error  message to telegram channel
              TelegramJob::dispatch("<b>".env('APP_NAME') . " CURL Error:</b>\nUrl: <code>{$url}</code>\nMessage: " . curl_error($ch), 'harapan');
              break;
          case '28':
              //write error  to log
              TelegramJob::dispatch("<b>".env('APP_NAME') . " CURL Error:</b>\nUrl: <code>{$url}</code>\nMessage: " . curl_error($ch), 'harapan');
              Log::error("Curl Error: url({$url}}. Message: " . curl_error($ch));
              sleep(5);
              //retry
              $this->curl($url, $post, $ref, $header, $proxy);
              break;
          default:
              throw new \Exception(curl_error($ch));
              break;
      }
    }
    
}