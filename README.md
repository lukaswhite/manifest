# Manifest

A simple PHP library for programmatically generating [Web App Manifests](https://developer.mozilla.org/en-US/docs/Web/Manifest).

## Simple Example

```php
$manifest = new Manifest( );
$manifest->name( 'My Awesome App' )
    ->shortName( 'AwesomeApp' )
    ->description( 'Just testing' );
    
print $manifest->toJson( );
// or
$manifest->save( 'public/manifest.json' );    
```

### In Laravel

```php
Route::get( 'manifest.json', function( ) {
    $manifest = new Manifest( );
    $manifest->name( Config::get( 'app.name' ) )
        ->description( trans( 'site.meta.description' ) );
        
    return response( )->json( $manifest );
} );
```

## A Longer Example

Here's an example taken from [the Mozilla documentation on web app manifests](https://developer.mozilla.org/en-US/docs/Web/Manifest):

```json
{
  "name": "HackerWeb",
  "short_name": "HackerWeb",
  "start_url": ".",
  "display": "standalone",
  "background_color": "#fff",
  "description": "A simply readable Hacker News app.",
  "icons": [{
    "src": "images/touch/homescreen48.png",
    "sizes": "48x48",
    "type": "image/png"
  }, {
    "src": "images/touch/homescreen72.png",
    "sizes": "72x72",
    "type": "image/png"
  }, {
    "src": "images/touch/homescreen96.png",
    "sizes": "96x96",
    "type": "image/png"
  }, {
    "src": "images/touch/homescreen144.png",
    "sizes": "144x144",
    "type": "image/png"
  }, {
    "src": "images/touch/homescreen168.png",
    "sizes": "168x168",
    "type": "image/png"
  }, {
    "src": "images/touch/homescreen192.png",
    "sizes": "192x192",
    "type": "image/png"
  }],
  "related_applications": [{
    "platform": "play",
    "url": "https://play.google.com/store/apps/details?id=cheeaun.hackerweb"
  }]
}
```

And here is how to build that using this library:

```php
$manifest = new Manifest( );
$manifest
    ->name( 'HackerWeb' )
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
            'play', 
            'https://play.google.com/store/apps/details?id=cheeaun.hackerweb'
        )
    );
```

## But...why?

I created this because I was a developing an application designed to be deployed multiple times with different configurations; things like the application name would be different across multiple installations, so it made sense to be able to control the contents of the manifest from the codebase.

Suppose you're building a CMS-driven application; chances are anything from the metadata (the name and description as it appears when adding an app to a homescreen) to the colour-scheme are probably database-driven; this allows you to do just that.  
  
There are a number of other reasons you might want to use this approach:
  
* Personal preference; maybe you prefer writing out JSON files by hand, perhaps you prefer the programmatic approach
* It helps remove duplication; suppose information such as a your application name are in config or a database &mdash; this allows you to avoid duplicating that information
* Some properties really belong in config; suppose you need to set a sender ID for Google Clound Messaging. It makes sense to keep that in config, and then you can simply inject it into your manifest as required.
* i18n; want to translate your app description into multiple languages? You can using this approach.
* You can integrate it into your deployment process; if you're automating, say, your application icon generation (who wants to do that resizing by hand?), this approach makes that easier to automate.

**More documentation to follow**