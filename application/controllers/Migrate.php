<?php

class Migrate extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
    }

    public function index()
    {
        if ($this->migration->latest() === FALSE)
        {
            show_error($this->migration->error_string());
        }
    }
}