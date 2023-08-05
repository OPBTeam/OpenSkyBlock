<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\world\WorldCreationOptions;
use pocketmine\world\generator\GeneratorManager;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\RawStringArgument;

class CreateWorld extends SBSubCommand
{

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {

        $this->setPermission("redskyblock.admin;redskyblock.createworld");
        $this->registerArgument(0, new RawStringArgument("name", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

        if (isset($args["name"])) {

            $name = $args["name"];
            $plugin = $this->plugin;

            if (!$plugin->getServer()->getWorldManager()->loadWorld($name)) {

                $generator = GeneratorManager::getInstance()->getGenerator("flat")->getGeneratorClass();
                $worldCreator = WorldCreationOptions::create()->setGeneratorOptions("3;minecraft:air");
                $worldCreator->setGeneratorClass($generator);

                $plugin->getServer()->getWorldManager()->generateWorld($name, $worldCreator);

                $message = $this->getMShop()->construct("CW");

            } else {

                $message = $this->getMShop()->construct("CW_EXISTS");
            }
            $message = str_replace("{WORLD}", $name, $message);
            $sender->sendMessage($message);
        } else {
            $this->sendUsage();
        }
    }
}
