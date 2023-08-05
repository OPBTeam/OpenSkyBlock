<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlock\trait;

use phuongaz\addon\Addon;
use phuongaz\addon\PluginAddon;
use pocketmine\plugin\Plugin;

trait AddonTrait {

    private static Plugin $plugin;
    private static PluginAddon $addon;

    public static function initAddon(Plugin $plugin, string $path) :void {
        self::$plugin = $plugin;
        self::$addon = new PluginAddon($plugin, $path);
        self::$addon->loadAddons();
    }

    public static function getAddonByName(string $name) :?Addon {
        return self::$addon->getAddonByName($name);
    }

    public static function getAddons() :array {
        return self::$addon->getAddons();
    }
}