<?php

declare(strict_types=1);

namespace GildedRose\Update\Updaters;
use GildedRose\Update\QualityHelpers;
use GildedRose\Update\ItemUpdater;
use GildedRose\Domain\Item;

final class BackstageUpdater implements ItemUpdater
{
    use QualityHelpers;

    public function supports(Item $item): bool
    {
        return $item->name === 'Backstage passes to a TAFKAL80ETC concert';
    }

    public function update(Item $item): void
    {
        $this->increaseQuality($item, 1);

        if ($item->sellIn < 11) {
            $this->increaseQuality($item, 1);
        }

        if ($item->sellIn < 6) {
            $this->increaseQuality($item, 1);
        }

        $this->decreaseSellIn($item);

        if ($this->isExpired($item)) {
            $item->quality = 0;
        }
    }
}
