<?php

declare(strict_types=1);

namespace GildedRose\Update\Updaters;
use GildedRose\Update\ItemUpdater;
use GildedRose\Update\QualityHelpers;

use GildedRose\Domain\Item;

final class NormalUpdater implements ItemUpdater
{
    use QualityHelpers;

    public function supports(Item $item): bool
    {
        return true;
    }

    public function update(Item $item): void
    {
        $this->decreaseQuality($item, 1);
        $this->decreaseSellIn($item);

        if ($this->isExpired($item)) {
            $this->decreaseQuality($item, 1);
        }
    }
}
