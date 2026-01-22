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

            if ($type === ItemType::Sulfuras) {
                continue;
            }

            $degradeAmount = ($type === ItemType::Conjured) ? 2 : 1;

            $this->updateQualityBeforeSellDate($item, $type, $degradeAmount);

            $this->decreaseSellIn($item);

            if ($this->isExpired($item)) {
                $this->updateQualityAfterSellDate($item, $type, $degradeAmount);
            }
        }
    }

    private function updateQualityBeforeSellDate(Item $item, ItemType $type, int $degradeAmount): void
    {
        switch ($type) {
            case ItemType::Brie:
                $this->increaseQuality($item, 1);
                return;

            case ItemType::Backstage:
                $this->increaseQuality($item, 1);

                if ($item->sellIn < 11) {
                    $this->increaseQuality($item, 1);
                }

                if ($item->sellIn < 6) {
                    $this->increaseQuality($item, 1);
                }

                return;

            case ItemType::Normal:
            case ItemType::Conjured:
            default:
                $this->decreaseQuality($item, $degradeAmount);
                return;
        }
    }

    private function updateQualityAfterSellDate(Item $item, ItemType $type, int $degradeAmount): void
    {
        switch ($type) {
            case ItemType::Brie:
                $this->increaseQuality($item, 1);
                return;

            case ItemType::Backstage:
                $item->quality = 0;
                return;

            case ItemType::Normal:
            case ItemType::Conjured:
            default:
                $this->decreaseQuality($item, $degradeAmount);
                return;
        }
    }

    private function typeOf(Item $item): ItemType
    {
        $typeMapping = [
            'Aged Brie' => ItemType::Brie,
            'Backstage passes to a TAFKAL80ETC concert' => ItemType::Backstage,
            'Sulfuras, Hand of Ragnaros' => ItemType::Sulfuras,
        ];

        if (isset($typeMapping[$item->name])) {
            return $typeMapping[$item->name];
        }

        if (strpos($item->name, 'Conjured') !== false) {
            return ItemType::Conjured;
        }

        return ItemType::Normal;
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
