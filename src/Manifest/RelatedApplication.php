<?php namespace Lukaswhite\Manifest;

/**
 * Class RelatedApplication
 *
 * @package Lukaswhite\Manifest
 */
class RelatedApplication implements \JsonSerializable
{
    /**
     * The platform (e.g. play, itunes)
     *
     * @var string
     */
    protected $platform;

    /**
     * The URL
     *
     * @var string
     */
    protected $url;

    /**
     * An optional ID
     *
     * @var string
     */
    protected $id;

    /**
     * RelatedApplication constructor.
     *
     * @param $platform
     * @param $url
     * @param null $id
     */
    public function __construct( $platform, $url, $id = null )
    {
        $this->platform = $platform;
        $this->url = $url;
        $this->id = $id;
    }

    /**
     * Create an array representation of this icon
     *
     * @return array
     */
    public function toArray( )
    {
        $data = [
            'platform'  =>  $this->platform,
            'url'       =>  $this->url,
        ];

        if ( $this->id )
        {
            $data[ 'id' ] = $this->id;
        }

        return $data;
    }

    /**
     * Specifies what data is used to create a JSON representation of this icon
     *
     * @return array
     */
    public function jsonSerialize( )
    {
        return $this->toArray( );
    }

}