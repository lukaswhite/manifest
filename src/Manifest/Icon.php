<?php namespace Lukaswhite\Manifest;

/**
 * Class Icon
 *
 * @package Lukaswhite\Manifest
 */
class Icon implements \JsonSerializable
{
    /**
     * The source path to the icon
     *
     * @var string
     */
    protected $src;

    /**
     * The sizes
     *
     * @var string
     */
    protected $sizes;

    /**
     * The (mime)type of the icon
     *
     * @var string
     */
    protected $type;

    /**
     * The density of the icon
     *
     * @var float
     */
    protected $density;

    /**
     * Icon constructor.
     *
     * @param string $src
     * @param string|integer|array $sizes
     * @param string $type
     */
    public function __construct( $src, $sizes, $type = null, $density = null )
    {
        $this->src = $src;

        if ( is_integer( $sizes ) ) {
            $this->sizes = sprintf( '%dx%d', $sizes, $sizes );
        } elseif ( is_array( $sizes ) ) {
            $this->setMultipleSizes( $sizes );
        } else {
            $this->sizes = $sizes;
        }

        if ( $type ) {
            $this->type = $type;
        }

        if ( $density ) {
            $this->density = $density;
        }
    }

    /**
     * Set multiple sizes at the same time
     *
     * @param array $sizes
     */
    private function setMultipleSizes( $sizes )
    {
        $data = [ ];
        foreach( $sizes as $size ) {
            if ( is_integer( $size ) ) {
                $data[ ] = sprintf( '%dx%d', $size, $size );
            } else {
                $data[ ] = $size;
            }
        }
        $this->sizes = implode( ' ', $data );
    }

    /**
     * Create an array representation of this icon
     *
     * @return array
     */
    public function toArray( )
    {
        $data = [
            'src'       =>  $this->src,
            'sizes'     =>  $this->sizes,
        ];

        if ( $this->type )
        {
            $data[ 'type' ] = $this->type;
        }

        if ( $this->density )
        {
            $data[ 'density' ] = $this->density;
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