<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GildedRose\Application\GildedRose;
use GildedRose\Domain\Item;
use GildedRose\Update\Updaters\AgedBrieUpdater;
use GildedRose\Update\Updaters\BackstageUpdater;
use GildedRose\Update\Updaters\ConjuredUpdater;
use GildedRose\Update\Updaters\NormalUpdater;
use GildedRose\Update\Updaters\SulfurasUpdater;
use GildedRose\Update\UpdaterResolver;


echo 'OMGHAI!' . PHP_EOL;

$items = [
    new Item('+5 Dexterity Vest', 10, 20),
    new Item('Aged Brie', 2, 0),
    new Item('Elixir of the Mongoose', 5, 7),
    new Item('Sulfuras, Hand of Ragnaros', 0, 80),
    new Item('Sulfuras, Hand of Ragnaros', -1, 80),
    new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
    new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
    new Item('Conjured Mana Cake', 3, 6),
];

$resolver = new UpdaterResolver([
    new SulfurasUpdater(),
    new AgedBrieUpdater(),
    new BackstageUpdater(),
    new ConjuredUpdater(),
]);

$app = new GildedRose($items, $resolver);

$days = 2;
if ((is_countable($argv) ? count($argv) : 0) > 1) {
    $days = (int) $argv[1];
}

for ($i = 0; $i < $days; $i++) {
    echo "-------- day {$i} --------" . PHP_EOL;
    echo 'name, sellIn, quality' . PHP_EOL;
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
    echo PHP_EOL;
    $app->updateQuality();
}
