<?php
declare(strict_types=1);

namespace space\yurisi;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use space\yurisi\Command\LogCommand;
use space\yurisi\DB\DataBase;
use space\yurisi\Event\PlayerEvent;

class SimpleLogger extends PluginBase {

    /** @var DataBase */
    private DataBase $log;

    private Config $config;

    private array $data;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvent($this), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new LogCommand($this));
        $this->log = new DataBase($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "player.yml", Config::YAML);
        $this->data = $this->config->getAll();
    }

    public function getDB(): DataBase {
        return $this->log;
    }

    public function changeParam(Player $player) {
        if (!isset($this->data[$player->getName()]) or !$this->data[$player->getName()]) {
            $this->data[$player->getName()] = true;
            return;
        }
        $this->data[$player->getName()] = false;
    }

    public function isOn(Player $player): bool {
        if (isset($this->data[$player->getName()])) {
            return $this->data[$player->getName()];
        }
        return false;
    }

    public function onDisable(): void {
        $this->config->setAll($this->data);
        try {
            $this->config->save();
        } catch (\JsonException $e) {
        }
        $this->log->close();
    }
}