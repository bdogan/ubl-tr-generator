<?php

function pr()
{
    $data = func_get_args();
    echo "<pre>" . print_r($data, true) . "</pre>";
}