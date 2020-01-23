<?php

namespace yurisi\Command;

use pocketmine\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use yurisi\SimpleLogger;
use yurisi\Database\SQliteLogger;

class MainCommand extends Command {

    public function __construct(SimpleLogger $main) {
        $this->main = $main;
        parent::__construct("log", "ログの確認のオンオフ", "/log x y z world");
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if ($sender->isOp()) {
            if (!isset($args[0])) {
                if ($sender instanceof Player) {
                    if ($this->main->playerlog[$sender->getName()] === 0) {
                        $this->main->playerlog[$sender->getName()] = 1;
                        $sender->sendMessage("[{$this->main->plugin}]ログ表示をオンにしました");
                        return true;
                    } else {
                        $this->main->playerlog[$sender->getName()] = 0;
                        $sender->sendMessage("[{$this->main->plugin}]ログ表示をオフにしました");
                        return true;
                    }
                } else {
                    $sender->sendMessage("/log x y z worldname");
                    return true;
                }
            }
            if (isset($args[0]) && isset($args[1]) && isset($args[2]) && isset($args[3])) {
                if (is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])) {
                    $cls=new SQliteLogger($this->main);
                    $cls->checklog($args[0], $args[1], $args[2], $args[3], $sender);
                } else {
                    $sender->sendMessage("/log x y z worldname");
                    return true;
                }
            } else {
                $sender->sendMessage("/log x y z worldname");
                return true;
            }
        }

    }
}