<?php

namespace Meisterwerk\BqUtils\OrderProperties;

class BqOrderPropertyQuery
{
    private string $name;
    private string $identifier;
    private string $type;

    public function __construct(string $name, string $identifier, string $type)
    {
        $this->name = $name;
        $this->identifier = $identifier;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getType(): string
    {
        return $this->type;
    }
}