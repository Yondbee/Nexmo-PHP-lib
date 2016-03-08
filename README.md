# Nexmo-PHP-lib

Sending SMS via the Nexmo SMS gateway.

## Quick Examples

1. Sending an SMS
```php
$sms = new NexmoMessage('account_key', 'account_secret');
$sms->sendText( '+447234567890', 'MyApp', 'Hello world!' );
```

2. Recieving SMS 
```php
$sms = new NexmoMessage('account_key', 'account_secret');
if ($sms->inboundText()) {
    $sms->reply('You said: ' . $sms->text);
}
```

3. Recieving a message receipt
```php
$receipt = new NexmoReceipt();
if ($receipt->exists()) {
    switch ($receipt->status) {
        case $receipt::STATUS_DELIVERED:
             // The message was delivered to the handset!
             break;
         
         case $receipt::STATUS_FAILED:
         case $receipt::STATUS_EXPIRED:
             // The message failed to be delivered
             break;
    }
}
```

4. List purchased numbers on your account
```php
$account = new NexmoAccount('account_key', 'account_secret');
$numbers = $account->numbersList();
```

## Most Frequent Issues

### Sending a message returns false.

This is usually due to your webserver unable to send a request to Nexmo. Make sure the following are met:

1. Either CURL is enabled for your PHP installation or the PHP option `allow_url_fopen` is set to `1` (default).
    
2. You have no firewalls blocking access to `rest.nexmo.com/sms/json` on port 443.