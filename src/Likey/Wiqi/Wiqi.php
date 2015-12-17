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
        $this->wiqiControl = new wiqiControl;
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
    
    public function search( $search )
    {
        $this->wiqiControl->setTitles( $search );
        
        return $this;
    }
    
    public function sentences( $sentences )
    {
        $this->wiqiControl->setExtractsSentences( $sentences );
        
        return $this;
    }
    
    public function chars( $chars )
    {
        $this->wiqiControl->setExtractsChars( $chars );
        
        return $this;
    }

    public function prop($prop, $options)
    {
        $this->wiqiControl->setProp( $prop, $options );

        return $this;
    }
    
    public function get()
    {
        return $this->wiqiControl->get();
    }
}
