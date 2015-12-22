<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - plaintext()
 * - format()
 * - search()
 * - sentences()
 * - chars()
 * - get()
 * Classes list:
 * - Wiqi
 */
namespace Likey\Wiqi;

class Wiqi
{
    
    protected $wiqiControl;
    
    public function __construct()
    {
        $this->wiqiControl = new WiqiControl;
    }

    public function query( $query )
    {
        if (is_string($query)){
            $this->wiqiControl->setTitles( $query );
        } else {
            $this->wiqiControl->setPageids( $query );
        }
        
        return $this;
    }
    
    public function plaintext()
    {
        $this->wiqiControl->setProp('extracts',[['plaintext',true]]);
        
        return $this;
    }
    
    public function format( $format )
    {
        $this->wiqiControl->setFormat( $format );
        
        return $this;
    }

    public function brief()
    {
        $this->wiqiControl->setProp('extracts',[['sentences',1],['plaintext',true]]);
        $this->wiqiControl->setProp('pageimages',[['prop','original']]);
        $this->wiqiControl->setProp('info',[['prop','url']]);

        return $this;
    }

    public function withImage()
    {
        $this->wiqiControl->setProp('pageimages',[['prop','original']]);

        return $this;
    }
    
    public function sentences( $sentences )
    {
        $this->wiqiControl->setProp('extracts',[['sentences',$sentences]]);
        
        return $this;
    }
    
    public function chars( $chars )
    {
        $this->wiqiControl->setProp('extracts',[['chars',$chars]]);
        
        return $this;
    }

    public function prop($prop, $options = [])
    {
        $this->wiqiControl->setProp( $prop, $options );

        return $this;
    }

    public function limit( $limit )
    {
        $this->wiqiControl->setLimits($limit);

        return $this;
    }
    
    public function source()
    {
        return $this->wiqiControl->getSourceResponse();
    }

    public function get()
    {
        return $this->wiqiControl->get();
    }

    public function getDis()
    {
        return $this->wiqiControl->getDis();
    }
}
