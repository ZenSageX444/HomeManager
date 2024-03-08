<?php

namespace zensage_x\HomeManager;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use zensage_x\HomeManager\ui\HomeUI;
use zensage_x\HomeManager\database\HomeData;

class HomeManager extends PluginBase{
    use \pocketmine\utils\SingletonTrait;

    private string $prefix = "§aHome:";
    private HomeData $homeData;
    private HomeUI $homeUI;
    
    public function getPrefix(): string{
        return $this->prefix;
    }
    
    public function onLoad(): void{
		self::setInstance($this);
    }
    
    public function onEnable(): void{
        $this->homeData = new HomeData($this);
        $this->homeUI = new HomeUI($this);
    }
    
    public function getHomeData(): HomeData{
        return $this->homeData;
    }
    
    public function getHomeUI(): HomeUI {
        return $this->homeUI;
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $commandLabel, array $args): bool{
        if($sender instanceof Player){
            $player = $sender;
            if($command->getName() == "home"){
                $this->getHomeUI()->HomeManagerUI($player);
            }
        }
        return false;
    }
}
