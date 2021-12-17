<?php
declare(strict_types=1);

namespace space\yurisi\Command;

use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use space\yurisi\SimpleLogger;

class LogCommand extends Command {

    private SimpleLogger $main;

    public function __construct(SimpleLogger $main) {
        $this->main = $main;
        parent::__construct("log", "ログの確認", "/log [x] [y] [z] [world]");
        $this->setPermission("space.yurisi.log");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) return false;
        if (!$this->testPermission($sender)) return false;
        if (!isset($args[0])) {
            $msg = ["ON", "OFF"];
            $this->main->changeParam($sender);
            $flag = $this->main->isOn($sender) ? 0 : 1;
            $sender->sendMessage("[SimpleLogger]§a{$msg[$flag]}にしました。");
            return true;
        }

        if (isset($args[1]) && isset($args[2]) && isset($args[3])) {
            if (is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])) {
                $cls = $this->main->getDB();
                $cls->checklog($args[0], $args[1], $args[2], $args[3], $sender);
                return true;
            }
        }
        return true;
    }
}