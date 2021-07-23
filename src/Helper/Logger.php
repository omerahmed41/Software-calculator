<?php


namespace App\Helper;


class Logger
{
        public bool $cliMode = false ;
    public function print_message($message, $state = null)
    {
        $message = json_encode($message);
        if ($this->cliMode) {
            if ($state == 'success') {
                print_r("\n\033[32m$message \033[0m\n");
            } elseif ($state == 'error') {
                print_r("\n\033[31m $message \033[0m\n");
            } elseif ($state == 'warning') {
                print_r("\n\033[33m$message \033[0m\n");
            } elseif ($state == 'debug') {
                print_r("\n\033[36m$message \033[0m\n");
            } elseif ($state == 'cli') {
                print_r("\n\033[36m$message \033[0m\n");
            } else {
                print_r("\n\033[36m$message \033[0m\n");
            }
        }

    }
}