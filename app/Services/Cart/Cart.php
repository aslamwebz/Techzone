<?php

namespace App\Services\Cart;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class Cart
{
    protected Session $session;

    protected ?Dispatcher $events;

    protected ?DatabaseManager $database;

    protected string $instance = 'default';

    protected float $taxRate = 0.0;

    public function __construct(Session $session, ?Dispatcher $events = null, ?DatabaseManager $database = null)
    {
        $this->session = $session;
        $this->events = $events;
        $this->database = $database;
        $this->taxRate = (float) config('cart.tax', 0);
    }

    public function instance(?string $instance = null): self
    {
        $instance = $instance ?: 'default';
        $this->instance = $instance;

        return $this;
    }

    public function currentInstance(): string
    {
        return $this->instance;
    }

    public function add(mixed $id, ?string $name = null, int $qty = 1, float $price = 0.0, array $options = []): CartItem
    {
        if (is_array($id)) {
            $cartItem = CartItem::fromAttributes($id, $this->taxRate);
        } else {
            $cartItem = new CartItem($id, (string) $name, (float) $price, $qty, $options, $this->taxRate);
        }

        $content = $this->getContent();

        if ($content->has($cartItem->rowId)) {
            $existing = $content->get($cartItem->rowId);
            $cartItem->qty += $existing->qty;
        }

        $content->put($cartItem->rowId, $cartItem);
        $this->saveContent($content);

        if ($this->events) {
            $this->events->dispatch('cart.added', [$cartItem]);
        }

        return $cartItem;
    }

    public function update(string $rowId, mixed $qty): ?CartItem
    {
        $content = $this->getContent();

        if (! $content->has($rowId)) {
            return null;
        }

        $cartItem = $content->get($rowId);

        if (is_array($qty)) {
            if (isset($qty['qty'])) {
                $cartItem->qty = (int) $qty['qty'];
            }
            if (isset($qty['price'])) {
                $cartItem->price = (float) $qty['price'];
            }
            if (isset($qty['name'])) {
                $cartItem->name = (string) $qty['name'];
            }
        } else {
            $cartItem->qty = (int) $qty;
        }

        if ($cartItem->qty <= 0) {
            $this->remove($rowId);

            return null;
        }

        $content->put($rowId, $cartItem);
        $this->saveContent($content);

        if ($this->events) {
            $this->events->dispatch('cart.updated', [$cartItem]);
        }

        return $cartItem;
    }

    public function remove(string $rowId): bool
    {
        $content = $this->getContent();

        if (! $content->has($rowId)) {
            return false;
        }

        $cartItem = $content->get($rowId);
        $content->forget($rowId);
        $this->saveContent($content);

        if ($this->events) {
            $this->events->dispatch('cart.removed', [$cartItem]);
        }

        return true;
    }

    public function get(string $rowId): ?CartItem
    {
        return $this->getContent()->get($rowId);
    }

    public function destroy(): void
    {
        $this->session->forget($this->getSessionKey());

        if ($this->events) {
            $this->events->dispatch('cart.destroyed');
        }
    }

    public function content(): Collection
    {
        return $this->getContent();
    }

    public function count(): int
    {
        return (int) $this->getContent()->sum('qty');
    }

    public function subtotal(int $decimals = 2, string $decimalPoint = '.', string $thousandSeparator = ''): string
    {
        $subtotal = $this->getContent()->sum(fn (CartItem $item) => $item->subtotal());

        return number_format($subtotal, $decimals, $decimalPoint, $thousandSeparator);
    }

    public function tax(int $decimals = 2, string $decimalPoint = '.', string $thousandSeparator = ''): string
    {
        $tax = $this->getContent()->sum(fn (CartItem $item) => $item->tax());

        return number_format($tax, $decimals, $decimalPoint, $thousandSeparator);
    }

    public function total(int $decimals = 2, string $decimalPoint = '.', string $thousandSeparator = ''): string
    {
        $total = $this->getContent()->sum(fn (CartItem $item) => $item->total());

        return number_format($total, $decimals, $decimalPoint, $thousandSeparator);
    }

    public function search(Closure $closure): Collection
    {
        return $this->getContent()->filter($closure);
    }

    public function setGlobalTax(float $taxRate): void
    {
        $this->taxRate = $taxRate;

        $content = $this->getContent()->map(function (CartItem $item) use ($taxRate) {
            $item->taxRate = $taxRate;

            return $item;
        });

        $this->saveContent($content);
    }

    public function store(string $identifier): void
    {
        if (! $this->database) {
            return;
        }

        $connection = config('cart.database.connection');
        $table = config('cart.database.table', 'shopping_cart');

        $this->database->connection($connection)->table($table)->updateOrInsert(
            [
                'identifier' => $identifier,
                'instance' => $this->currentInstance(),
            ],
            [
                'content' => serialize($this->getContent()),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function restore(string $identifier): void
    {
        if (! $this->database) {
            return;
        }

        $connection = config('cart.database.connection');
        $table = config('cart.database.table', 'shopping_cart');

        $record = $this->database->connection($connection)->table($table)
            ->where('identifier', $identifier)
            ->where('instance', $this->currentInstance())
            ->first();

        if ($record) {
            $content = unserialize($record->content);
            if ($content instanceof Collection) {
                $this->saveContent($content);
            }
        }
    }

    protected function getSessionKey(): string
    {
        return 'cart.'.$this->instance;
    }

    protected function getContent(): Collection
    {
        $data = $this->session->get($this->getSessionKey(), []);

        if ($data instanceof Collection) {
            return $data;
        }

        return new Collection($data);
    }

    protected function saveContent(Collection $content): void
    {
        $this->session->put($this->getSessionKey(), $content);
    }
}
