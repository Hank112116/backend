<?php
namespace Backend\Model;

use Backend\Contracts\Serializable;

class Attachment implements Serializable
{
    const KEY_NAME     = 'name';
    const KEY_URL      = 'url';
    const KEY_SIZE     = 'size';
    const KEY_PREVIEWS = 'previews';

    private $data;
    private $name;
    private $url;
    private $size;
    private $preview;

    public function __construct(array $data)
    {
        $this->data    = $data;
        $this->name    = $data[self::KEY_NAME];
        $this->url     = $data[self::KEY_URL];
        $this->size    = $data[self::KEY_SIZE];
        $this->preview = $data[self::KEY_PREVIEWS];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getPreview()
    {
        return $this->preview[0];
    }

    public function normalize()
    {
        return $this->data;
    }

    public static function denormalize(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("'{$data}' is not a valid array format");
        }

        return new Attachment($data);
    }

    public function serialize()
    {
        return json_encode($this);
    }

    public static function deserialize($serialized)
    {
        $result = json_decode($serialized, true);

        if (!is_array($result)) {
            throw new \InvalidArgumentException("'{$serialized}' is not a valid array format");
        }

        return static::denormalize($result);
    }

    public function jsonSerialize()
    {
        return $this->normalize();
    }
}
