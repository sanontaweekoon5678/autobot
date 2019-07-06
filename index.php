<?php
require_once('./vendor/autoload.php');

//Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

//Token
$channel_token='lWtY8ZGPyvz4W0zzz5KH4eHcR5cerA0sqGCEmjwu976pAaNsikSl4H931y8b1mBxR3GImGGUDhYM6RJKXyZVyQAVn0aY0zxmWxntOYGmw9tQl99TifEWI6oqPmnkhJF+b1V/pExiEk9/fW+7z9yYTwdB04t89/1O/w1cDnyilFU=';
$channel_secret='35e654c7cd3a978fab43c926c660dd98';

//LINEBot
$httpClient=new CurlHTTPClient($channel_token);
$bot=new LINEBot($httpClient, array('chanelSecret'-> $channel_secret));

//Get message from line API
$content=file_get_contents('php://input');
$events=json_decode($content, true);

if(!is_null($events['events'])){
    //Loop through each event
    foreach($events['events']as $event){
        //line API send a lot of event type, we interted in message only
        if($event['type']=='message'){
            $replyToken=$event['replyToken'];
            switch($event['message']['type']){
                
                case'text':
                    $respMessage= 'Hello, your messsage is '.$event['message']['text'];
                break;
                
                 case 'image':
                      $messageID=$event['message']['id'];
                      $respMessage= 'Hello, your image ID is '.$messageID;
                break;
                
                 case 'sticker':
                    $messageID=$event['message']['packageId'];
                    $respMessage='Hello, your Sticker Package ID is '.$messageID;
                break;

                 case 'video':
                    $messageID=$event['message']['id'];
                    $fileID=$event['message']['id'];
                    $response=$bot->getMessageContent($fileID);
                    $fileName='linebot.mp4';
                    $file=fopen($fileName, 'w');
                    fwrite($file, $response->getRawBody());
                    $respMessage='Hello, your video ID is '.$messageID;
                break;

                case 'audio':
                    $messageID=$event['message']['id'];
                    $fileID=$event['message']['id'];
                    $response=$bot->getMessageContent($fileID);
                    $fileName='linebot.m4a';
                    $file=fopen($fileName, 'w');
                    fwrite($file, $response->getRawBody());
                    $respMessage='Hello, your audio ID is '.$messageID;
                    break;

                case 'location':
                    $address=$event['message']['address'];
                    $respMessage='Hello, your address is '.$address;
                    break;
            }

                $textMessageBuilder = new TextMessageBuilder($respMessage);
                $response = $bot->replyMessage($replyToken, $textMessageBuilder);
            }
            if($event['type']=='follow'){
                $replyToken=$event['replyToken'];
                $respMessage='Thanks you I try to be your best friend';
                $httpClient=new CurlHTTPClient($channel_token);
                $bot=new LINEBot($httpClient, array('chanelSecret'-> $channel_secret));
                $textMessageBuilder=new TextMessageBuilder($respMessage);
                $response=$bot->replyMessage($replyToken, $textMessageBuilder);
            }
        }

    }
    

echo"OK";
