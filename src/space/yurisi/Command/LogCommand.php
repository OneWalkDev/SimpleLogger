<?php

namespace space\yurisi\Command;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\Player;

use pocketmine\command\CommandSender;

use space\yurisi\DB\DataBase;
use space\yurisi\SimpleLogger;

class LogCommand extends VanillaCommand {

    public function __construct() {
        parent::__construct("log", "ログの確認のオンオフ", "/log [x] [y] [z] [world]");
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if ($sender instanceof Player) {
            if ($sender->isOp()) {
                if (!isset($args[0])) {
                    $tag = $sender->namedtag;
                    $msg = ["OFF", "ON"];
                    SimpleLogger::getInstance()->isOn($sender) ? $flag = 0 : $flag = 1;
                    $tag->setInt(SimpleLogger::getInstance()->getName(), $flag);
                    $sender->sendMessage("[" . SimpleLogger::getInstance()->getName() . "]§a{$msg[$flag]}にしました。");
                    return true;
                }

                if (isset($args[0]) && isset($args[1]) && isset($args[2]) && isset($args[3])) {
                    if (is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])) {
                        $cls = new DataBase();
                        $cls->checklog($args[0], $args[1], $args[2], $args[3], $sender);
                        return true;
                    }
                }
                $sender->sendMessage("/log [x] [y] [z] [world]");
            }
        }
        return true;
    }
}