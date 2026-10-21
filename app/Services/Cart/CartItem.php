<?php

namespace App\Services\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Fluent;

class CartItem implements Arrayable, Jsonable
{
    public string $rowId;

    public mixed $id;

    public string $name;

    public int $qty;

    public float $price;

    public Fluent $options;

    public float $taxRate;

    public function __construct(mixed $id, string $name, float $price, int $qty = 1, array $options = [], float $taxRate = 0.0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->qty = max(1, $qty);
        $this->options = new Fluent($options);
        $this->taxRate = $taxRate;
        $this->rowId = $this->generateRowId($id, $options);
    }

    public static function fromAttributes(array $attributes, float $defaultTaxRate = 0.0): self
    {
        $id = $attributes['id'] ?? null;
        $name = $attributes['name'] ?? '';
        $price = (float) ($attributes['price'] ?? 0);
        $qty = (int) ($attributes['qty'] ?? ($attributes['quantity'] ?? 1));
        $options = (array) ($attributes['options'] ?? []);
        $taxRate = (float) ($attributes['taxRate'] ?? $defaultTaxRate);

        return new self($id, $name, $price, $qty, $options, $taxRate);
    }

    public function generateRowId(mixed $id, array $options): string
    {
        ksort($options);

        return md5($id.serialize($options));
    }

    public function subtotal(): float
    {
        return $this->price * $this->qty;
    }

    public function tax(): float
    {
        return $this->subtotal() * ($this->taxRate / 100);
    }

    public function total(): float
    {
        return $this->subtotal() + $this->tax();
    }

    public function __get(string $property): mixed
    {
        if ($property === 'subtotal') {
            return $this->subtotal();
        }

        if ($property === 'tax') {
            return $this->tax();
        }

        if ($property === 'total') {
            return $this->total();
        }

        return $this->options->{$property} ?? null;
    }

    public function toArray(): array
    {
        return [
            'rowId' => $this->rowId,
            'id' => $this->id,
            'name' => $this->name,
            'qty' => $this->qty,
            'price' => $this->price,
            'options' => $this->options->toArray(),
            'taxRate' => $this->taxRate,
            'subtotal' => $this->subtotal(),
            'tax' => $this->tax(),
            'total' => $this->total(),
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
