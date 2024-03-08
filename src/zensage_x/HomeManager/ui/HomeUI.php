<?php

namespace zensage_x\HomeManager\ui;

use pocketmine\utils\Config;
use pocketmine\player\Player;

use zensage_x\HomeManager\HomeManager;
use zensage_x\HomeManager\formapi\SimpleForm;

class HomeUI{
    
    private HomeManager $plugin;
    private Config $data;
    
    public function __construct(HomeManager $plugin){
        $this->plugin = $plugin;
    }
    
    public function getPlugin(): HomeManager{
        return $this->plugin;
    }
    
    public function HomeManagerUI(Player $player, string $content = ""): void{
        $count = 0;
        foreach($this->getPlugin()->getHomeData()->getHomeDatas() as $key => $value){
            if($value["owner"] == $player->getName()){
                $count++;
            }
        }
        $form = new SimpleForm(function(Player $player, $data){
            if($data === null)return;
            if($data == "addHome"){
                $this->getPlugin()->getHomeData()->addHome($player);
                $this->HomeManagerUI($player, "\n §eSave Home Successful\n\n");
            }else{
                $this->HomeEditUI($player, $data);
            }
        });
        $form->setTitle("HomeManager");
        $form->setContent($content);
        foreach($this->getPlugin()->getHomeData()->getHomeDatas() as $key => $value){
            if($value["owner"] == $player->getName()){
                $id = $value["id"];
                $x = $value["x"];
                $y = $value["y"];
                $z = $value["z"];
                $world = $value["world"];
                $form->addButton("§eid:§8 $id §eworld:§8 $world\n§axyz:§8 $x $y $z",0,"textures/ui/icon_recipe_nature",$id);
            }
        }
        $form->addButton("addHome",0,"textures/ui/icon_recipe_item","addHome");
        $form->sendToPlayer($player);
    }
    
    public function HomeEditUI(Player $player, int $id): void{
        $form = new SimpleForm(function(Player $player, $data)use($id){
            if($data === null)return;
            if($data == 0){
                $this->getPlugin()->getHomeData()->teleportHome($player, $id);
            }else{
                $this->getPlugin()->getHomeData()->removeHome($id);
                $this->HomeManagerUI($player, "\n §bdelete id §e$id §bsuccessful\n\n");
            }
        });
        $form->setTitle("HomeEditUI");
        $form->setContent("");
        $form->addButton("Teleport to Home",0,"textures/ui/realms_art_icon");
        $form->addButton("Delete Home",0,"textures/ui/icon_trash");
        $form->sendToPlayer($player);
    }
}
