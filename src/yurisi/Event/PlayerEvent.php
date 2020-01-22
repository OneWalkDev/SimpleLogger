<?php

namespace yurisi\Event;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use yurisi\Database\SQliteLogger;
use yurisi\main;

class PlayerEvent implements Listener{

    public function __construct(main $main) {
        $this->main = $main;
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->main->playerlog[$event->getPlayer()->getName()]=0;
    }

    /**
     * @priority MONITOR
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if($this->main->playerlog[$name]===1){
            $x = $event->getBlock()->getFloorX();
            $y = $event->getBlock()->getFloorY();
            $z = $event->getBlock()->getFloorZ();
            $world = $event->getBlock()->getLevel()->getName();
            $cls=new SQliteLogger($this->main);
            $cls->checklog($x,$y,$z,$world,$player);
            $event->setCancelled();
            return true;
        }

        $x = $event->getBlock()->getFloorX();
        $y = $event->getBlock()->getFloorY();
        $z = $event->getBlock()->getFloorZ();
        $world = $event->getBlock()->getLevel()->getName();
        $id = $event->getBlock()->getId();
        $meta=$event->getBlock()->getDamage();
	$cls=new SQliteLogger($this->main);
        $cls->registerlog($x,$y,$z,$world,$id,$meta,$player,"b");
    }

    /**
     * @priority MONITOR
     */
    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if($this->main->playerlog[$name]===1){
            $x = $event->getBlock()->getFloorX();
            $y = $event->getBlock()->getFloorY();
            $z = $event->getBlock()->getFloorZ();
            $world = $event->getBlock()->getLevel()->getName();
            $cls=new SQliteLogger($this->main);
            $cls->checklog($x,$y,$z,$world,$player);
            $event->setCancelled();
            return true;
        }

        $x = $event->getBlock()->getFloorX();
        $y = $event->getBlock()->getFloorY();
        $z = $event->getBlock()->getFloorZ();
        $world = $event->getBlock()->getLevel()->getName();
        $id = $event->getBlock()->getId();
        $meta=$event->getBlock()->getDamage();
	$cls=new SQliteLogger($this->main);
        $cls->registerlog($x,$y,$z,$world,$id,$meta,$player,"p");
    }
}