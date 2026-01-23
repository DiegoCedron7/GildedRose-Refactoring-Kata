<?php

declare(strict_types=1);

namespace GildedRose\Update;

use GildedRose\Domain\Item;

interface ItemUpdater
{
    public function supports(Item $item): bool;

    public function update(Item $item): void;
}
