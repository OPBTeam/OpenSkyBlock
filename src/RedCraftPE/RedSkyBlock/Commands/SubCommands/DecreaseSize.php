<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TextArgument;

class DecreaseSize extends SBSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {

        $this->setPermission("redskyblock.admin");
        $this->registerArgument(0, new IntegerArgument("amount", false));
        $this->registerArgument(1, new TextArgument("name", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

        $playerName = $args["name"];
        $subAmount = $args["amount"];
        $island = $this->plugin->islandManager->getIslandByCreatorName($playerName);
        if ($island instanceof Island) {

            $newSize = $island->getSize() - $subAmount;
            if ($newSize < 0) $newSize = 0;

            $island->setSize($newSize);

            $message = $this->getMShop()->construct("PLAYER_ISLAND_SIZE_CHANGE");
            $message = str_replace("{NAME}", $island->getCreator(), $message);
            $message = str_replace("{SIZE}", $newSize, $message);
            $sender->sendMessage($message);

            $player = $this->plugin->getServer()->getPlayerExact($playerName);
            if ($player instanceof Player) {

                $message = $this->getMShop()->construct("ISLAND_SIZE_CHANGED");
                $message = str_replace("{SIZE}", $newSize, $message);
                $player->sendMessage($message);
            }
        } else {

            $message = $this->getMShop()->construct("PLAYER_HAS_NO_ISLAND");
            $message = str_replace("{NAME}", $playerName, $message);
            $sender->sendMessage($message);
        }
    }
}
