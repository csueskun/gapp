<?php
namespace App\Util;
class PushService{
    
    public static function push($canal,$evento,$data){
        require_once 'Pusher.php';
        $options = array(
            'cluster' => 'us2',
            'encrypted' => true
        );
        $pusher = new Pusher(
                'a64aeef2e8202a8b1d97', '00ce75149f0cbf99d77d', '360945', $options
        );
        $pusher->trigger($canal, $evento, $data);
    }
    
}