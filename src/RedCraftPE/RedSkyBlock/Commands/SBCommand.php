<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Accept;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\AddPermission;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Ban;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Chat;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Create;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\CreateWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\DecreaseSize;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Delete;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Demote;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Fly;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Help;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\IncreaseSize;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Info;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Invite;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Kick;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Leave;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Level;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Lock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Name;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\OnIsland;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Promote;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Rank;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Reload;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Remove;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\RemovePermission;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Rename;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Reset;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetSize;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetSpawn;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Setting;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Teleport;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\TopIslands;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Unlock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\UpdateZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Value;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Visit;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\ZoneTools;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;

class SBCommand extends BaseCommand
{

    protected SkyBlock $plugin;

    private string $permission = "redskyblock.command";

    public function __construct(SkyBlock $plugin, string $name, string $description = "", array $aliases = [])
    {
        $this->plugin = $plugin;
        $this->setPermission($this->permission);
        parent::__construct($plugin, $name, $description, $aliases);
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {

        $this->registerArgument(0, new RawStringArgument("help", true));

        $this->registerSubCommand(new Accept(
            $this->plugin,
            "accept",
            "Accept an invite to another SkyBlock Island."
        ));

        $this->registerSubCommand(new AddPermission(
            $this->plugin,
            "addpermission",
            "Add a permission to an island rank on your SkyBlock Island.",
            ["addperm", "setpermission", "setperm"]
        ));

        $this->registerSubCommand(new Ban(
            $this->plugin,
            "ban",
            "Ban a player from your SkyBlock Island.",
            ["banish"]
        ));

        $this->registerSubCommand(new Chat(
            $this->plugin,
            "chat",
            "Chat with the members of a SkyBlock island."
        ));

        $this->registerSubCommand(new Create(
            $this->plugin,
            "create",
            "Create your SkyBlock island!"
        ));

        $this->registerSubCommand(new CreateWorld(
            $this->plugin,
            "createworld",
            "Creates a new world ready for SkyBlock!",
            ["cw"]
        ));

        $this->registerSubCommand(new DecreaseSize(
            $this->plugin,
            "decreasesize",
            "Decrease the size of a player's SkyBlock island.",
            ["decrease", "subtractsize", "subtract"]
        ));

        $this->registerSubCommand(new Delete(
            $this->plugin,
            "delete",
            "Delete a player's SkyBlock island.",
            ["disband", "kill", "eridicate", "expunge", "cancel"]
        ));

        $this->registerSubCommand(new Demote(
            $this->plugin,
            "demote",
            "Demote a player on your SkyBlock island."
        ));

        $this->registerSubCommand(new Fly(
            $this->plugin,
            "fly",
            "Enable Flight in the SkyBlock world."
        ));

        $this->registerSubCommand(new Help(
            $this->plugin,
            "help",
            "Open the RedSkyBlock Help menu"
        ));

        $this->registerSubCommand(new IncreaseSize(
            $this->plugin,
            "increasesize",
            "Increase the size of a player's SkyBlock island.",
            ["increase", "addsize"]
        ));

        $this->registerSubCommand(new Info(
            $this->plugin,
            "info",
            "See detailed info about the SkyBlock Island you're on."
        ));

        $this->registerSubCommand(new Invite(
            $this->plugin,
            "invite",
            "Invite a player to join your SkyBlock island.",
            ["coop", "add"]
        ));

        $this->registerSubCommand(new Kick(
            $this->plugin,
            "kick",
            "Kick a player off of your SkyBlock island."
        ));

        $this->registerSubCommand(new Leave(
            $this->plugin,
            "leave",
            "Resign from a player's SkyBlock island.",
            ["quit"]
        ));

        $this->registerSubCommand(new Level(
            $this->plugin,
            "level",
            "View a SkyBlock island's level.",
            ["xp"]
        ));

        $this->registerSubCommand(new Lock(
            $this->plugin,
            "lock",
            "Lock your SkyBlock island.",
            ["close"]
        ));

        $this->registerSubCommand(new Name(
            $this->plugin,
            "name",
            "View the name of the SkyBlock island you are on."
        ));

        $this->registerSubCommand(new OnIsland(
            $this->plugin,
            "onisland",
            "View the players on your island.",
            ["on"]
        ));

        $this->registerSubCommand(new Promote(
            $this->plugin,
            "promote",
            "Promote a player on your SkyBlock island."
        ));

        $this->registerSubCommand(new Rank(
            $this->plugin,
            "rank",
            "View the rank of an island"
        ));

        $this->registerSubCommand(new Reload(
            $this->plugin,
            "reload",
            "Reloads SkyBlock data files."
        ));

        $this->registerSubCommand(new Remove(
            $this->plugin,
            "remove",
            "Remove a member from your SkyBlock island."
        ));

        $this->registerSubCommand(new RemovePermission(
            $this->plugin,
            "removepermission",
            "Remove a permission from an island rank on your SkyBlock Island.",
            ["removeperm", "unsetpermission", "deletepermission", "deleteperm", "unsetperm"]
        ));

        $this->registerSubCommand(new Rename(
            $this->plugin,
            "rename",
            "Renames your SkyBlock island."
        ));

        $this->registerSubCommand(new Reset(
            $this->plugin,
            "reset",
            "Reset your SkyBlock island."
        ));

        $this->registerSubCommand(new SetSize(
            $this->plugin,
            "setsize",
            "Set the size of a player's island.",
            ["size"]
        ));

        $this->registerSubCommand(new SetSpawn(
            $this->plugin,
            "setspawn",
            "Changes the spawnpoint on your SkyBlock island."
        ));

        $this->registerSubCommand(new Setting(
            $this->plugin,
            "setting",
            "Edit an Island Setting on your SkyBlock island."
        ));

        $this->registerSubCommand(new SetWorld(
            $this->plugin,
            "setworld",
            "Select a world to use for SkyBlock.",
            ["sw"]
        ));

        $this->registerSubCommand(new Teleport(
            $this->plugin,
            "teleport",
            "Teleport to your SkyBlock island.",
            ["tp", "go", "spawn", "goto"]
        ));

        $this->registerSubCommand(new TopIslands(
            $this->plugin,
            "topislands",
            "View the top SkyBlock islands.",
            ["top", "leaderboard", "lb"]
        ));

        $this->registerSubCommand(new Unlock(
            $this->plugin,
            "unlock",
            "Unlock your SkyBlock island.",
            ["open"]
        ));

        $this->registerSubCommand(new UpdateZone(
            $this->plugin,
            "updatezone",
            "Updates the custom island zone.",
        ));

        $this->registerSubCommand(new Value(
            $this->plugin,
            "value",
            "View the value of an island."
        ));

        $this->registerSubCommand(new Visit(
            $this->plugin,
            "visit",
            "Visit another SkyBlock Island!",
            ["tour"]
        ));

        $this->registerSubCommand(new ZoneTools(
            $this->plugin,
            "zonetools",
            "Gives Custom Island Creator Tools",
            ["zt", "zonetool"]
        ));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (isset($args["help"])) {

            $sender->sendMessage("Success!");
        } else {

            $this->sendUsage();
        }
    }

    public function getPermission(): string
    {
        return $this->permission;
    }
}
