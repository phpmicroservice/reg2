<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: common.proto

namespace GPBMetadata;

class Common
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
common.protoms.register"7
Reg
name (	"
data (2.ms.register.RegData"�
RegData
name (	
host (	
port (\'
type (2.ms.register.RegData.Type"@
Type
TCP 
Http	
Https	
Http2
Ws
Wssbproto3'
        , true);

        static::$is_initialized = true;
    }
}

