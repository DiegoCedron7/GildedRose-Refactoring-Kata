<?php

declare(strict_types=1);

namespace GildedRose\Update;

use GildedRose\Domain\Item;

trait QualityHelpers
{

    private function decreaseQuality(Item $item, int $amount): void
    {

        $item->quality = max(0, $item->quality - $amount);
    }

    private function increaseQuality(Item $item, int $amount): void
    {
        $item->quality = min(50, $item->quality + $amount);
    }

    private function decreaseSellIn(Item $item): void
    {
        $item->sellIn -= 1;
    }

    private function isExpired(Item $item): bool
    {
        return $item->sellIn < 0;
    }
}
