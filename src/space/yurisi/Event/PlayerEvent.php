<?php

namespace space\yurisi\Event;

use pocketmine\event\block\BlockEvent;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use space\yurisi\DB\DataBase;
use space\yurisi\SimpleLogger;

class PlayerEvent implements Listener {

    /**
     * @priority MONITOR
     * @param BlockBreakEvent $event
     * @return bool
     */
    public function onBreak(BlockBreakEvent $event) {
        $this->checkLog($event, "b");
        return true;
    }

    /**
     * @priority MONITOR
     * @param BlockPlaceEvent $event
     * @return bool
     */
    public function onPlace(BlockPlaceEvent $event) {
        $this->checkLog($event, "p");
        return true;
    }

    private function checkLog(BlockEvent $event, string $eventType) {
        $player = $event->getPlayer();
        if ($player instanceof Player) {
            $x = $event->getBlock()->getFloorX();
            $y = $event->getBlock()->getFloorY();
            $z = $event->getBlock()->getFloorZ();
            $world = $event->getBlock()->getLevel()->getFolderName();
            $cls = new DataBase();

            if (SimpleLogger::getInstance()->isOn($player)) {
                $cls->checklog($x, $y, $z, $world, $player);
                $event->setCancelled();
            } else {
                $id = $event->getBlock()->getId();
                $meta = $event->getBlock()->getDamage();
                $cls->registerlog($x, $y, $z, $world, $id, $meta, $player, $eventType);
            }
        }
        return true;
    }
}