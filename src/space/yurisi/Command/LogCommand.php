<?php

namespace space\yurisi\Command;

use pocketmine\Player;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\permission\Permission;
use space\yurisi\DB\DataBase;
use space\yurisi\SimpleLogger;

class LogCommand extends PluginCommand {

    public function __construct(SimpleLogger $main) {
        parent::__construct("log", $main);
        $this->setDescription("ログの確認のオンオフ");
        $this->setUsage("/log [x] [y] [z] [world]");
        $this->setPermission(Permission::DEFAULT_OP);
    }

    public function execute(CommandSender $sender, string $label, array $args): bool{
        if ($sender instanceof Player) {
            if ($this->testPermission($sender)) {
                if (!isset($args[0])) {
                    /** @var SimpleLogger $main */
                    $main = $this->getPlugin();
                    $tag = $sender->namedtag;
                    $msg = [
                        "OFF",
                        "ON"
                    ];
                    $flag = $main->isOn($sender) ? 0 : 1;
                    $tag->setInt($main->getName(), $flag);
                    $sender->sendMessage("[{$main->getName()}]§a{$msg[$flag]}にしました。");
                    return true;
                }

                if (isset($args[0]) && isset($args[1]) && isset($args[2]) && isset($args[3])) {
                    if (is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])) {
                        $cls = DataBase::getInstance();
                        $cls->checklog($args[0], $args[1], $args[2], $args[3], $sender);
                        return true;
                    }
                }
                throw new InvalidCommandSyntaxException();
            }
        }
        return true;
    }
}