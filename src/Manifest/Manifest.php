<?php namespace Lukaswhite\Manifest;

/**
 * Class Manifest
 *
 * @package Lukaswhite\Manifest
 */
class Manifest implements \JsonSerializable
{
    /**
     * The data that mankes up the manifest
     *
     * @var array
     */
    protected $data = [ ];

    /**
     * Manifest constructor.
     *
     * @param array $data
     */
    public function __construct( $data = [ ] )
    {
        $this->data = $data;
    }

    /**
     * Set some data in the manifest
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set( $key, $value )
    {
        $this->data[ $key ] = $value;
        return $this;
    }

    /**
     * Set the application name
     *
     * @param string $value
     * @return $this
     */
    public function name( $value )
    {
        return $this->set( 'name', $value );
    }

    /**
     * Set the application short name
     *
     * @param string $value
     * @return $this
     */
    public function shortName( $value )
    {
        return $this->set( 'short_name', $value );
    }

    /**
     * Set the application description
     *
     * @param string $value
     * @return $this
     */
    public function description( $value )
    {
        return $this->set( 'description', $value );
    }

    /**
     * Set the scope of the application.
     *
     * From the docs:
     *
     * Defines the navigation scope of this web application's application context. This basically restricts
     * what web pages can be viewed while the manifest is applied. If the user navigates the application
     * outside the scope, it returns to being a normal web page.
     *
     * @param string $value
     * @return $this
     */
    public function scope( $value )
    {
        return $this->set( 'scope', $value );
    }

    /**
     * Set the application's start URL
     *
     * @param string $value
     * @return $this
     */
    public function startUrl( $value )
    {
        return $this->set( 'start_url', $value );
    }

    /**
     * Set the application's icons
     *
     * @param array $icons
     * @return $this
     */
    public function icons( array $icons )
    {
        $this->set( 'icons', $icons);
        return $this;
    }

    /**
     * Add an icon
     *
     * @param Icon $icon
     * @return $this
     */
    public function icon( Icon $icon )
    {
        if ( ! isset( $this->data[ 'icons' ] ) ) {
            $this->set( 'icons', [ ] );
        }
        $this->data[ 'icons' ][ ] = $icon;
        return $this;
    }

    /**
     * Set the display type
     *
     * @param string $value
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function display( $value )
    {
        $allowed = [ 'fullscreen', 'standalone', 'minimal-ui', 'browser' ];
        if ( ! in_array( $value, $allowed ) ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The display type must be one of %s',
                    implode( ', ', $allowed )
                )
            );
        }
        return $this->set( 'display', $value );
    }

    /**
     * Sets the display type to "fullscreen"; it's basically just syntactic sugar for ->display( 'fullscreen ')
     *
     * @return $this
     */
    public function fullscreen( )
    {
        return $this->display( 'fullscreen' );
    }

    /**
     * Sets the display type to "standlone"; it's basically just syntactic sugar for ->display( 'standalone ')
     *
     * @return $this
     */
    public function standalone( )
    {
        return $this->display( 'standalone' );
    }

    /**
     * Sets the display type to "minimal-ui"; it's basically just syntactic sugar for ->display( 'minimal-ui ')
     *
     * @return $this
     */
    public function minimalUI( )
    {
        return $this->display( 'minimal-ui' );
    }

    /**
     * Sets the display type to "browser"; it's basically just syntactic sugar for ->display( 'browser ')
     *
     * @return $this
     */
    public function browser( )
    {
        return $this->display( 'browser' );
    }

    /**
     * Set the orientation
     *
     * @param string $value
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function orientation( $value )
    {
        $allowed = [
            'any',
            'natural',
            'landscape',
            'landscape-primary',
            'landscape-secondary',
            'portrait',
            'portrait-primary',
            'portrait-secondary',
        ];
        if ( ! in_array( $value, $allowed ) ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The orientation must be one of %s',
                    implode( ', ', $allowed )
                )
            );
        }
        return $this->set( 'orientation', $value );
    }

    /**
     * Sets the orientation to "natural"; it's basically just syntactic sugar for ->orientation( 'natural' )
     *
     * @return $this
     */
    public function natural( )
    {
        return $this->orientation( 'natural' );
    }

    /**
     * Sets the orientation to "portrait"; it's basically just syntactic sugar for ->orientation( 'portrait' )
     *
     * @return $this
     */
    public function portrait( )
    {
        return $this->orientation( 'portrait' );
    }

    /**
     * Sets the orientation to "portrait-primary"; it's basically just syntactic sugar for ->orientation( 'portrait-primary' )
     *
     * @return $this
     */
    public function portraitPrimary( )
    {
        return $this->orientation( 'portrait-primary' );
    }

    /**
     * Sets the orientation to "portrait-secondary"; it's basically just syntactic sugar
     * for ->orientation( 'portrait-secondary' )
     *
     * @return $this
     */
    public function portraitSecondary( )
    {
        return $this->orientation( 'portrait-secondary' );
    }

    /**
     * Sets the orientation to "landscape"; it's basically just syntactic sugar for ->orientation( 'landscape' )
     *
     * @return $this
     */
    public function landscape( )
    {
        return $this->orientation( 'landscape' );
    }

    /**
     * Sets the orientation to "landscape-primary"; it's basically just syntactic sugar
     * for ->orientation( 'landscape-primary' )
     *
     * @return $this
     */
    public function landscapePrimary( )
    {
        return $this->orientation( 'landscape-primary' );
    }

    /**
     * Sets the orientation to "landscape-secondary"; it's basically just syntactic sugar
     * for ->orientation( 'landscape-secondary' )
     *
     * @return $this
     */
    public function landscapeSecondary( )
    {
        return $this->orientation( 'landscape-secondary' );
    }

    /**
     * Sets the orientation to "any"; it's basically just syntactic sugar
     * for ->orientation( 'any' )
     *
     * @return $this
     */
    public function anyOrientation( )
    {
        return $this->orientation( 'any' );
    }

    /**
     * Set the text direction; this applies to things like the name and short name.
     *
     * @param string $value
     * @return $this
     */
    public function textDirection( $value )
    {
        $allowed = [ 'ltr', 'rtl', 'auto' ];
        if ( ! in_array( $value, $allowed ) ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The direction must be one of %s',
                    implode( ', ', $allowed )
                )
            );
        }
        return $this->set( 'dir', $value );
    }

    /**
     * Set the language; this applies to things like the name and short name.
     *
     * @param string $value
     * @return $this
     */
    public function language( $value )
    {
        return $this->set( 'lang', $value );
    }

    /**
     * Set the GCM Sender ID
     *
     * @param string $value
     * @return $this
     */
    public function gcmSenderId( $value )
    {
        return $this->set( 'gcm_sender_id', $value );
    }

    /**
     * Set the theme color
     *
     * @param string $value
     * @return $this
     */
    public function themeColor( $value )
    {
        return $this->set( 'theme_color', $value );
    }

    /**
     * Set the background color
     *
     * @param string $value
     * @return $this
     */
    public function backgroundColor( $value )
    {
        return $this->set( 'background_color', $value );
    }

    /**
     * Set the related applications
     *
     * @param array $applications
     * @return $this
     */
    public function relatedApplications( array $applications )
    {
        return $this->set( 'related_applications', $applications );
    }

    /**
     * Add a related application
     *
     * @param RelatedApplication $application
     * @return $this
     */
    public function addRelatedApplication( RelatedApplication $application )
    {
        if ( ! isset( $this->data[ 'related_applications' ] ) ) {
            $this->set( 'related_applications', [ ] );
        }
        $this->data[ 'related_applications' ][ ] = $application;
        return $this;
    }

    /**
     * Specifies a boolean value that hints for the user agent to indicate to the user that the specified
     * related applications are available, and recommended over the web application.
     *
     * @param bool $value
     * @return $this
     */
    public function preferRelatedApplications( $value = true )
    {
        return $this->set( 'prefer_related_applications', $value );
    }

    /**
     * Specifies what data is used to create a JSON representation of this icon
     *
     * @return array
     */
    public function jsonSerialize( )
    {
        $data = $this->data;

        if ( isset( $this->data[ 'icons' ] ) && count( $this->data[ 'icons' ] ) ) {
            $data[ 'icons' ] = array_map(
                function( Icon $icon ) {
                    return $icon->jsonSerialize( );
                },
                $this->data[ 'icons' ]
            );
        }
        if ( isset( $this->data[ 'related_applications' ] ) && count( $this->data[ 'related_applications' ] ) ) {
            $data[ 'related_applications' ] = array_map(
                function( RelatedApplication $application ) {
                    return $application->jsonSerialize( );
                },
                $this->data[ 'related_applications' ]
            );
        }
        return $data;
    }

    /**
     * Convert this manifest to JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson( $options = 0 )
    {
        return json_encode( $this, $options );
    }

    /**
     * Save this manifest to a file, at the specified location.
     *
     * @param bool $prettyPrint
     * @return int
     */
    public function save( $filepath, $prettyPrint = false )
    {
        return file_put_contents(
            $filepath,
            $this->toJson( $prettyPrint ? JSON_PRETTY_PRINT : 0 )
        );
    }

}