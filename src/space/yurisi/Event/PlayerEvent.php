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

    /** @var SimpleLogger */
    private $main;

    public function __construct(SimpleLogger $main) {
        $this->main = $main;
    }

    /**
     * @priority MONITOR
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event) {
        $this->checkLog($event, "b");
    }

    /**
     * @priority MONITOR
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) {
        $this->checkLog($event, "p");
    }

    private function checkLog(BlockEvent $event, string $eventType) {
        $player = $event->getPlayer();
        if ($player instanceof Player) {
            $block = $event->getBlock();
            $floorVec = $block->floor();
            $x = $floorVec->x;
            $y = $floorVec->y;
            $z = $floorVec->z;
            $world = $block->getLevel()->getFolderName();
            $cls = DataBase::getInstance();
            if ($this->main->isOn($player)) {
                $cls->checklog($x, $y, $z, $world, $player);
                $event->setCancelled();
            } else {
                $id = $block->getId();
                $meta = $block->getDamage();
                $cls->registerlog($x, $y, $z, $world, $id, $meta, $player, $eventType);
            }
        }
        return true;
    }
}