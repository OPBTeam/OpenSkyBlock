<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Rename extends SBSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {

        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->setPermission("redskyblock.island");
        $this->registerArgument(0, new TextArgument("name", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use command in game");
            return;
        }
        $name = $args["name"];
        $island = $this->plugin->islandManager->getIslandAtPlayer($sender);
        if (!($island instanceof Island)) {

            if ($this->checkIsland($sender)) {

                $island = $this->plugin->islandManager->getIsland($sender);

            } else {

                $message = $this->getMShop()->construct("NO_ISLAND");
                $sender->sendMessage($message);
                return;
            }
        }

        $members = $island->getMembers();
        if (array_key_exists(strtolower($sender->getName()), $members) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblock.admin")) {

            if (array_key_exists(strtolower($sender->getName()), $members) && !$sender->hasPermission("redskyblock.admin")) {

                $islandPermissions = $island->getPermissions();
                $senderRank = $members[strtolower($sender->getName())];

                if (in_array("island.name", $islandPermissions[$senderRank])) {

                    if (!$this->plugin->islandManager->checkRepeatIslandName($name)) {

                        $island->setName($name);

                        $message = $this->getMShop()->construct("NAME_CHANGE");
                        $message = str_replace("{NAME}", $name, $message);
                    } else {

                        $message = $this->getMShop()->construct("ISLAND_NAME_EXISTS");
                        $message = str_replace("{ISLAND_NAME}", $name, $message);
                    }
                } else {

                    $message = $this->getMShop()->construct("RANK_TOO_LOW");
                    $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
                }
                $sender->sendMessage($message);
            } else {

                if (!$this->plugin->islandManager->checkRepeatIslandName($name)) {

                    $island->setName($name);

                    $message = $this->getMShop()->construct("NAME_CHANGE");
                    $message = str_replace("{NAME}", $name, $message);
                } else {

                    $message = $this->getMShop()->construct("ISLAND_NAME_EXISTS");
                    $message = str_replace("{ISLAND_NAME}", $name, $message);
                }
                $sender->sendMessage($message);
            }
        } else {

            $message = $this->getMShop()->construct("NOT_A_MEMBER_SELF");
            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
            $sender->sendMessage($message);
        }
    }
}
