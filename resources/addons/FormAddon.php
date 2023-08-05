<?php

declare(strict_types=1);

use jojoe77777\FormAPI\SimpleForm;
use phuongaz\addon\Addon;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

class FormAddon extends Addon implements Listener {

    public function __construct() {
        parent::__construct("FormAddon", "1.0.0", "RedCraftPE", "FormAddon for OpenSkyBlock");
    }

    public function execute(Plugin $plugin) : void {
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    public function onCommandEvent(CommandEvent $event) :void {
        $command = $event->getCommand();
        $commandSender = $event->getSender();
        if($command === "skyblock" && $commandSender instanceof Player) {
            $commandSender->sendMessage("Test command for FormAddon");
            $this->sendForm($commandSender);
        }
        $event->cancel();
    }

    public function sendForm(Player $player) :void {
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null) {
                return;
            }
            var_dump($data);
        });
        $form->setTitle("Test Form");
        $form->setContent("This is a test form");
        $form->addButton("Button 1");
        $form->addButton("Button 2");
        $form->addButton("Button 3");
        $player->sendForm($form);
    }

}