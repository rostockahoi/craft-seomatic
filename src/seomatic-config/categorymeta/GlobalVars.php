<?php
/**
 * SEOmatic plugin for Craft CMS 3.x
 *
 * A turnkey SEO implementation for Craft CMS that is comprehensive, powerful,
 * and flexible
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

/**
 * @author    nystudio107
 * @package   Seomatic
 * @since     3.0.0
 */

return [
    '*' => [
        'mainEntityOfPage'        => 'WebPage',
        'seoTitle'                => '{category.title}',
        'siteNamePosition'        => 'before',
        'seoDescription'          => '',
        'seoKeywords'             => '',
        'seoImage'                => '',
        'seoImageDescription'     => '',
        'canonicalUrl'            => '{category.url}',
        'robots'                  => 'all',
        'ogType'                  => 'website',
        'ogTitle'                 => '{seomatic.meta.seoTitle}',
        'ogSiteNamePosition'      => 'none',
        'ogDescription'           => '{seomatic.meta.seoDescription}',
        'ogImage'                 => '{seomatic.meta.seoImage}',
        'ogImageDescription'      => '{seomatic.meta.seoImageDescription}',
        'twitterCard'             => 'summary_large_image',
        'twitterCreator'          => '{seomatic.site.twitterHandle}',
        'twitterTitle'            => '{seomatic.meta.seoTitle}',
        'twitterSiteNamePosition' => 'none',
        'twitterDescription'      => '{seomatic.meta.seoDescription}',
        'twitterImage'            => '{seomatic.meta.seoImage}',
        'twitterImageDescription' => '{seomatic.meta.seoImageDescription}',
    ],
];
