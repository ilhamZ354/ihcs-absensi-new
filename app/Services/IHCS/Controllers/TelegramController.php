<?php

namespace App\Services\IHCS\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IHCS\Foundation\Config;
use Illuminate\Support\Facades\Storage;
use \ZipArchive;
use App\Services\IHCS\Foundation\Curly;

class TelegramController extends Controller 
{
    use Curly;

    private $config;
    private $token;
    private $client;    
    private $body;    

    public function __construct($client = 'ptpn4') 
    {
        $this->config = new Config('telegram');

        $this->token = $this->config->get('bot')['token'];

        $this->client = $this->config->get('clients')[$client]['id'];
    }

    public function sendMessage(Request $request) {
        $request->validate([
            'chat_id' => 'required',
            'text' => 'required',
        ]);
        return $this->_sendMessage($request->chat_id, $request->text);
    }

    public function sendReport($message, $format = null) {
        return $this->_sendMessage($this->client, $message, $format);
    }

    private function _sendMessage($client_id = "225177928", $message, $format = null) {
        $params = [
            'command' => 'sendMessage',
            'chat_id' => $client_id,
            'text' => $message,
        ];

        if(!is_null($format)) {
            $params['parse_mode'] = $format;
        }

        return response()->json($this->run($params));
    }

    public function getUpdates() {
        $params = [
            'command' => 'getUpdates',
        ];
        return response()->json($this->run($params));
    }

    public function run($params = [])
    {
        $url = "https://api.telegram.org/{$this->token}/{$params['command']}";
        
        unset($params['command']);

        $proxy = [
            'ip'=>'10.10.5.200',
            'port'=>'8080',
        ];
        $this->curl($url,$params, '', '', '');

        return response()->json(json_decode($this->body))->original; 
    }

    public function sendDocument($file, $filename)
    {
        Storage::put($filename, $file);
        $zipfile =  storage_path($filename.'.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipfile, ZipArchive::CREATE) !== TRUE) {
            \Log::error("cannot open <$zipfile>");
            throw new \Exception("cannot open <$zipfile>\n");
        } else {
            \Log::info("file <$zipfile> success");
            $zip->addFile(storage_path($filename));
            $zip->close();
        }
        \Log::info("after if");

        // $filepath = Storage::get($filename . '.zip');
        // $filepath = $file;
        $post = [
            'chat_id' => $this->client,
            'document'=> $zipfile
        ];    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot685476259:AAGDatYYOof1wR30apP7pruq3Gi7x-CgbLE/sendDocument");
        if(env('CURL_PROXY_STATUS')) {
            curl_setopt($ch, CURLOPT_PROXY, '10.10.5.200');
            curl_setopt($ch, CURLOPT_PROXYPORT, '8080');
        }
        curl_setopt($ch, CURLOPT_POST, 1);   
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close ($ch); 
        return response()->json(json_decode($output))->original; 
    }
}
