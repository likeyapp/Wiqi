<?php
/**
 * Class and Function List:
 * Function list:
 * - getSourceResponse()
 * - get()
 * - getQueryUrl()
 * - getQueryString()
 * - setTitles()
 * - setPageids()
 * - getTitles()
 * - setParams()
 * - setParam()
 * - setProp()
 * - getProps()
 * - setLimits()
 * - getLimits()
 * - errors()
 * - isDis()
 * - getDis()
 * - getWithDis()
 * - cleanResult()
 * Classes list:
 * - WiqiControl
 */
namespace Likey\Wiqi;

class WiqiControl
{
    
    protected $baseApi = 'http://en.wikipedia.org/w/api.php';
    
    protected $queryParamsRequired = array(
        'action' => 'query',
        'redirects' => true,
        'format' => 'json',
    );
    
    protected $queryParams = array();
    
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
    
    protected $wiqiRuntime = array(
        'hasDis' => false,
        'checkDis' => true,
        'resultsCount' => 10,
        'addedLimit' => 5,
    );
    
    public function getSourceResponse()
    {
        return file_get_contents( $this->getQueryUrl() );
    }
    
    public function get()
    {
        if( $this->isDis() ) {
            $disArray = $this->getDis();
            $returnArray = $disArray + $this->cleanResult();
            array_unique( $returnArray, SORT_REGULAR );
        } 
        else {
            $returnArray = $this->cleanResult();
        }
        return $returnArray;
    }
    
    public function getQueryUrl()
    {
        return $this->baseApi . '?' . $this->getQueryString();
    }
    
    public function getQueryString()
    {
        return http_build_query( array_merge( $this->queryParams, $this->queryParamsRequired ) );
    }
    
    public function setTitles( $titles )
    {
        return $this->queryParams['titles'] = ucfirst( $titles );
    }
    
    public function setPageids( $ids )
    {
        return $this->queryParams['pageids'] = $ids;
    }
    
    public function getTitles()
    {
        return( $this->queryParams['titles'] ? $this->queryParams['titles'] : null );
    }
    
    public function setParams( $qstr )
    {
        return parse_str( $qstr, $this->queryParams );
    }
    
    public function setParam( $name, $value )
    {
        return $this->setParams( $name . '=' . $value );
    }
    
    public function setProp( $prop, $options )
    {
        if( in_array( $prop, array_keys( $this->propOptions ) ) ) {
            if( empty( $this->queryParams['prop'] ) ) {
                $this->queryParams['prop'] = '';
            }
            if( strpos( $this->queryParams['prop'], $prop ) === false ) {
                $this->queryParams['prop'].=( empty( $this->queryParams['prop'] ) ? '' : '|' ) . $prop;
            }
            
            foreach( $options as $option ) {
                if( in_array( $option[0], array_keys( $this->propOptions[$prop]['params'] ) ) ) {
                    $this->queryParams[$this->propOptions[$prop]['prefix'] . $option[0]] = $option[1];
                }
            }
        }
    }
    
    public function getProps()
    {
        if( empty( $this->queryParams['prop'] ) ) {
            return false;
        } 
        else {
            return explode( '|', $this->queryParams['prop'] );
        }
    }
    
    public function setLimits( $limit = null )
    {
        if( $limit == null ) {
            $limit = $this->wiqiRuntime['resultsCount'];
        }
        
        $this->wiqiRuntime['resultsCount'] = $limit + $this->wiqiRuntime['addedLimit'];
        
        if( $props = $this->getProps() ) {
            foreach( $props as $prop ) {
                if( !empty( $this->propOptions[$prop]['params']['limit'] ) ) {
                    $this->setProp( $prop, [['limit', $this->wiqiRuntime['resultsCount']]] );
                }
                if( $prop == 'extracts' ) {
                    $this->queryParams['exintro'] = true;
                }
            }
        }
        
        if( !empty( $this->queryParams['titles'] ) ) {
            $this->queryParams['generator'] = 'prefixsearch';
            $this->queryParams['gpssearch'] = $this->queryParams['titles'];
            $this->queryParams['gpslimit'] = $this->wiqiRuntime['resultsCount'];
        }
    }
    
    public function getLimits()
    {
        return $this->wiqiRuntime['resultsCount'] - $this->wiqiRuntime['addedLimit'];
    }
    
    public function errors( $errors )
    {
        return array(
            'errors' => $errors
        );
    }
    
    public function isDis()
    {
        if( $this->wiqiRuntime['checkDis'] ) {
            if( $this->getTitles() ) {
                $results = json_decode( file_get_contents( $this->baseApi . '?action=query&format=json&prop=pageprops&ppprop=disambiguation&titles=' . rawurlencode( $this->queryParams['titles'] ) ), true );
                if( !empty( $results['query']['pages'] ) ) {
                    $results = array_values( $results['query']['pages'] );
                    if( array_key_exists( 'pageprops', $results[0] ) ) {
                        $this->wiqiRuntime['hasDis'] = $results[0]['pageid'];
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    public function getDis()
    {
        if( $this->isDis() ) {
            $tempOptions = $this->queryParams;
            unset( $this->queryParams['gpslimit'] );
            unset( $this->queryParams['gpssearch'] );
            unset( $this->queryParams['titles'] );
            $this->setPageids( $this->wiqiRuntime['hasDis'] );
            $this->queryParams['generator'] = 'links';
            $this->queryParams['redirects'] = true;
            
            //$this->queryParams['gpldir'] = 'descending';
            if( $this->wiqiRuntime['resultsCount'] ) {
                $this->queryParams['gpllimit'] = $this->wiqiRuntime['resultsCount'];
            }
            
            $disResults = $this->cleanResult();
            
            $this->queryParams = $tempOptions;
            
            return $disResults;
        }
    }
    
    public function getWithDis()
    {
        
        // if ()
        
        
    }
    
    public function cleanResult()
    {
        $redirectIndex = false;
        $this->setLimits();
        $source = json_decode( $this->getSourceResponse() , true );
        if( !empty( $source['query']['redirects'] ) ) {
            $redirects = array_column( $source['query']['redirects'], 'index' );
            $redirectIndex = array_combine( $redirects, array_keys( $redirects ) );
        }
        if( !empty( $source['query']['pages'] ) ) {
            $results = $source['query']['pages'];
            foreach( $results as $key => $row ) {
                
                // Remove Results that seem like Disambiguation Pages
                if( isset( $results[$key]['extract'] ) ) {
                    if( strrpos( $results[$key]['extract'], 'refer to' ) !== false ) {
                        unset( $results[$key] );
                        continue;
                    }
                }
                if( isset( $results[$key]['title'] ) ) {
                    if( strrpos( $results[$key]['title'], 'isambiguation' ) !== false ) {
                        unset( $results[$key] );
                        continue;
                    }
                }
                
                // Replace sub array with string to image
                if( isset( $results[$key]['thumbnail']['original'] ) ) {
                    $results[$key]['thumbnail'] = $results[$key]['thumbnail']['original'];
                }
                
                // Remove the namespace param
                if( isset( $results[$key]['ns'] ) ) {
                    unset( $results[$key]['ns'] );
                }
                
                // Setup Array to Sort by 'index' and add Redirect Titles
                if( isset( $results[$key]['index'] ) ) {
                    if( isset( $redirectIndex[$results[$key]['index']] ) ) {
                        $results[$key]['title'] = $source['query']['redirects'][$redirectIndex[$results[$key]['index']]]['from'] . " (" . $results[$key]['title'] . ")";
                    }
                    $sort[$key] = $results[$key]['index'];
                }
                
                // Remove the index
                unset( $results[$key]['index'] );
            }
            
            // Sort the array if it was organized
            if( isset( $sort ) ) {
                array_multisort( $sort, SORT_ASC, $results );
            }
            
            // Remove lame keys
            $results = array_values( $results );
            
            // Trim Results to Requested Length
            $results = array_slice( $results, 0, $this->getLimits() );
        } 
        else {
            $results = array(
                'query' => array(
                    'No Results.'
                )
            );
        }
        
        return $results;
    }
}
