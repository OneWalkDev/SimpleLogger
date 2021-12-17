<?php
declare(strict_types=1);

namespace space\yurisi\DB;

use pocketmine\item\ItemFactory;

use pocketmine\player\Player;
use SQLite3;

class DataBase extends SQLite3 {

    public function __construct(string $path) {
        parent::__construct("{$path}log.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        $this->query("CREATE TABLE IF NOT EXISTS logdata (xyz TEXT PRIMARY KEY, who TEXT , action TEXT, time TEXT, id INT,meta INT)");
    }

    public function registerLog(int $x, int $y, int $z, string $level, int $id, int $meta,Player $player,String $eventType){
        $xyz ="x{$x}y{$y}z{$z}w{$level}";
        $who = $player->getName();
        $time = date("Y/m/d-H:i:s");
        $this->query("INSERT OR REPLACE INTO logdata VALUES(\"$xyz\",   \"$who\",  \"$eventType\", \"$time\", \"$id\",\"$meta\")");
    }

    public function checkLog(int $x, int $y, int $z, string $level, Player $player){
        $xyz ="x{$x}y{$y}z{$z}w{$level}";
        $query = $this->query("SELECT who, action, id, meta, time FROM logdata WHERE xyz = \"$xyz\"");
        $results = $query->fetchArray(SQLITE3_ASSOC);
        if(!$results){
            $player->sendPopup("{$x},{$y},{$z},{$level} ここにログは存在していません");
        }else{
            $pb = $results['action'] === "b" ? "破壊": "設置";
            $item_name = ItemFactory::getInstance()->get($results['id'], $results['meta'], 1)->getName();
            $player->sendPopup("§c[座標] {$x},{$y},{$z},{$level}\n[日時] {$results['time']}\n[行動者] {$results['who']}\n[行動]{$pb}\n[物] {$results['id']}:{$results['meta']} {$item_name}");
        }
    }

}