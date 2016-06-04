<?php

namespace Scripts;

use IRC\Event\Command\CommandEvent;
use IRC\Event\Listener;
use IRC\Event\Plugin\PluginLoadEvent;
use IRC\Plugin\PluginBase;

class Scripts extends PluginBase implements Listener{

    private $pluginCommands = [];

    public function onLoad(){
        $plugins = $this->getPluginManager()->getPlugins();
        foreach($plugins as $plugin){
            foreach($plugin->commands as $command => $info){
                $this->pluginCommands[strtolower($command)] = $info;
            }
        }
        $this->getEventHandler()->registerEvents($this, $this->plugin);
    }

    public function onPluginLoadEvent(PluginLoadEvent $event){
        foreach($event->getPlugin()->commands as $command => $info){
            $this->pluginCommands[strtolower($command)] = $info;
        }
    }

    /**
     * @param $command
     * @param CommandEvent $event
     * @return array|number
     */
    public function executeScript($command, CommandEvent $event){
        $shell = $event->getChannel()->getName()." ".$event->getUser()->getNick()." ".implode(" ", $event->getArgs());
        exec("php plugins/Scripts/scripts/".basename($command).".php ".escapeshellcmd($shell), $output, $return);
        if($return === 0){
            return $output;
        }
        return $return;
    }

    /**
     * @param $command
     * @return bool
     */
    public function isScript($command){
        return is_file("plugins/Scripts/scripts/".basename($command).".php");
    }

    public function onCommandEvent(CommandEvent $event){
        $command = strtolower($event->getCommand());
        if(!isset($this->pluginCommands[$command])){
            if($this->isScript($command)){
                $result = $this->executeScript($command, $event);
                if(is_array($result)){
                    foreach($result as $message){
                        $event->getChannel()->sendMessage($message);
                    }
                } else {
                    $event->getChannel()->sendMessage("Error! Script terminated with exit status ".$result);
                }
            }
        }
    }

}