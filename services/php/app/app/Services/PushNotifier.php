<?php

namespace App\Services;

use WebSocket\Client;

class PushNotifier
{
    protected $url;

    public function __construct()
    {
        $this->url = config('services.push-server.url');
    }

    public function sendNewOrder(array $order)
    {
        if($this->url == '') {
            return;
        }
        $client = new Client($this->url);

        $client->send(json_encode([
            'type' => 'new_order',
            'order' => $order
        ]));

        $client->close();
    }
    public function sendProductoPedidoPreparado(array $productoPedido)
    {
        if($this->url == '') {
            return;
        }
        $client = new Client($this->url);

        $client->send(json_encode([
            'type' => 'item_prepared',
            'item' => $productoPedido
        ]));

        $client->close();
    }
}
