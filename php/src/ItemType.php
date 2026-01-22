<?php

declare(strict_types=1);

namespace GildedRose;

enum ItemType: string
{
    case Brie = 'brie';
    case Backstage = 'backstage';
    case Sulfuras = 'sulfuras';
    case Normal = 'normal';
    case Conjured = 'conjured';
}
