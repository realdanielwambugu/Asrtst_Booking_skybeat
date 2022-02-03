<?php

Namespace interfaces;
/**
 *
 */
interface ContainerInterface
{
    public function bind($key, $value = null, $args = [], $singleton = false);

    public function singleton($key, $value = null, $args = []);

    public function make($key, $args = []);
}
