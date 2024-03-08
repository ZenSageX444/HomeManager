<?php

namespace zensage_x\HomeManager\database;

use pocketmine\utils\Config;
use pocketmine\player\Player;

use zensage_x\HomeManager\HomeManager;
use zensage_x\HomeManager\utils\HomeUtils;

class HomeData{
    
    private HomeManager $plugin;
    private Config $data;
    
    public function __construct(HomeManager $plugin){
        $this->plugin = $plugin;
        $this->data = new Config($plugin->getDataFolder()."HomeManager.yml", Config::YAML, array());
    }
    
    public function getPlugin(): HomeManager{
        return $this->plugin;
    }
    
    public function getData(): Config{
        return $this->data;
    }
    
    public function getHomeDatas(): array{
        $data = $this->getData();
        $dataAll = $data->getAll();
        return $dataAll;
    }
    
    public function isHome(int $id): bool{
        $data = $this->getData();
        $dataAll = $data->getAll();
        return isset($dataAll[$id]);
    }    
    
    public function addHome(Player $player): void{
        $pos = $player->getPosition();
        $x = $pos->getFloorX();
        $y = $pos->getFloorY();
        $z = $pos->getFloorZ();
        $world = $pos->getWorld()->getFolderName();
        
        $data = $this->getData();
        $dataAll = $data->getAll();
        $id = count($dataAll);
        if($id > 0){
            $id = end($dataAll)["id"] + 1;
        }
        $dataAll[$id] = [
            "id" => $id,
            "owner" => $player->getName(),
            "world" => $world,
            "x" => $x,
            "y" => $y,
            "z" => $z
            ];
        $data->setAll($dataAll);
        $data->save();
    }
    
    public function removeHome(int $id): void{
        $data = $this->getData();
        $dataAll = $data->getAll();
        if($this->isHome($id)){
            unset($dataAll[$id]);
            $data->setAll($dataAll);
            $data->save();
        }
    }    
    
    public function teleportHome(Player $player, int $id): void{
        if($this->isHome($id)){
            foreach($this->getHomeDatas() as $key => $value){
                if($key == $id){
                    $world = $value["world"];
                    $x = $value["x"];
                    $y = $value["y"];
                    $z = $value["z"];
                    HomeUtils::teleportWorldXYZ($player, $world, $x, $y, $z);
                }
            }
        }
    }
    
}
