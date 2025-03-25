<?php

namespace GBGCO\Types;

use GBGCO\Client;

class Events
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}