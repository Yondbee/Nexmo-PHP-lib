<?php


class NexmoVerify extends NexmoMessage {

    function NexmoVerify ($api_key, $api_secret) {
        parent::NexmoMessage($api_key, $api_secret);

        // setup verify parameters
        $this->nx_uri = 'https://api.nexmo.com/verify/json';

        $this->nx_key_varname = 'api_key';
        $this->nx_secret_varname = 'api_secret';
    }

    public function sendVerify($to, $brand, $from = null, $code_length = null, $lg = null)
    {
        $post = [];
        if ($from !== null && !is_numeric($from) && !mb_check_encoding($from, 'UTF-8') ) {
            error_log('$from needs to be a valid UTF-8 encoded string');
            return false;
        }
        else {
            $post['sender_id'] = $this->validateOriginator($from);
        }

        // Send away!
        $post['number'] = $to;
        $post['brand'] = $brand;

        // optional parameters
        if (!empty($code_length))
            $post['code_length'] = intval($code_length);

        if (!empty($lg))
            $post['lg'] = $lg;

        $response = $this->sendRequest ( $post );

        if ($response === false || empty($response) || !property_exists($response, 'status'))
        {
            error_log('[NEXMO] Invalid response received from server');
            return false;
        }

        if ($response->status != 0)
        {
            error_log('[NEXMO] Error response received from server: ' . $response->error_text);
            return false;
        }

        return $response->request_id;
    }


    public function checkVerify($request_id, $code, $ip_address = null)
    {
        $post = [
                'request_id' => $request_id,
                'code' => $code
            ];

        // optional parameters
        if (!empty($ip_address))
            $post['ip_address'] = $ip_address;
        else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']))
            $post['ip_address'] = $_SERVER['REMOTE_ADDR'];

        $response = $this->sendRequest ( $post, 'https://api.nexmo.com/verify/check/json' );

        if ($response === false || empty($response) || !property_exists($response, 'status'))
        {
            error_log('[NEXMO] Invalid response received from server');
            return false;
        }

        if ($response->status != 0)
        {
            error_log('[NEXMO] Error response received from server: ' . $response->error_text);
            return false;
        }

        return true;
    }
} 