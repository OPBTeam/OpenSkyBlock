<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use RedCraftPE\RedSkyBlock\Utils\LoggerTrait;

class Remove extends SBSubCommand
{
    use LoggerTrait;

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
        if (isset($args["name"])) {

            if ($this->checkIsland($sender)) {

                $name = $args["name"];
                $island = $this->plugin->islandManager->getIsland($sender);
                $creator = $island->getCreator();

                if (strtolower($name) !== strtolower($creator)) {

                    if ($island->removeMember($name)) {

                        $message = $this->getMShop()->construct("MEMBER_REMOVED");
                        $message = str_replace("{NAME}", $name, $message);
                        $sender->sendMessage($message);

                        $player = $this->plugin->getServer()->getPlayerExact($name);
                        if ($player instanceof Player) {

                            $message = $this->getMShop()->construct("REMOVED_FROM_ISLAND");
                            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
                            $player->sendMessage($message);
                            self::logSub("member", "Player " . $player->getName() . " has been removed from " . $island->getName());
                        }
                    } else {

                        $message = $this->getMShop()->construct("NOT_A_MEMBER_OTHER");
                        $message = str_replace("{NAME}", $name, $message);
                        $sender->sendMessage($message);
                    }
                } else {

                    $message = $this->getMShop()->construct("CANT_REMOVE_SELF");
                    $sender->sendMessage($message);
                }
            } else {

                $message = $this->getMShop()->construct("NO_ISLAND");
                $sender->sendMessage($message);
            }
        } else {
            $this->sendUsage();
        }
    }
}
