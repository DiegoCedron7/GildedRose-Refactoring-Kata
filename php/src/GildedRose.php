<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {

            $type = $this->typeOf($item);
            
            if ($type === 'sulfuras') {
                continue;
            }

            $degradeAmount = ($type === 'conjured') ? 2 : 1;

            $this->updateQualityBeforeSellDate($item, $type, $degradeAmount);

            $this->decreaseSellIn($item);

            if ($this->isExpired($item)) {
                $this->updateQualityAfterSellDate($item, $type, $degradeAmount);
            }
        }
    }

    private function updateQualityBeforeSellDate(Item $item, string $type, int $degradeAmount): void
    {
        switch ($type) {
            case 'brie':
                $this->increaseQuality($item, 1);
                return;

            case 'backstage':
                $this->increaseQuality($item, 1);

                if ($item->sellIn < 11) {
                    $this->increaseQuality($item, 1);
                }

                if ($item->sellIn < 6) {
                    $this->increaseQuality($item, 1);
                }

                return;

            case 'normal':
            case 'conjured':
            default:
                $this->decreaseQuality($item, $degradeAmount);
                return;
        }
    }

    private function updateQualityAfterSellDate(Item $item, string $type, int $degradeAmount): void
    {
        switch ($type) {
            case 'brie':
                $this->increaseQuality($item, 1);
                return;

            case 'backstage':
                $item->quality = 0;
                return;

            case 'normal':
            case 'conjured':
            default:
                $this->decreaseQuality($item, $degradeAmount);
                return;
        }
    }

    private function typeOf(Item $item): string
    {
        $typeMapping = [
            'Aged Brie' => 'brie',
            'Backstage passes to a TAFKAL80ETC concert' => 'backstage',
            'Sulfuras, Hand of Ragnaros' => 'sulfuras',
        ];

        if (isset($typeMapping[$item->name])) {
            return $typeMapping[$item->name];
        }

        if (strpos($item->name, 'Conjured') !== false) {
            return 'conjured';
        }

        return 'normal';
    }


    private function decreaseQuality(Item $item, int $amount): void
    {
        $item->quality = max(0, $item->quality - $amount);
    }

    private function increaseQuality(Item $item, int $amount): void
    {
        $item->quality = min(50, $item->quality + $amount);
    }

    private function isExpired(Item $item): bool
    {
        return $item->sellIn < 0;
    }
    private function decreaseSellIn(Item $item): void
    {
        $item->sellIn -= 1;
    }

}
