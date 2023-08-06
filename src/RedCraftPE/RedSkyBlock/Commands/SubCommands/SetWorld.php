<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\exception\ArgumentOrderException;
use JsonException;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\RawStringArgument;

class SetWorld extends SBSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
    $this->setPermission("redskyblock.admin;redskyblock.setworld");
    $this->registerArgument(0, new RawStringArgument("name", false));
  }

    /**
     * @throws JsonException
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      $plugin = $this->plugin;
      $name = $args["name"];

      if ($plugin->skyblock->get("Master World") !== $name) {

        if ($plugin->getServer()->getWorldManager()->loadWorld($name)) {

          $plugin->skyblock->set("Islands", 0); //reconfigure so if master world is changed back to previous skyblock world the amount of islands already created remains
          $plugin->skyblock->set("Master World", $name);
          $plugin->skyblock->save();

          $message = $this->getMShop()->construct("WORLD_SET");
        } else {

          $message = $this->getMShop()->construct("NO_WORLD");
        }
      } else {

        $message = $this->getMShop()->construct("NO_CHANGE");
      }
        $message = str_replace("{WORLD}", $name, $message);
        $sender->sendMessage($message);
    } else {

      $this->sendUsage();
    }
  }
}
