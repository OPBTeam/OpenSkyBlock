<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use RedCraftPE\RedSkyBlock\Commands\SBCommand;
use RedCraftPE\RedSkyBlock\Tasks\AutoSaveIslands;
use RedCraftPE\RedSkyBlock\trait\AddonTrait;
use RedCraftPE\RedSkyBlock\trait\LoggerTrait;
use RedCraftPE\RedSkyBlock\Utils\ConfigManager;
use RedCraftPE\RedSkyBlock\Utils\IslandManager;
use RedCraftPE\RedSkyBlock\Utils\MessageConstructor;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

class SkyBlock extends PluginBase
{
    use SingletonTrait;
    use LoggerTrait;
    use AddonTrait;

    public SkyblockListener $listener;
    public MessageConstructor $mShop;
    public Config $cfg;
    public Config $skyblock;
    public Config $messages;
    public ZoneManager $zoneManager;
    public ConfigManager $configManager;
    public IslandManager $islandManager;

    public function onLoad(): void
    {
        self::setInstance($this);
    }

    public function onEnable(): void
    {
        self::initLogger();
        $this->saveResource("addons\\FormAddon.php");
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock")) {
            mkdir($this->getDataFolder() . "../RedSkyBlock");
        }
        $defaultFiles = [
            "skyblock.json",
            "config.yml",
            "messages.yml",
        ];

        foreach ($defaultFiles as $file) {
            $filePath = $this->getDataFolder() . "../RedSkyBlock/" . $file;
            if (!file_exists($filePath)) {
                $this->saveResource($file, false); // The second parameter is to overwrite if the file already exists
            }
        }
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock/Players")) {
            mkdir($this->getDataFolder() . "../RedSkyBlock/Players");
        }

        if (!file_exists($this->getDataFolder() . "addons")) {
            mkdir($this->getDataFolder() . "addons");
        }

        self::initAddon($this, $this->getDataFolder() . "addons");

        $this->skyblock = new Config($this->getDataFolder() . "../RedSkyBlock/skyblock.json", Config::JSON);
        $this->cfg = new Config($this->getDataFolder() . "../RedSkyBlock/config.yml", Config::YAML);
        $this->messages = new Config($this->getDataFolder() . "../RedSkyBlock/messages.yml", Config::YAML);
        $this->skyblock->reload();
        $this->cfg->reload();
        $this->messages->reload();

        $this->configManager = new ConfigManager($this);
        $this->zoneManager = new ZoneManager($this);
        $this->islandManager = new IslandManager($this);
        $this->islandManager->constructAllIslands();
        $this->mShop = new MessageConstructor($this);
        $this->listener = new SkyblockListener($this);

        //begin autosave
        $autosaveTimer = $this->cfg->get("Autosave Timer");
        $ticks = round($autosaveTimer * 1200); //converts minutes to ticks
        $this->getScheduler()->scheduleRepeatingTask(new AutoSaveIslands($this), $ticks);

        $this->getServer()->getCommandMap()->register("OpenSkyBlock", new SBCommand($this, "skyblock", "Open SkyBlock Command", ["sb"]));

        if ($this->skyblock->get("Master World") === false) {
            $message = $this->mShop->construct("NO_MASTER");
            $this->getLogger()->info($message);
        } else {
            $masterWorldName = $this->skyblock->get("Master World");
            if ($this->getServer()->getWorldManager()->loadWorld($masterWorldName)) {
                $this->getServer()->getWorldManager()->loadWorld($masterWorldName);
                if ($this->cfg->get("Nether Islands")) {
                    $netherWorldName = $masterWorldName . "-Nether";
                    $this->getServer()->getWorldManager()->loadWorld($netherWorldName);
                }
            } else {
                $message = $this->mShop->construct("LOAD_ERROR");
                $this->getLogger()->info($message);
            }
            $masterWorld = $this->getServer()->getWorldManager()->getWorldByName($masterWorldName);
            if (!$masterWorld instanceof World) {
                $message = $this->mShop->construct("MASTER_FAILED");
                $message = str_replace("{MWORLD}", $masterWorldName, $message);
                $this->getLogger()->info($message);
                $masterWorld = null;
            } else {
                $message = $this->mShop->construct("MASTER_SUCCESS");
                $message = str_replace("{MWORLD}", $masterWorld->getFolderName(), $message);
                $this->getLogger()->info($message);
            }
        }
    }

    public function onDisable(): void
    {
        IslandManager::getInstance()->saveAllIslands();
    }

}
