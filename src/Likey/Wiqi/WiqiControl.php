<?php
/**
 * Class and Function List:
 * Function list:
 * - __toString()
 * - get()
 * - getQueryUrl()
 * - getQueryString()
 * - getFormat()
 * - setFormat()
 * - setProp()
 * Classes list:
 * - WiqiControl
 */
namespace Likey\Wiqi;

class WiqiControl
{
    
    protected $baseApi = "http://en.wikipedia.org/w/api.php";
    
    protected $queryRequired = array(
        'action' => 'query',
    );
    
    protected $queryOptions = array(
        'titles' => null,
        'format' => 'json',
    );
    
    protected $formatOptions = array(
        'json',
        'xml',
        'php',
        'none',
        'jsonfm',
        'rawfm',
        'phpfm',
        'xmlfm',
    );
    
    protected $propOptions = array(
        'extracts' => array(
            'prefix' => 'ex',
            'params' => array(
                'chars' => ['type' => 'int'],
                'sentances' => ['type' => 'int'],
                'limit' => ['type' => 'int'],
                'intro' => ['type' => 'bool'],
                'plaintext' => ['type' => 'bool'],
            ) ,
        ) ,
        'pageimages' => array(
            'prefix' => 'pi',
            'params' => array(
                'thumbsize' => ['type' => 'int'],
                'limit' => ['type' => 'int'],
            ) ,
        ) ,
        
        /* Additional Options to add.
        'categories',
        'categoryinfo',
        'contributors',
        'coordinates',
        'deletedrevisions',
        'duplicatefiles',
        'extlinks',
        'fileusage',
        'flagged',
        'flowinfo',
        'globalusage',
        'imageinfo',
        'images',
        'info',
        'iwlinks',
        'langlinks',
        'links',
        'linkshere',
        'listmembership',
        'pageprops',
        'pageterms',
        'redirects',
        'revisions',
        'stashimageinfo',
        'templates',
        'transcludedin',
        'transcodestatus',
        'videoinfo',
        */
    );
    
    protected $generatorOptions = array(
        'links' => array(
            'prefix' => 'pl',
            'params' => array(
                'limit' => ['type' => 'int'],
            ) ,
        ) ,
        'prefixsearch' => array(
            'prefix' => 'ps',
            'params' => array(
                'search' => ['type' => 'string'],
                'limit' => ['type' => 'int'],
                'offset' => ['type' => 'int'],
            ) ,
        ) ,
        
        /* Additional Options to add.
        'allcategories',
        'alldeletedrevisions',
        'allfileusages',
        'allimages',
        'alllinks',
        'allpages',
        'allredirects',
        'allrevisions',
        'alltransclusions',
        'backlinks',
        'categories',
        'categorymembers',
        'contenttranslation',
        'contenttranslationsuggestions',
        'deletedrevisions',
        'duplicatefiles',
        'embeddedin',
        'exturlusage',
        'fileusage',
        'geosearch',
        'gettingstartedgetpages',
        'images',
        'imageusage',
        'iwbacklinks',
        'langbacklinks',
        'linkshere',
        'listpages',
        'oldreviewedpages',
        'pageswithprop',
        'protectedtitles',
        'querypage',
        'random',
        'recentchanges',
        'redirects',
        'revisions',
        'search',
        'templates',
        'transcludedin',
        'watchlist',
        'watchlistraw',
        */
    );
    
    public function __toString()
    {
        return $this->get();
    }
    
    public function get()
    {
        return file_get_contents( $this->getQueryUrl() );
    }
    
    public function getQueryUrl()
    {
        return $this->baseApi . '?' . $this->getQueryString();
    }
    
    public function getQueryString()
    {
        return http_build_query( array_merge( $this->queryOptions, $this->queryRequired ) );
    }
    
    public function getFormat()
    {
        return $this->queryOptions["format"];
    }
    
    public function setFormat( $format )
    {
        return( !in_array( $format, $this->formatOptions ) ? false : $this->queryOptions["format"] = $format );
    }
    
    public function setProp( $prop, $options )
    {
        if( in_array( $prop, array_keys( $this->propOptions ) ) ) {
            $this->queryOptions['props']+= '|' . $prop;
            
            foreach( $options as $option ) {
                if( in_array( $option[0], array_keys( $this->propOptions[$prop]['params'] ) ) ) {
                    $this->queryOptions[$this->propOptions[$prop]['params'][$option[0]]['prefix'] . $option[0]] = $option[1];
                }
            }
        }
    }
}
