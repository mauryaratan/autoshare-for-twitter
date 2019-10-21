# Auto Tweet

> Automatically tweets the post title or custom message and a link to the post.

[![Support Level](https://img.shields.io/badge/support-active-green.svg)](#support-level) [![Release Version](https://img.shields.io/github/release/10up/autotweet.svg)](https://github.com/10up/autotweet/releases/latest) ![WordPress tested up to version](https://img.shields.io/badge/WordPress-v4.9.8%20tested-success.svg) [![MIT License](https://img.shields.io/github/license/10up/autotweet.svg)](https://github.com/10up/autotweet/blob/develop/LICENSE.md)

## Overview

**Disclaimer:** *TWITTER, TWEET, RETWEET and the Twitter logo are trademarks of Twitter, Inc. or its affiliates.*

**Note:** Posts and pages are supported by default. Developers can use the `tenup_autotweet_default_post_types` filter to change the default supported post types (for more, see #25).  The plugin namespace changed to just 'autotweet' as of version 1.0.0.

Custom post types can now be opted into autotweet features like so:

```php
function opt_my_cpt_into_autotweet() {
	add_post_type_support( 'my-cpt', 'autotweet' );
}
add_action( 'init', 'opt_my_cpt_into_autotweet' );
```

In addition, adding support while registering custom post types also works.  Post types are automatically set to auto-tweet. Future versions of this plugin could allow this to be set manually.

## Plugin Compatibility ##

### Distributor ###
When using with 10up's [Distributor plugin](https://github.com/10up/distributor), posts that are distributed will not auto-tweet if they are already tweeted from the origin site. Autotweet tracks posts that have been tweeted in post meta to avoid "double tweeting". To avoid this behavior, use the `dt_blacklisted_meta` filter to exclude the 'autotweet_twitter-status' meta value from being distrivuted :

```php
add_filter( 'dt_blacklisted_meta', function( $blacklisted_metas ) {
	$blacklisted_metas[] = 'autotweet_twitter-status';
	return $blacklisted_metas;
} )
```

## Requirements

* PHP 7.0+
* [WordPress](http://wordpress.org) 4.7+

## Installation
1. Upload the entire `/tenup-auto-tweet` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin
3. Register post type support for types that should be allowed to auto tweet. `add_post_type_support( 'post', 'autotweet' );`

## FAQs
### Does this plugin work with Gutenberg?
Yes, yes it does!  For more details on this, see #44.

## Support Level

**Active:** 10up is actively working on this, and we expect to continue work for the foreseeable future including keeping tested up to the most recent version of WordPress.  Bug reports, feature requests, questions, and pull requests are welcome.

## Changelog

A complete listing of all notable changes to Eight Day Week are documented in [CHANGELOG.md](https://github.com/10up/autotweet/blob/develop/CHANGELOG.md).

## Contributing

Please read [CODE_OF_CONDUCT.md](https://github.com/10up/autotweet/blob/develop/CODE_OF_CONDUCT.md) for details on our code of conduct, [CONTRIBUTING.md](https://github.com/10up/autotweet/blob/develop/CONTRIBUTING.md) for details on the process for submitting pull requests to us, and [CREDITS.md](https://github.com/10up/autotweet/blob/develop/CREDITS.md) for a listing of maintainers, contributors, and libraries for Auto Tweet.

## Like what you see?

<a href="http://10up.com/contact/"><img src="https://10updotcom-wpengine.s3.amazonaws.com/uploads/2016/10/10up-Github-Banner.png" width="850" alt="Work with us at 10up"></a>