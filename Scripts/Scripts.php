<?php

namespace Scripts;

use IRC\Event\Command\CommandEvent;
use IRC\Event\Listener;
use IRC\Event\Plugin\PluginLoadEvent;
use IRC\Event\Plugin\PluginUnloadEvent;
use IRC\Plugin\Plugin;
use IRC\Plugin\PluginBase;

class Scripts extends PluginBase implements Listener{

    private $pluginCommands = [];
    private $config = [];

    public function onLoad(){
        $this->config = json_decode(file_get_contents("plugins/Scripts/plugin.json"), true)["configuration"];
        $this->updateCommands();
        $this->getEventHandler()->registerEvents($this, $this->plugin);
    }


    public function updateCommands(){
        $this->pluginCommands = [];
        $commands = $this->getConnection()->getCommandMap()->getCommands();
        foreach($commands as $command){
            $this->pluginCommands[$command->getCommand()] = true;
        }
    }

    public function onPluginLoadEvent(PluginLoadEvent $event){
        $this->updateCommands();
    }

    public function onPluginUnloadEvent(PluginUnloadEvent $event){
        $this->updateCommands();
    }

    /**
     * @param $command
     * @param CommandEvent $event
     * @return array|number
     */
    public function executeScript($command, CommandEvent $event){
        $shell = $event->getChannel()->getName()." ".$event->getUser()->getNick()." ".implode(" ", $event->getArgs());
        $interpreter = (new \SplFileObject($command))->getExtension();
        if(isset($this->config[$interpreter])){
            exec($this->config[$interpreter]." ".$command." ".escapeshellcmd($shell), $output, $return);
            if($return === 0){
                return $output;
            }
            return $return;
        }
        return 127; //Command not found
    }

    /**
     * @param $command
     * @return bool|string
     */
    public function isScript($command){
        $files = glob("plugins/Scripts/scripts/".basename($command).".*");
        foreach($files as $file){
            if(is_file($file)){
                return $file;
            }
        }
        return false;
    }

    public function onCommandEvent(CommandEvent $event){
        $command = strtolower($event->getCommand());
        if(!isset($this->pluginCommands[$command])){
            $script = $this->isScript($command);
            if($script !== false){
                $result = $this->executeScript($script, $event);
                if(is_array($result)){
                    foreach($result as $message){
                        $event->getChannel()->sendMessage($message);
                    }
                } else {
                    $event->getChannel()->sendMessage("Error! Script terminated with exit status ".$result);
                }
            }
            $event->setCancelled();
        }
    }

}