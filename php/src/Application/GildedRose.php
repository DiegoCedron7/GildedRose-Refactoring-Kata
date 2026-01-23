<?php

declare(strict_types=1);

namespace GildedRose\Application;
use GildedRose\Domain\Item;
use GildedRose\Update\UpdaterResolver;


final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items,
        private ?UpdaterResolver $resolver,
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->resolver->resolve($item)->update($item);
        }
    }
}
