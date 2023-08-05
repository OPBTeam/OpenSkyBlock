<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use CortexPE\Commando\args\TextArgument;

class Promote extends SBSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {

        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->setPermission("redskyblock.island");
        $this->registerArgument(0, new TextArgument("player", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use command in game");
            return;
        }
        if ($this->checkIsland($sender)) {

            $playerName = strtolower($args["player"]);
            $island = $this->plugin->islandManager->getIsland($sender);
            $members = $island->getMembers();
            if (array_key_exists($playerName, $members)) {

                $currentRank = $members[$playerName];
                $possibleRanks = Island::MEMBER_RANKS;
                $highestRank = end($possibleRanks);
                if ($currentRank !== $highestRank) {

                    $index = array_search($currentRank, $possibleRanks);
                    $newRank = $possibleRanks[$index + 1];
                    $island->setRank($playerName, $newRank);

                    $message = $this->getMShop()->construct("PROMOTED_OTHER");
                    $message = str_replace("{RANK}", ucfirst($newRank), $message);
                    $message = str_replace("{NAME}", $playerName, $message);
                    $sender->sendMessage($message);

                    $player = $this->plugin->getServer()->getPlayerExact($playerName);
                    if ($player instanceof Player) {

                        $message = $this->getMShop()->construct("PROMOTED_SELF");
                        $message = str_replace("{RANK}", ucfirst($newRank), $message);
                        $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
                        $player->sendMessage($message);
                    }
                } else {

                    $message = $this->getMShop()->construct("CANT_PROMOTE");
                    $message = str_replace("{NAME}", $args["player"], $message);
                    $sender->sendMessage($message);
                }
            } else {

                $message = $this->getMShop()->construct("NOT_A_MEMBER_OTHER");
                $message = str_replace("{NAME}", $args["player"], $message);
                $sender->sendMessage($message);
            }
        } else {

            $message = $this->getMShop()->construct("NO_ISLAND");
            $sender->sendMessage($message);
        }
    }
}
