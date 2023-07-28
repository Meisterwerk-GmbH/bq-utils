<?php

namespace Meisterwerk\BqUtils\OrderProperties;

class BqOrderPropertyQuery
{
    private string $name;
    private string $identifier;

    public function __construct(string $name, string $identifier)
    {
        $this->name = $name;
        $this->identifier = $identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}