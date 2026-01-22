<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

final class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
    }

    public function testNewItem(): void
    {
        $items = [new Item('newItem', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('newItem', $items[0]->name);
    }

    private function updateOneItem(string $name, int $sellIn, int $quality): Item
    {
        $items = [new Item($name, $sellIn, $quality)];
        $app = new GildedRose($items);
        $app->updateQuality();
        return $items[0];
    }

    public function testNormalItemDecreasesSellInAndQualityByOne(): void
    {
        $item = $this->updateOneItem('Elixir of the Mongoose', 5, 7);
        $this->assertSame(4, $item->sellIn);
        $this->assertSame(6, $item->quality);
    }

    public function testNormalItemDegradesTwiceAsFastAfterSellDate(): void
    {
        $item = $this->updateOneItem('+5 Dexterity Vest', 0, 10);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(8, $item->quality);
    }


    public function testNormalItemQualityNeverNegative(): void
    {
        $item = $this->updateOneItem('Some Normal Item', 0, 1);
        $this->assertSame(-1, $item->sellIn);

        $this->assertSame(0, $item->quality);
    }

    public function testNormalItemDegradesTwiceAfterSellInIsZero(): void
    {
        $item = $this->updateOneItem('Normal Item', 0, 10);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(8, $item->quality);
    }

    public function testNormalItemUpdatesCorrectlyOverMultipleDays(): void
    {
        $item = $this->updateOneItem('Normal Item', 5, 10);
        $this->assertSame(4, $item->sellIn);
        $this->assertSame(9, $item->quality);

        $item = $this->updateOneItem('Normal Item', $item->sellIn, $item->quality);
        $this->assertSame(3, $item->sellIn);
        $this->assertSame(8, $item->quality);

        $item = $this->updateOneItem('Normal Item', $item->sellIn, $item->quality);
        $this->assertSame(2, $item->sellIn);
        $this->assertSame(7, $item->quality);
    }



    public function testAgedBrieIncreasesInQuality(): void
    {
        $item = $this->updateOneItem('Aged Brie', 2, 0);
        $this->assertSame(1, $item->sellIn);
        $this->assertSame(1, $item->quality);
    }


    public function testAgedBrieIncreasesTwiceAsFastAfterSellDate(): void
    {
        $item = $this->updateOneItem('Aged Brie', 0, 2);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(4, $item->quality);
    }

    public function testAgedBrieQualityNeverMoreThanFifty(): void
    {
        $item = $this->updateOneItem('Aged Brie', 10, 50);
        $this->assertSame(9, $item->sellIn);
        $this->assertSame(50, $item->quality);
    }

    public function testAgedBrieQualityNeverMoreThanFiftyWhenIncreasing(): void
    {
        $item = $this->updateOneItem('Aged Brie', 2, 50);
        $this->assertSame(1, $item->sellIn);
        $this->assertSame(50, $item->quality);

        $item = $this->updateOneItem('Aged Brie', $item->sellIn, $item->quality);
        $this->assertSame(0, $item->sellIn);
        $this->assertSame(50, $item->quality);
    }


    public function testBackstagePassesIncreaseByTwoWhenTenDaysOrLess(): void
    {
        $item = $this->updateOneItem('Backstage passes to a TAFKAL80ETC concert', 10, 20);
        $this->assertSame(9, $item->sellIn);
        $this->assertSame(22, $item->quality);
    }

    public function testBackstagePassesIncreaseByThreeWhenFiveDaysOrLess(): void
    {
        $item = $this->updateOneItem('Backstage passes to a TAFKAL80ETC concert', 5, 20);
        $this->assertSame(4, $item->sellIn);
        $this->assertSame(23, $item->quality);
    }

    public function testBackstagePassesDropToZeroAfterConcert(): void
    {
        $item = $this->updateOneItem('Backstage passes to a TAFKAL80ETC concert', 0, 20);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(0, $item->quality);
    }

    public function testBackstagePassesQualityNeverMoreThanFifty(): void
    {
        $item = $this->updateOneItem('Backstage passes to a TAFKAL80ETC concert', 5, 50);
        $this->assertSame(4, $item->sellIn);
        $this->assertSame(50, $item->quality);

        $item = $this->updateOneItem('Backstage passes to a TAFKAL80ETC concert', $item->sellIn, $item->quality);
        $this->assertSame(3, $item->sellIn);
        $this->assertSame(50, $item->quality);
    }

    public function testSulfurasNeverChanges(): void
    {
        $item = $this->updateOneItem('Sulfuras, Hand of Ragnaros', 0, 80);
        $this->assertSame(0, $item->sellIn);
        $this->assertSame(80, $item->quality);
    }
    public function testSulfurasNeverChangesInSellInOrQuality(): void
    {
        $item = $this->updateOneItem('Sulfuras, Hand of Ragnaros', 10, 80);
        $this->assertSame(10, $item->sellIn);
        $this->assertSame(80, $item->quality);

        $item = $this->updateOneItem('Sulfuras, Hand of Ragnaros', $item->sellIn, $item->quality);
        $this->assertSame(10, $item->sellIn);
        $this->assertSame(80, $item->quality);
    }


    public function testConjuredItemDecreasesQualityTwiceAsFastBeforeSellDate(): void
    {
        $item = $this->updateOneItem('Conjured Mana Cake', 3, 6);
        $this->assertSame(2, $item->sellIn);
        $this->assertSame(4, $item->quality);
    }

    public function testConjuredItemDecreasesQualityFourAfterSellDate(): void
    {
        $item = $this->updateOneItem('Conjured Mana Cake', 0, 6);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(2, $item->quality);
    }


    public function testConjuredItemQualityNeverNegative(): void
    {
        $item = $this->updateOneItem('Conjured Mana Cake', 0, 3);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(0, $item->quality);
    }

    public function testConjuredItemQualityNeverNegativeAfterExpiration(): void
    {
        $item = $this->updateOneItem('Conjured Mana Cake', 0, 1);
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(0, $item->quality);
    }
    public function testMixedInventoryUpdatesCorrectly(): void
    {
        $items = [
            new Item('Aged Brie', 2, 0),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Conjured Mana Cake', 3, 6),
            new Item('Normal Item', 5, 10),
        ];

        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();

        $this->assertSame(1, $items[0]->sellIn);
        $this->assertSame(1, $items[0]->quality);

        $this->assertSame(14, $items[1]->sellIn);
        $this->assertSame(21, $items[1]->quality);

        $this->assertSame(0, $items[2]->sellIn);
        $this->assertSame(80, $items[2]->quality);

        $this->assertSame(2, $items[3]->sellIn);
        $this->assertSame(4, $items[3]->quality);

        $this->assertSame(4, $items[4]->sellIn);
        $this->assertSame(9, $items[4]->quality);
    }

}
