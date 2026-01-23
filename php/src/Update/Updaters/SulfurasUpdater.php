<?php

declare(strict_types=1);

namespace GildedRose\Update\Updaters;
use GildedRose\Update\ItemUpdater;

use GildedRose\Domain\Item;

final class SulfurasUpdater implements ItemUpdater
{
    public function supports(Item $item): bool
    {
        return $item->name === 'Sulfuras, Hand of Ragnaros';
    }

    public function update(Item $item): void
    {
        // no op
    }
}
