<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class OnIsland extends SBSubCommand
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
        if ($this->checkIsland($sender)) {

            $island = $this->plugin->islandManager->getIsland($sender);
            $playersOnIsland = $this->plugin->islandManager->getPlayersAtIsland($island);
            $playersOnIsland = implode(", ", $playersOnIsland);

            $message = $this->getMShop()->construct("PLAYERS_ON_ISLAND");
            $message = str_replace("{PLAYERS}", $playersOnIsland, $message);
        } else {

            $message = $this->getMShop()->construct("NO_ISLAND");
        }
        $sender->sendMessage($message);
    }
}
