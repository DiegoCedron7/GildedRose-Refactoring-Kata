<?php

declare(strict_types=1);

namespace GildedRose\Update;
use GildedRose\Domain\Item;
use GildedRose\Update\Updaters\NormalUpdater;



final class UpdaterResolver
{
    /** @param ItemUpdater[] $updaters */
    public function __construct(private array $updaters)
    {
    }

    public function resolve(Item $item): ItemUpdater
    {
        foreach ($this->updaters as $updater) {
            if ($updater->supports($item)) {
                return $updater;
            }
        }

        return new NormalUpdater();
    }
}
