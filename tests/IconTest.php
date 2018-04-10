<?php namespace Lukaswhite\Manifest\Tests;

use Lukaswhite\Manifest\Icon;
use PHPUnit\Framework\TestCase;

class IconTest extends TestCase
{
    public function testSimpleCreate( )
    {
        $icon = new Icon(
            'img/icon36x36.png',
            '36x36',
            'image/png'
        );

        $this->assertEquals( [
            'src'   =>  'img/icon36x36.png',
            'sizes' =>  '36x36',
            'type'  =>  'image/png'
        ], $icon->jsonSerialize( ) );
    }

    public function testSettingSizeAsInteger( )
    {
        $icon = new Icon(
            'img/icon36x36.foo',
            36
        );

        $this->assertEquals( [
            'src'   =>  'img/icon36x36.foo',
            'sizes' =>  '36x36'
        ], $icon->jsonSerialize( ) );
    }

    public function testSettingSizeAsMultipleIntegers( )
    {
        $icon = new Icon(
            'icon/hd_hi.ico',
            [ 72, 96, 128, 256 ]
        );

        $this->assertEquals( [
            'src'   =>  'icon/hd_hi.ico',
            'sizes' =>  '72x72 96x96 128x128 256x256',
        ], $icon->jsonSerialize( ) );
    }
}