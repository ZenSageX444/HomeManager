<?php

namespace zensage_x\HomeManager\utils;

use pocketmine\player\Player;
use pocketmine\world\Position;

use zensage_x\HomeManager\HomeManager;

class HomeUtils{
    
    public static function teleportWorldXYZ(Player $player, string $world, int $x, int $y, int $z): void{
        $manager = HomeManager::getInstance()->getServer()->getWorldManager();
        if(!$manager->isWorldGenerated($world)){
            $player->sendMessage(HomeManager::getInstance()->getPrefix()."§c world§f $world §cis null");
            return;
        }elseif(!$manager->isWorldLoaded($world)){
            $player->sendMessage(HomeManager::getInstance()->getPrefix()."§e loadworld§f $world §e....");
            if(!$manager->loadWorld($world)){
                $player->sendMessage(HomeManager::getInstance()->getPrefix()."§c can not load world§f $world §c...");
                return;
            }
        }
        $pos = new Position($x, $y, $z, $manager->getWorldByName($world));
        $player->teleport($pos);
        $player->sendMessage(HomeManager::getInstance()->getPrefix()."§a Teleport to §eworld§f $world §eXYZ:§f $x $y $z §aSuccessful");
    }    
    
}
