<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: common.proto

namespace Ms\Register;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 **
 * 注册数据
 *
 * Generated from protobuf message <code>ms.register.RegData</code>
 */
class RegData extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string name = 1;</code>
     */
    protected $name = '';
    /**
     * Generated from protobuf field <code>string host = 2;</code>
     */
    protected $host = '';
    /**
     * Generated from protobuf field <code>int32 port = 3;</code>
     */
    protected $port = 0;
    /**
     * Generated from protobuf field <code>.ms.register.RegData.Type type = 4;</code>
     */
    protected $type = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *     @type string $host
     *     @type int $port
     *     @type int $type
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Common::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string host = 2;</code>
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Generated from protobuf field <code>string host = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setHost($var)
    {
        GPBUtil::checkString($var, True);
        $this->host = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 port = 3;</code>
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Generated from protobuf field <code>int32 port = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setPort($var)
    {
        GPBUtil::checkInt32($var);
        $this->port = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.ms.register.RegData.Type type = 4;</code>
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Generated from protobuf field <code>.ms.register.RegData.Type type = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkEnum($var, \Ms\Register\RegData\Type::class);
        $this->type = $var;

        return $this;
    }

}

