<?php namespace Lukaswhite\Manifest\Tests;

use PHPUnit\Framework\TestCase;
use Lukaswhite\Manifest\Manifest;
use Lukaswhite\Manifest\Icon;
use Lukaswhite\Manifest\RelatedApplication;
use org\bovigo\vfs\vfsStream;

class ManifestTest extends TestCase
{
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup( 'json' );
    }

    public function testEmpty( )
    {
        $manifest = new Manifest( );
        $this->assertAttributeEquals( [ ], 'data', $manifest );
    }

    public function testSettingNameAndDescription( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'My Awesome App' )
            ->shortName( 'AwesomeApp' )
            ->description( 'Just testing' );

        $this->assertEquals(
            [
                'name'          =>  'My Awesome App',
                'short_name'    =>  'AwesomeApp',
                'description'   =>  'Just testing'
            ],
            $manifest->jsonSerialize( )
        );
    }

    public function testCreatesJson( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'My Awesome App' )
            ->shortName( 'AwesomeApp' )
            ->description( 'Just testing' );
        $json = $manifest->toJson( );
        $this->assertEquals(
            '{"name":"My Awesome App","short_name":"AwesomeApp","description":"Just testing"}',
            $json
        );
        $this->assertEquals(
            '{"name":"My Awesome App","short_name":"AwesomeApp","description":"Just testing"}',
            json_encode( $manifest )
        );
    }

    public function testCreatesJsonPrettyPrinted( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'My Awesome App' )
            ->shortName( 'AwesomeApp' )
            ->description( 'Just testing' );
        $json = $manifest->toJson( JSON_PRETTY_PRINT );
        $this->assertEquals(
            '{
    "name": "My Awesome App",
    "short_name": "AwesomeApp",
    "description": "Just testing"
}',
            $json
        );
    }

    public function testSavesToFile( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'My Awesome App' )
            ->shortName( 'AwesomeApp' )
            ->description( 'Just testing' );
        $manifest->save( vfsStream::url( 'json/manifest.json' ) );
        $this->assertTrue( $this->root->hasChild( 'json/manifest.json' ) );
        $this->assertEquals(
            '{"name":"My Awesome App","short_name":"AwesomeApp","description":"Just testing"}',
            file_get_contents( vfsStream::url( 'json/manifest.json' ) )
        );
    }

    public function testSavesToFilePrettyPrinted( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'My Awesome App' )
            ->shortName( 'AwesomeApp' )
            ->description( 'Just testing' );
        $manifest->save( vfsStream::url( 'json/manifest.json' ), true );
        $this->assertTrue( $this->root->hasChild( 'json/manifest.json' ) );
        $this->assertEquals(
            '{
    "name": "My Awesome App",
    "short_name": "AwesomeApp",
    "description": "Just testing"
}',
            file_get_contents( vfsStream::url( 'json/manifest.json' ) )
        );
    }

    public function testSettingOtherProperties( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'My Awesome App' )
            ->shortName( 'AwesomeApp' )
            ->description( 'Just testing' )
            ->language( 'en-GB' )
            ->scope( '/myapp/' )
            ->startUrl( '/?utm_source=homescreen' )
            ->themeColor( 'aliceblue' )
            ->backgroundColor( '#ff0000' )
            ->gcmSenderId( 12345 );

        $this->assertEquals(
            [
                'name'              =>  'My Awesome App',
                'short_name'        =>  'AwesomeApp',
                'description'       =>  'Just testing',
                'lang'              =>  'en-GB',
                'scope'             =>  '/myapp/',
                'start_url'         =>  '/?utm_source=homescreen',
                'theme_color'       =>  'aliceblue',
                'background_color'  =>  '#ff0000',
                'gcm_sender_id'     =>  12345,
            ],
            $manifest->jsonSerialize( )
        );
    }

    public function testIcons( )
    {
        $manifest = new Manifest( );

        $manifest->icons( [
            new Icon(
                'icon/lowres.webp',
                '48x48'
            ),
            new Icon(
                'icon/lowres',
                '48x48'
            ),
        ] );

        $this->assertEquals(
            [
                'icons' => [
                    [
                        'src'   =>  'icon/lowres.webp',
                        'sizes' =>  '48x48'
                    ],
                    [
                        'src'   =>  'icon/lowres',
                        'sizes' =>  '48x48'
                    ]
                ],
            ],
            $manifest->jsonSerialize( )
        );

        $manifest->icon(
            new Icon(
                'icon/hd_hi.ico',
                [ 72, 96, 128, 256 ]
            )
        );

        $this->assertEquals(
            [
                'icons' => [
                    [
                        'src'   =>  'icon/lowres.webp',
                        'sizes' =>  '48x48'
                    ],
                    [
                        'src'   =>  'icon/lowres',
                        'sizes' =>  '48x48'
                    ],
                    [
                        'src'   =>  'icon/hd_hi.ico',
                        'sizes' =>  '72x72 96x96 128x128 256x256',
                    ]
                ],
            ],
            $manifest->jsonSerialize( )
        );

        unset( $manifest );

        $manifest = new Manifest( );

        $manifest->icon(
            new Icon(
                'icon/hd_hi.ico',
                [ 72, 96, 128, 256 ]
            )
        );

        $this->assertEquals(
            [
                'icons' => [
                    [
                        'src'   =>  'icon/hd_hi.ico',
                        'sizes' =>  '72x72 96x96 128x128 256x256',
                    ]
                ],
            ],
            $manifest->jsonSerialize( )
        );

    }

    public function testRelatedApplications( )
    {
        $manifest = new Manifest( );
        $manifest->relatedApplications(
            [
                new RelatedApplication(
                    'play',
                    'https://play.google.com/store/apps/details?id=com.example.app1',
                    'com.example.app1'
                ),
                new RelatedApplication(
                    'itunes',
                    'https://itunes.apple.com/app/example-app1/id123456789'
                ),
            ]
        );

        $this->assertEquals(
            [
                'related_applications' => [
                    [
                        'platform'  =>  'play',
                        'url'       =>  'https://play.google.com/store/apps/details?id=com.example.app1',
                        'id'        =>  'com.example.app1'
                    ],
                    [
                        'platform'  =>  'itunes',
                        'url'       =>  'https://itunes.apple.com/app/example-app1/id123456789',
                    ]
                ]
            ],
            $manifest->jsonSerialize( )
        );

        unset( $manifest );


        $manifest = new Manifest( );
        $manifest->addRelatedApplication(
            new RelatedApplication(
                'play',
                'https://play.google.com/store/apps/details?id=com.example.app1',
                'com.example.app1'
            )
        );
        $manifest->addRelatedApplication(
            new RelatedApplication(
                'itunes',
                'https://itunes.apple.com/app/example-app1/id123456789'
            )
        );
        $manifest->preferRelatedApplications( );

        $this->assertEquals(
            [
                'related_applications' => [
                    [
                        'platform'  =>  'play',
                        'url'       =>  'https://play.google.com/store/apps/details?id=com.example.app1',
                        'id'        =>  'com.example.app1'
                    ],
                    [
                        'platform'  =>  'itunes',
                        'url'       =>  'https://itunes.apple.com/app/example-app1/id123456789',
                    ]
                ],
                'prefer_related_applications'   =>  true,
            ],
            $manifest->jsonSerialize( )
        );
    }

    public function testSettingDisplay( )
    {
        $manifest = new Manifest( );
        $manifest->display( 'standalone' );
        $this->assertEquals(
            [
                'display'   =>  'standalone',
            ],
            $manifest->jsonSerialize( )
        );
        unset( $manifest );
        $manifest = new Manifest( );
        $manifest->standalone( );
        $this->assertEquals(
            [
                'display'   =>  'standalone',
            ],
            $manifest->jsonSerialize( )
        );
        unset( $manifest );
        $manifest = new Manifest( );
        $manifest->browser( );
        $this->assertEquals(
            [
                'display'   =>  'browser',
            ],
            $manifest->jsonSerialize( )
        );
        unset( $manifest );
        $manifest = new Manifest( );
        $manifest->minimalUI( );
        $this->assertEquals(
            [
                'display'   =>  'minimal-ui',
            ],
            $manifest->jsonSerialize( )
        );
        unset( $manifest );
        $manifest = new Manifest( );
        $manifest->fullscreen( );
        $this->assertEquals(
            [
                'display'   =>  'fullscreen',
            ],
            $manifest->jsonSerialize( )
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingDisplayInvalid( )
    {
        $manifest = new Manifest();
        $manifest->display( 'billboard' );
    }

    public function testSettingtextDirection( )
    {
        $manifest = new Manifest( );
        $manifest->textDirection( 'rtl' );
        $this->assertEquals(
            [
                'dir'   =>  'rtl',
            ],
            $manifest->jsonSerialize( )
        );
        unset( $manifest );
        $manifest = new Manifest( );
        $manifest->textDirection( 'auto' );
        $this->assertEquals(
            [
                'dir'   =>  'auto',
            ],
            $manifest->jsonSerialize( )
        );
        unset( $manifest );
        $manifest = new Manifest( );
        $manifest->textDirection( 'ltr' );
        $this->assertEquals(
            [
                'dir'   =>  'ltr',
            ],
            $manifest->jsonSerialize( )
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingTextDirectionInvalid( )
    {
        $manifest = new Manifest();
        $manifest->textDirection( 'upside_down' );
    }

    public function testSettingOrientation( )
    {
        $manifest = new Manifest();
        $manifest->orientation( 'landscape' );
        $this->assertEquals(
            [
                'orientation' => 'landscape',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->landscape( );
        $this->assertEquals(
            [
                'orientation' => 'landscape',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->landscapePrimary( );
        $this->assertEquals(
            [
                'orientation' => 'landscape-primary',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->landscapeSecondary( );
        $this->assertEquals(
            [
                'orientation' => 'landscape-secondary',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->portrait( );
        $this->assertEquals(
            [
                'orientation' => 'portrait',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->portraitPrimary( );
        $this->assertEquals(
            [
                'orientation' => 'portrait-primary',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->portraitSecondary( );
        $this->assertEquals(
            [
                'orientation' => 'portrait-secondary',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->natural( );
        $this->assertEquals(
            [
                'orientation' => 'natural',
            ],
            $manifest->jsonSerialize()
        );
        unset( $manifest );
        $manifest = new Manifest();
        $manifest->anyOrientation( );
        $this->assertEquals(
            [
                'orientation' => 'any',
            ],
            $manifest->jsonSerialize()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingOrientationInvalid( )
    {
        $manifest = new Manifest();
        $manifest->orientation( 'diagonally' );
    }

    public function testFullExample( )
    {
        $manifest = new Manifest( );
        $manifest->name( 'HackerWeb' )
            ->shortName( 'HackerWeb' )
            ->description( 'A simply readable Hacker News app.' )
            ->startUrl( '.' )
            ->standalone( )
            ->backgroundColor( '#fff' )
            ->icons(
                [
                    new Icon( 'images/touch/homescreen48.png', 48, 'image/png' ),
                    new Icon( 'images/touch/homescreen72.png', 72, 'image/png' ),
                    new Icon( 'images/touch/homescreen96.png', 96, 'image/png' ),
                    new Icon( 'images/touch/homescreen144.png', 144, 'image/png' ),
                    new Icon( 'images/touch/homescreen168.png', 168, 'image/png' ),
                    new Icon( 'images/touch/homescreen192.png', 192, 'image/png' ),
                ]
            )
            ->addRelatedApplication(
                new RelatedApplication(
                    'play', 'https://play.google.com/store/apps/details?id=cheeaun.hackerweb'
                )
            );

        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/fixtures/manifest.json',
            $manifest->toJson( )
        );
    }
}