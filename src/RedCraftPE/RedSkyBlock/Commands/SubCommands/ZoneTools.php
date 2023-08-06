<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class ZoneTools extends SBSubCommand
{

    private Item $zoneShovel;
    private Item $spawnFeather;

    public function prepare(): void
    {

        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->setPermission("redskyblock.admin;redskyblock.zone");
        $this->zoneShovel = ZoneManager::getZoneShovel();
        $this->spawnFeather = ZoneManager::getSpawnFeather();
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use command in game");
            return;
        }
        $zoneKeeper = ZoneManager::getZoneKeeper();
        $senderInv = $sender->getInventory();
        $zoneShovel = clone $this->zoneShovel;
        $spawnFeather = clone $this->spawnFeather;

        if ($zoneKeeper !== $sender) {

            if ($zoneKeeper == null) {

                ZoneManager::clearZoneTools($sender);
                $senderInv->addItem($zoneShovel);
                $senderInv->addItem($spawnFeather);
                ZoneManager::setZoneKeeper($sender);
                ZoneManager::setSpawnPosition();
                ZoneManager::setFirstPosition();
                ZoneManager::setSecondPosition();
            } else {

                ZoneManager::clearZoneTools($zoneKeeper);
                ZoneManager::setZoneKeeper($sender);
                ZoneManager::setSpawnPosition();
                ZoneManager::setFirstPosition();
                ZoneManager::setSecondPosition();
                $senderInv->addItem($zoneShovel);
                $senderInv->addItem($spawnFeather);
            }
        } elseif (!$senderInv->contains($zoneShovel) || !$senderInv->contains($spawnFeather)) {
            ZoneManager::clearZoneTools($sender);
            $senderInv->addItem($zoneShovel);
            $senderInv->addItem($spawnFeather);
        }
    }
}
