<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class AddPermission extends SBSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {

        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->setPermission("redskyblock.island");
        $this->registerArgument(0, new RawStringArgument("rank", false));
        $this->registerArgument(1, new RawStringArgument("permission", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use command in game");
            return;
        }
        $rank = strtolower($args["rank"]);
        $permission = strtolower($args["permission"]);

        if ($this->checkIsland($sender)) {

            $island = $this->plugin->islandManager->getIsland($sender);
            if ($island->addPermission($rank, $permission)) {

                $message = $this->getMShop()->construct("PERMISSION_ADDED");
                $message = str_replace("{PERMISSION}", $permission, $message);
                $message = str_replace("{RANK}", ucfirst($rank), $message);
                $sender->sendMessage($message);
            } else {

                $message = $this->getMShop()->construct("PERMISSION_NOT_ADDED");
                $message = str_replace("{PERMISSION}", $permission, $message);
                $message = str_replace("{RANK}", $rank, $message);
                $sender->sendMessage($message);
            }
        } else {

            $message = $this->getMShop()->construct("NO_ISLAND");
            $sender->sendMessage($message);
        }
    }
}
