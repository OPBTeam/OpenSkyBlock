<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\block\BlockTypeIds;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\block\BlockLegacyMetadata;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;

use RedCraftPE\RedSkyBlock\Commands\SBCommand;
use RedCraftPE\RedSkyBlock\Utils\MessageConstructor;
use RedCraftPE\RedSkyBlock\Utils\ConfigManager;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;
use RedCraftPE\RedSkyBlock\Utils\IslandManager;
use RedCraftPE\RedSkyBlock\Tasks\AutoSaveIslands;

class SkyBlock extends PluginBase
{
    use SingletonTrait;

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
        //database setup:
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock")) {

            mkdir($this->getDataFolder() . "../RedSkyBlock");
        }
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock/skyblock.json")) {

            $this->saveResource("skyblock.json");
        }
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock/config.yml")) {

            $this->saveResource("config.yml");
        }
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock/messages.yml")) {

            $this->saveResource("messages.yml");
        }
        if (!file_exists($this->getDataFolder() . "../RedSkyBlock/Players")) {

            mkdir($this->getDataFolder() . "../RedSkyBlock/Players");
        }

        $this->skyblock = new Config($this->getDataFolder() . "../RedSkyBlock/skyblock.json", Config::JSON);
        $this->cfg = new Config($this->getDataFolder() . "../RedSkyBlock/config.yml", Config::YAML);
        $this->messages = new Config($this->getDataFolder() . "../RedSkyBlock/messages.yml", Config::YAML);
        $this->skyblock->reload();
        $this->cfg->reload();
        $this->messages->reload();

        //register config manager:
        $this->configManager = new ConfigManager($this);
        //register zone manager:
        $this->zoneManager = new ZoneManager($this);
        //register island manager:
        $this->islandManager = new IslandManager($this);
        $this->islandManager->constructAllIslands();
        //register message constructor:
        $this->mShop = new MessageConstructor($this);
        //register listener for RedSkyBlock:
        $this->listener = new SkyblockListener($this);

        //begin autosave
        $autosaveTimer = $this->cfg->get("Autosave Timer");
        $ticks = round($autosaveTimer * 1200); //converts minutes to ticks
        $this->getScheduler()->scheduleRepeatingTask(new AutoSaveIslands($this), $ticks);

        $this->getServer()->getCommandMap()->register("OpenSkyBlock", new SBCommand($this, "skyblock", "Open SkyBlock Command", ["sb"]));

        //Determine if a skyblock world is being used: -- from older RedSkyBlock will probably be udpated

        if ($this->skyblock->get("Master World") === false) {
            $message = $this->mShop->construct("NO_MASTER");
            $this->getLogger()->info($message);
        } else {

            if ($this->getServer()->getWorldManager()->loadWorld($this->skyblock->get("Master World"))) {

                $this->getServer()->getWorldManager()->loadWorld($this->skyblock->get("Master World"));
                if ($this->cfg->get("Nether Islands")) {

                    $this->getServer()->getWorldManager()->loadWorld($this->skyblock->get("Master World") . "-Nether");
                }
            } else {

                $message = $this->mShop->construct("LOAD_ERROR");
                $this->getLogger()->info($message);
            }

            $masterWorld = $this->getServer()->getWorldManager()->getWorldByName($this->skyblock->get("Master World"));
            if (!$masterWorld instanceof World) {

                $message = $this->mShop->construct("MASTER_FAILED");
                $message = str_replace("{MWORLD}", $this->skyblock->get("Master World"), $message);
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
