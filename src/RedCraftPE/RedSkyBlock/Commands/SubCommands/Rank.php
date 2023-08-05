<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Rank extends SBSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {

        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->setPermission("redskyblock.island");
        $this->registerArgument(0, new TextArgument("island", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use command in game");
            return;
        }
        $islandCount = count($this->plugin->islandManager->getIslands());

        if (isset($args["island"])) {

            $islandName = $args["island"];
            $island = $this->plugin->islandManager->getIslandByName($islandName);
            if ($island instanceof Island) {

                $rank = $this->plugin->islandManager->getIslandRank($island);

                $message = $this->getMShop()->construct("ISLAND_RANK_OTHER");
                $message = str_replace("{NAME}", $islandName, $message);
                $message = str_replace("{RANK}", $rank, $message);
                $message = str_replace("{TOTAL_ISLANDS}", $islandCount, $message);
                $sender->sendMessage($message);
            } else {

                $message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
                $message = str_replace("{ISLAND_NAME}", $islandName, $message);
                $sender->sendMessage($message);
            }
        } else {

            if ($this->checkIsland($sender)) {

                $island = $this->plugin->islandManager->getIsland($sender);
                $rank = $this->plugin->islandManager->getIslandrank($island);

                $message = $this->getMShop()->construct("ISLAND_RANK_SELF");
                $message = str_replace("{RANK}", $rank, $message);
                $message = str_replace("{TOTAL_ISLANDS}", $islandCount, $message);
                $sender->sendMessage($message);
            } else {

                $message = $this->getMShop()->construct("NO_ISLAND");
                $sender->sendMessage($message);
            }
        }
    }
}
