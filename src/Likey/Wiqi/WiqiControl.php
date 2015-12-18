<?php
/**
 * Class and Function List:
 * Function list:
 * - __toString()
 * - getSourceResponse()
 * - get()
 * - getQueryUrl()
 * - getQueryString()
 * - getFormat()
 * - setFormat()
 * - setTitles()
 * - setPageids()
 * - getTitles()
 * - setProp()
 * - getProps()
 * - setLimits()
 * - errors()
 * - isDisambig()
 * - cleanResult()
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
                'sentences' => ['type' => 'int'],
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
                'prop' => ['type' => 'string'],
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
    
    protected $sourceResponse = null;
    protected $hasDis = false;
    protected $count = false;
    
    public function __toString()
    {
        return $this->get();
    }
    
    public function getSourceResponse()
    {
        return $sourceResponse = file_get_contents( $this->getQueryUrl() );
    }
    
    public function get()
    {
        if( $this->getFormat() == 'json' ) {
            return $this->cleanResult();
        } 
        else if( $sourceResponse ) {
            return $sourceResponse;
        } 
        else {
            return $this->getSourceResponse();
        }
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
    
    public function setTitles( $titles )
    {
        $title = ucfirst( $titles );
        $this->queryOptions["titles"] = $title;

        $this->checkDis();

        return $this->queryOptions["titles"];
    }
    
    public function setPageids( $ids )
    {
        return $this->queryOptions["pageids"] = $ids;
    }
    
    public function getTitles()
    {
        return( $this->queryOptions["titles"] ? $this->queryOptions["titles"] : false );
    }
    
    public function setProp( $prop, $options )
    {
        if( in_array( $prop, array_keys( $this->propOptions ) ) ) {
            if( empty( $this->queryOptions['prop'] ) ) {
                $this->queryOptions['prop'] = '';
            }
            if( strpos( $this->queryOptions['prop'], $prop ) === false ) {
                $this->queryOptions['prop'].=( empty( $this->queryOptions['prop'] ) ? '' : '|' ) . $prop;
            }
            
            foreach( $options as $option ) {
                if( in_array( $option[0], array_keys( $this->propOptions[$prop]['params'] ) ) ) {
                    $this->queryOptions[$this->propOptions[$prop]['prefix'] . $option[0]] = $option[1];
                }
            }
        }
    }
    
    public function getProps()
    {
        if( empty( $this->queryOptions['prop'] ) ) {
            return false;
        } 
        else {
            return explode( "|", $this->queryOptions['prop'] );
        }
    }
    
    public function setLimits( $limit )
    {
        $limit = ($this->hasDis ? $limit+1 : $limit);
        $this->count = $limit;
        if( $props = $this->getProps() ) {
            foreach( $props as $prop ) {
                if( !empty( $this->propOptions[$prop]['params']['limit'] ) ) {
                    $this->setProp( $prop, [['limit', $limit]] );
                }
                if( $prop == 'extracts' ) {
                    $this->queryOptions['exintro'] = true;
                }
            }
        }
        
        if( !empty( $this->queryOptions["titles"] ) ) {
            $this->queryOptions['generator'] = 'prefixsearch';
            $this->queryOptions['gpssearch'] = $this->queryOptions["titles"];
            $this->queryOptions['gpslimit'] = $limit;
        }
    }
    
    public function errors( $errors )
    {
        return array(
            'errors' => $errors
        );
    }
    
    public function checkDis()
    {
        
        if( $this->getTitles() ) {
            $results = json_decode( file_get_contents( $this->baseApi . '?action=query&format=json&prop=pageprops&ppprop=disambiguation&titles=' . $this->queryOptions["titles"] ), true );
            $results = array_values( $results['query']['pages'] );
            if( array_key_exists( 'pageprops', $results[0] ) ) {
                $this->hasDis = $results[0]['pageid'];
                return true;
            } 
            else {
                return false;
            }
        } 
        else {
            print_r( $this->errors( ['No Title to check. Disambiguation Checks only work with string searches.'] ) );
        }
    }

    public function getDis(){
        if ($this->hasDis){
            $tempTitles = $this->getTitles();
            unset($this->queryOptions['titles']);
            $this->setPageids($this->hasDis);
            $this->queryOptions['generator'] = 'links';
            if ($this->count){
                $this->queryOptions['gpllimit'] = $this->count;
            }
        }
        return $this->cleanResult();
        
    }

    public function getWithDis()
    {
       // if ()
    }
    
    public function cleanResult()
    {
        $source = json_decode( $this->getSourceResponse() , true );
        //print_r($source);
        $results = array_values( $source['query']['pages'] );
            foreach( $results as $key => $row ) {
                if ( !empty($results[$key]['extract']) && $this->hasDis){
                    if ( strrpos($results[$key]['extract'], 'could refer to a:') !== false ){
                        unset( $results[$key] );
                        continue;
                    } 
                }
                if( !empty( $row['index'] ) ) {
                    $sort[$key] = $row['index'];
                    unset( $results[$key]['index'] );
                }
                if( !empty($row['thumbnail']) ){
                    $results[$key]['image'] = $results[$key]['thumbnail']['original'];
                    unset( $results[$key]['thumbnail'] );
                }
                unset( $results[$key]['ns'] );
            }
            if (!$this->hasDis) {
                unset( $results[count($results)] );
            }
            if( !empty( $sort ) ) {
                array_multisort( $sort, SORT_ASC, $results );
            }
        
        return $results;
    }
}
