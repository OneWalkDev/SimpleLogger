<?php

namespace yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\utils\Config;

use yurisi\Command\MainCommand;
use yurisi\Event\PlayerEvent;

class SimpleLogger extends PluginBase implements Listener {

    public $playerlog;

    public $plugin="SimpleLogger";

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvent($this),$this);
        $this->getServer()->getCommandMap()->register("log", new MainCommand($this));
        $this->getLogger()->info("§b=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+");
        $this->getLogger()->info("§b=+".$this->plugin."を開きました                    =+");
        $this->getLogger()->info("§b=+".$this->plugin."の二次配布は一切禁止です。      =+");
        $this->getLogger()->info("§b=+".$this->plugin."は超軽量のログ保存システムですが=+");
        $this->getLogger()->info("§b=+定期的にlog.dbを削除してください。          =+");
        $this->getLogger()->info("§b=+問題やバグなどがあれば twitter @dev_yrsまで =+");
        $this->getLogger()->info("§b=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+");

        if(!file_exists($this->getDataFolder()."log.db")){
            $this->log = new \SQLite3($this->getDataFolder()."log.db",SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        }else{
            $this->log = new \SQLite3($this->getDataFolder()."log.db",SQLITE3_OPEN_READWRITE);
        }
        $this->log->query("CREATE TABLE IF NOT EXISTS logdata (xyz TEXT PRIMARY KEY, who TEXT , action TEXT, time TEXT, id INT,meta INT)");
    }


   public function onDisable() {
      $this->log->close();
   }
}