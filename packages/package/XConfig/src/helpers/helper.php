<?php

use Package\XConfig\Models\Config;

/**
 * Get configuration value
 *
 * @param  String  $config_name
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfig(String $config_name, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'TX') {
        return $config->value;
    }
    elseif ($config!=NULL && $config->config_type == 'DD') {
        return json_decode($config->options_array);
    } else {
        if($default_value == NULL)
            return '';
        else
            return $default_value;
    }
}

/**
 * Get configuration as array
 *
 * @param  String  $config_name
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfigArray(String $config_name, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'TX') {
        return $config->value;
    }
    elseif ($config!=NULL && $config->config_type == 'DD') {
        return json_decode($config->options_array, true);
    } else {
        if($default_value == NULL)
            return '';
        else
            return $default_value;
    }
}

/**
 * Get config text value or default value
 *
 * @param  String  $config_name
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfigValue(String $config_name, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL) {
        return $config->value;
    }
    else {
        if($default_value == NULL)
            return '';
        else
            return $default_value;
    }
}

/**
 * Get configuration value by key
 *
 * @param  String  $config_name
 * @param  String  $array_key
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfigArrayValueByKey(String $config_name, String $array_key, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'DD' && isset(json_decode($config->options_array)->$array_key)){
        return json_decode($config->options_array)->$array_key;
    } elseif ($config!=NULL && $config->config_type == 'DD' && isset(json_decode($config->options_array, true)[$array_key])){
        return json_decode($config->options_array,true)[$array_key];
    } else
        if($default_value == NULL)
            return '';
        else
            return $default_value;
}

/**
 * Get configuration key by value
 *
 * @param  String  $config_name
 * @param  String  $array_value
 * @param  null  $default_value
 * @return false|int|mixed|string
 */
function getConfigArrayKeyByValue(String $config_name, String $array_value, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();
    if ($config!=NULL && $config->config_type == 'DD'){
        $options_array= json_decode($config->options_array,true);
        $key = array_search($array_value,$options_array);
        return $key;
    } else
        if($default_value == NULL)
            return '';
        else
            return $default_value;
}
