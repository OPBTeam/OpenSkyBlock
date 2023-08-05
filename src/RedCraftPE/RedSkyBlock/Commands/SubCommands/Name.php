<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Name extends SBSubCommand
{

    public function prepare(): void
    {

        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->setPermission("redskyblock.island");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use command in game");
            return;
        }
        $island = $this->plugin->islandManager->getIslandAtPlayer($sender);
        if ($island instanceof Island) {

            $islandName = $island->getName();

            $message = $this->getMShop()->construct("ISLAND_NAME");
            $message = str_replace("{ISLAND_NAME}", $islandName, $message);
            $sender->sendTip($message);
        } elseif ($this->checkIsland($sender)) {

            $island = $this->plugin->islandManager->getIsland($sender);
            $islandName = $island->getName();

            $message = $this->getMShop()->construct("ISLAND_NAME");
            $message = str_replace("{ISLAND_NAME}", $islandName, $message);
            $sender->sendTip($message);
        } else {

            $message = $this->getMShop()->construct("NO_ISLAND");
            $sender->sendMessage($message);
        }
    }
}
