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

namespace nystudio107\seomatic\helpers;

/**
 * @author    nystudio107
 * @package   Seomatic
 * @since     3.0.0
 */
class PullField
{
    // Constants
    // =========================================================================


    const PULL_TEXT_FIELDS = [
        ['fieldName' => 'seoTitle', 'seoField' => 'seoTitle'],
        ['fieldName' => 'siteNamePosition', 'seoField' => 'siteNamePosition'],
        ['fieldName' => 'seoDescription', 'seoField' => 'seoDescription'],
        ['fieldName' => 'seoKeywords', 'seoField' => 'seoKeywords'],
        ['fieldName' => 'seoImageDescription', 'seoField' => 'seoImageDescription'],
        ['fieldName' => 'ogTitle', 'seoField' => 'seoTitle'],
        ['fieldName' => 'ogSiteNamePosition', 'seoField' => 'siteNamePosition'],
        ['fieldName' => 'ogDescription', 'seoField' => 'seoDescription'],
        ['fieldName' => 'ogImageDescription', 'seoField' => 'seoImageDescription'],
        ['fieldName' => 'twitterTitle', 'seoField' => 'seoTitle'],
        ['fieldName' => 'twitterSiteNamePosition', 'seoField' => 'siteNamePosition'],
        ['fieldName' => 'twitterCreator', 'seoField' => 'twitterHandle'],
        ['fieldName' => 'twitterDescription', 'seoField' => 'seoDescription'],
        ['fieldName' => 'twitterImageDescription', 'seoField' => 'seoImageDescription'],
    ];

    const PULL_ASSET_FIELDS = [
        ['fieldName' => 'seoImage', 'seoField' => 'seoImage', 'transformName' => 'base'],
        ['fieldName' => 'ogImage', 'seoField' => 'seoImage', 'transformName' => 'facebook'],
        ['fieldName' => 'twitterImage', 'seoField' => 'seoImage', 'transformName' => 'twitter'],
    ];

    // Static Methods
    // =========================================================================


    /**
     * Set the text sources depending on the field settings
     *
     * @param string $elementName
     * @param        $globalsSettings
     * @param        $bundleSettings
     */
    public static function parseTextSources(string $elementName, &$globalsSettings, &$bundleSettings)
    {
        $objectPrefix = '';
        if (!empty($elementName)) {
            $elementName .= '.';
            $objectPrefix = 'object.';
        }
        foreach (self::PULL_TEXT_FIELDS as $fields) {
            $fieldName = $fields['fieldName'];
            $source = $bundleSettings[$fieldName.'Source'] ?? '';
            $sourceField = $bundleSettings[$fieldName.'Field'] ?? '';
            if (!empty($source)) {
                $seoField = $fields['seoField'];
                switch ($source) {
                    case 'sameAsSeo':
                        $globalsSettings[$fieldName] =
                            '{seomatic.meta.'.$seoField.'}';
                        break;

                    case 'sameAsSiteTwitter':
                        $globalsSettings[$fieldName] =
                            '{seomatic.site.'.$seoField.'}';
                        break;

                    case 'sameAsGlobal':
                        $globalsSettings[$fieldName] =
                            '';
                        break;

                    case 'fromField':
                        $globalsSettings[$fieldName] =
                            '{seomatic.helper.extractTextFromField('
                            .$objectPrefix.$elementName.$sourceField
                            .')}';
                        break;

                    case 'fromUserField':
                        $globalsSettings[$fieldName] =
                            '{seomatic.helper.extractTextFromField('
                            .$objectPrefix.$elementName.'author.'.$sourceField
                            .')}';
                        break;

                    case 'summaryFromField':
                        $globalsSettings[$fieldName] =
                            '{seomatic.helper.extractSummary(seomatic.helper.extractTextFromField('
                            .$objectPrefix.$elementName.$sourceField
                            .'))}';
                        break;

                    case 'keywordsFromField':
                        $globalsSettings[$fieldName] =
                            '{seomatic.helper.extractKeywords(seomatic.helper.extractTextFromField('
                            .$objectPrefix.$elementName.$sourceField
                            .'))}';
                        break;

                    case 'fromCustom':
                        break;
                }
            }
        }
    }

    /**
     * Set the image sources depending on the field settings
     *
     * @param $elementName
     * @param $globalsSettings
     * @param $bundleSettings
     * @param $siteId
     */
    public static function parseImageSources($elementName, &$globalsSettings, &$bundleSettings, $siteId = 0)
    {
        if (empty($siteId)) {
            $siteId = 0;
        }
        $objectPrefix = '';
        if (!empty($elementName)) {
            $elementName .= '.';
            $objectPrefix = 'object.';
        }
        foreach (self::PULL_ASSET_FIELDS as $fields) {
            $fieldName = $fields['fieldName'];
            $source = $bundleSettings[$fieldName.'Source'] ?? '';
            $ids = $bundleSettings[$fieldName.'Ids'] ?? [];
            $sourceField = $bundleSettings[$fieldName.'Field'] ?? '';
            if (!empty($source)) {
                $transformImage = $bundleSettings[$fieldName.'Transform'] ?? true;
                $seoField = $fields['seoField'];
                $transformName = $fields['transformName'];
                // Special-case Twitter transforms
                if ($transformName === 'twitter') {
                    $transformName = 'twitter-summary';
                    if (!empty($globalsSettings['twitterCard']) && $globalsSettings['twitterCard'] === 'summary_large_image') {
                        $transformName = 'twitter-large';
                    }
                }
                if ($transformImage) {
                    switch ($source) {
                        case 'sameAsSeo':
                            $seoSource = $bundleSettings[$seoField.'Source'] ?? '';
                            $seoIds = $bundleSettings[$seoField.'Ids'] ?? [];
                            $seoSourceField = $bundleSettings[$seoField.'Field'] ?? '';
                            if (!empty($seoSource)) {
                                switch ($seoSource) {
                                    case 'fromField':
                                        if (!empty($seoSourceField)) {
                                            $globalsSettings[$fieldName] = '{seomatic.helper.socialTransform('
                                                .$objectPrefix.$elementName.$seoSourceField.'.one()'
                                                .', "'.$transformName.'"'
                                                .', '.$siteId.')}';
                                        }
                                        break;
                                    case 'fromAsset':
                                        if (!empty($seoIds)) {
                                            $globalsSettings[$fieldName] = '{seomatic.helper.socialTransform('
                                                .$seoIds[0]
                                                .', "'.$transformName.'"'
                                                .', '.$siteId.')}';
                                        }
                                        break;
                                    default:
                                        $globalsSettings[$fieldName] = '{seomatic.meta.'.$seoField.'}';
                                        break;
                                }
                            }
                            break;
                        case 'fromField':
                            if (!empty($sourceField)) {
                                $globalsSettings[$fieldName] = '{seomatic.helper.socialTransform('
                                    .$objectPrefix.$elementName.$sourceField.'.one()'
                                    .', "'.$transformName.'"'
                                    .', '.$siteId.')}';
                            }
                            break;
                        case 'fromAsset':
                            if (!empty($ids)) {
                                $globalsSettings[$fieldName] = '{seomatic.helper.socialTransform('
                                    .$ids[0]
                                    .', "'.$transformName.'"'
                                    .', '.$siteId.')}';
                            }
                            break;
                    }
                } else {
                    switch ($source) {
                        case 'sameAsSeo':
                            $globalsSettings[$fieldName] = '{seomatic.meta.'.$seoField.'}';
                            break;
                        case 'fromField':
                            if (!empty($sourceField)) {
                                $globalsSettings[$fieldName] = '{'
                                    .$elementName.$sourceField.'.one().url'
                                    .'}';
                            }
                            break;
                        case 'fromAsset':
                            if (!empty($ids)) {
                                $globalsSettings[$fieldName] = '{{ craft.app.assets.assetById('
                                    .$ids[0]
                                    .', '.$siteId.').url }}';
                            }
                            break;
                    }
                }
            }
        }
    }
}
