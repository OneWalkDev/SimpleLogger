<?php

namespace yurisi\Database;

use yurisi\main;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;

class SQliteLogger{


    public function __construct(main $main) {
        $this->main = $main;
    }

    public function registerlog($x, $y, $z,String $level,Int $id,Int $meta,Player $player,String $eventname){
        $xyz =""."x"."$x"."y"."$y"."z"."$z"."w"."$level"."";
        $who = $player->getName();
        $time = date("Y/m/d-H:i:s");
        $this->main->log->query("INSERT OR REPLACE INTO logdata VALUES(\"$xyz\",   \"$who\",  \"$eventname\", \"$time\", \"$id\",\"$meta\")");
    }

    public function checklog($x, $y, $z,String $level,Player $player){
        $xyz =""."x"."$x"."y"."$y"."z"."$z"."w"."$level"."";
        $result = $this->main->log->query("SELECT who , action, id,meta, time FROM logdata WHERE xyz = \"$xyz\"");
        $results = $result->fetchArray(SQLITE3_ASSOC);
        if($results['who'] == null){
            $player->sendPopup("[".$this->main->plugin."]".$x.",".$y.",".$z.",".$level." ここにログは存在していません");
        }elseif($result){
            if($results['action']==="b"){
                $pb="破壊";
            }else{
                $pb="設置";
            }
            $itemname=Item::get($results['id'],$results['meta'],1)->getName();

            $player->sendPopup("§c[座標] ".$x.",".$y.",".$z.",".$level."\n[日時] ". $results['time']."\n[行動者] ". $results['who']."\n[行動]". $pb."\n[物] ". $results['id'].":".$results['meta']." ".$itemname);

        }
    }
}