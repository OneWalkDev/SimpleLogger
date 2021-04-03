<?php

namespace space\yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use space\yurisi\Command\LogCommand;
use space\yurisi\DB\DataBase;
use space\yurisi\Event\PlayerEvent;

use SQLite3;

class SimpleLogger extends PluginBase{

    /** @var SQLite3 */
    private $log;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvent($this), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new LogCommand($this));
        $this->log = new DataBase($this);
    }

    public function getDB(): DataBase{
        return $this->log;
    }

    public function isOn(Player $player): bool{
        $tag = $player->namedtag;
        if ($tag->offsetExists($this->getName()) && ($tag->getInt($this->getName()) !== 0)) {
            return true;
        }
        return false;
    }

    public function onDisable() {
        $this->log->close();
    }
}