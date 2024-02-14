<?php

namespace Useful\Controller;

/**
 * Class QuickGitController
 * @package Useful\Controller
 */
class QuickGitController
{

    private $version;

    function __construct()
    {
        try {
            exec('git describe --always', $version_mini_hash);
            exec('git rev-list HEAD | wc -l', $version_number);
            // exec('git log -1',$line);
            $version = $version_number[0] ?? '0';
            $hash = $version_mini_hash[0] ?? '0';
            $this->version['short'] = "2.36.248." . substr($hash, 0, 2);
        } catch (\Exception $e) {
            $this->version['short'] = "2.36.248";
        }
    }

    public function short()
    {
        return $this->version['short'];
    }

    public function output()
    {
        return $this->version;
    }

    public function show()
    {
        echo $this->version;
    }
}