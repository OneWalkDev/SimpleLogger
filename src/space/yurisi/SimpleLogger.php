<?php

namespace space\yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use space\yurisi\Command\LogCommand;
use space\yurisi\DB\DataBase;
use space\yurisi\Event\PlayerEvent;

class SimpleLogger extends PluginBase{

   /**
    * @var self
    */
    private static $main;

    /**
     * @var \SQLite3
     */
    private $log;

    public function onEnable(){
	Server::getInstance()->getPluginManager()->registerEvents(new PlayerEvent(),$this);
	Server::getInstance()->getCommandMap()->register("log", new LogCommand());
	self::$main=$this;
	$this->log=new DataBase();
    }

    public static function getInstance():self {
	return self::$main;
    }

    public function getDB():\SQLite3{
	return $this->log;
    }

    public function isOn(Player $player):bool{
	$tag = $player->namedtag;
	if ($tag->offsetExists($this->getName())) if ($tag->getInt($this->getName()) !== 0) return true;
	return false;
    }

   public function onDisable() {
      $this->getDB()->close();
   }
}