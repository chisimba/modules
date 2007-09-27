<?php

// ----------------------------------------------------------------------------------
// Class: N3Serializer
// ----------------------------------------------------------------------------------

/**
 * PHP Notation3 Serializer 
 * 
 * This class serialises models to N3 Syntax.
 * 
 * Supported N3 features: 
 * <ul>
 *   <li>Using [ ] for blank nodes, or _: if nescessary</li>
 *   <li>Literal datytype- and xmlLanguageTag support</li> 
 * </ul>
 * 
 * Un-supported N3 Features include: 
 * <ul>
 *   <li>Reification</li>
 * </ul>
 *
 *
 * TODO: * added namespace prefixes are persisent...
 *
 * @author Gunnar AA. Grimnes <ggrimnes@csd.abdn.ac.uk>, Daniel Westphal <mail@d-westphal.de>
 * @version $Id$
 * @package syntax
 * @access public
 **/

define('MAGIC_STRING', '~~~');

class N3Serializer extends Object_rap {

  var $debug; 

  var $prefixes; 

  var $done;  // keeps track of already serialized resources
  var $resourcetext; 
  var $resourcetext_taken; 
  var $model;
  var $res; 
  var $anon;

   /**
    * Constructor
    *
    * @access   public
    */
  function N3Serializer() { 
    $this->debug=FALSE;
  }


  /** 
   * Adds a new namespace prefix to use. 
   * Unknown namespaces will become ns0, ns1 etc. 
   * @access public
   * @param string $s
   * @returns void 
   **/

  function addNSPrefix( $ns, $prefix) { 
    $this->prefixes[$ns]=$prefix; 
  }

  /**
   * Serializes a model to N3 syntax.
   *
   * @param     object Model $model
   * @return    string
   * @access    public
   */
  function & serialize(&$m) { 

    if (is_a($m, 'DbModel')) 
    	$m=$m->getMemModel();
    
    $this->reset();
    $this->model=$m;
    $this->res=""; 
    
    // copy default namespaces
    global $default_prefixes;
    foreach($default_prefixes as $prefix => $namespace)
            $this->addNSPrefix($namespace,$prefix);
    
    $nps= $this->model->getParsedNamespaces(); 
    if($nps!=false){
    	foreach($nps as $uri => $prefix){
    		$this->addNSPrefix($uri,$prefix);
    	}
    }
    
    $namespaces=array();
    $count=array();
    $resources=array(); 
    foreach ($this->model->triples as $t) { 
      $s=$t->getSubject();
      if ( is_a($s, 'Resource')) 
		$namespaces[$s->getNamespace()]=1;

      $p=$t->getPredicate();
      if ( is_a($p, 'Resource')) 
		$namespaces[$p->getNamespace()]=1;

      $o=$t->getObject();
      if ( is_a($o, 'Resource')) 
		$namespaces[$o->getNamespace()]=1;
     
      $uri=$s->getURI();
      
      if (isset($count[$uri])) { 
		$count[$uri]++;
      } else { 	
		$count[$uri]=0;
		$resources[$uri]=$s;
      }
    }

    if (!HIDE_ADVERTISE) 
    	$this->res .= '# Generated by N3Serializer.php from RDF RAP.'.LINEFEED
    	             .'# http://www.wiwiss.fu-berlin.de/suhl/bizer/rdfapi/index.html'
    	             .LINEFEED.LINEFEED;

    $this->doNamespaces($namespaces); 

    $this->res.=LINEFEED.LINEFEED;

    arsort($count);
    
    foreach ( $count as $k=>$v) { 
      $this->doResource($resources[$k]); 
      //      $this->res.=" .\n";
    }

    $c=0;
    foreach ( $this->resourcetext as $r=>$t) { 

      if ( preg_match_all('/'.MAGIC_STRING.'([^ ]+)'.MAGIC_STRING.'/', $t, $ms, PREG_SET_ORDER)) {

	foreach($ms as $mseach) { 
	  $rp=$this->resourcetext[$mseach[1]];
	  $t=preg_replace('/'.MAGIC_STRING.$mseach[1].MAGIC_STRING.'/', $rp, $t);
	}
      }

      if ($this->debug) $this->res.=$c.': ';
      if ( !( isset($this->resourcetext_taken[$r]) && $this->resourcetext_taken[$r]>0) ) 
	     $this->res.=$t.' .'.LINEFEED; 
      else if ( $this->debug ) 
         $this->res.=' Skipping : '.$t.LINEFEED;
      $c++;
    } 

//     $max=-1111;
//     $maxkey="";
//     foreach ($count as $k=>$c) { 
//       if ( $c>$max) { $maxkey=$k; $max=$c; }
//     }
    
//     if ($this->debug) { 
//       print "$maxkey is subject of most triples! ($max) \n"; 
//     }

    return $this->res;
  }

/**
 * Serializes a model and saves it into a file.
 * Returns FALSE if the model couldn't be saved to the file.
 *
 * @param     object MemModel $model
 * @param     string $filename
 * @return    boolean
 * @access    public
 */
 function  saveAs(&$model, $filename) {

   // serialize model
   $n3 = $this->serialize($model);

   // write serialized model to file
   $file_handle = @fopen($filename, 'w');
   if ($file_handle) {
      fwrite($file_handle, $n3);
      fclose($file_handle);
      return TRUE;
   }else{
      return FALSE;
   };
 }
  

  /* ==================== Private Methods from here ==================== */


/**
 * Readies this object for serializing another model
 * @access private
 * @param void
 * @returns void 
 **/
  function reset() {
    $this->anon=0;
    $this->done=array();
    $this->resourcetext_taken=array();
    $this->resourcetext=array();
    $this->res='';
    $this->model=NULL;
    unset($this->prefixes);
  }

/**
 * Makes ns0, ns1 etc. prefixes for unknown prefixes. 
 * Outputs @prefix lines. 
 * @access private
 * @param array $n
 * @returns void 
 **/
  function doNamespaces(&$n) { 
    $c=0;
    foreach ($n as $ns => $nonsense) { 
      if ( !$ns ) continue;  
      if ( isset($this->prefixes[$ns]) ) {
	$p=$this->prefixes[$ns];
      } else { 
	$p='ns'.$c;
	$this->prefixes[$ns]=$p;
	$c++;
      }
      $this->res.="@prefix $p: <".$ns.'> .'.LINEFEED;
    }
  }

/**
 * Fill in $resourcetext for a single resource. 
 * Will recurse into Objects of triples, but should never look ? (really?) 
 * @access private
 * @param object Resource $r
 * @returns boolean
 **/

  function doResource(&$r) { 
    //    print $r->getURI(); 

    $ts=$this->model->find($r, null, null);
    if (count($ts->triples)==0) return; 
	
    $out="";

    if ( isset($this->done[$r->getURI()]) && $this->done[$r->getURI()] ) {
      if ( is_a($r, 'BlankNode')) { 
      
      	if ( $this->resourcetext_taken[$r->getURI()] == 1) { 
	  		//Oh bother, we must use the _:blah construct. 
	  		$a=$this->resourcetext[$r->getURI()];
	  		$this->resourcetext[$r->getURI()]='_:anon'.$this->anon;
	  		$this->resourcetext['_:anon'.$this->anon]=$this->fixAnon($a, '_:anon'.$this->anon);
	  		$this->resourcetext_taken[$r->getURI()]=2; 
	  		$this->anon++;
		}

      }
      return false; 
    }

    $this->done[$r->getURI()]=TRUE;

    if ( is_a($r, 'Resource') ) {

      if ( is_a($r, 'BlankNode') ) { 

      	 //test, if this blanknode is referenced somewhere
      	$rbn=$this->model->find(null, null, $r);

      	if (count($rbn->triples)>0 | !N3SER_BNODE_SHORT) {
      		$out.='_:'.$r->getLabel();
      	} else {
      		$out.='[ ';
      	};	
      } else { 

        $this->doURI($r, $out);

      };
    };
  
    usort($ts->triples, 'statementsorter');
    $lastp='';

    $out.=' ';

    foreach ($ts->triples as $t) { 
      
      $p=$t->getPredicate(); 
      
      if ($p == $lastp) {
	$out.=' , '; 
      } else { 
	if ($lastp!='') $out.=' ; '; 
	$this->doURI($p, $out);
	$lastp=$p;
      }

      $out.=' ';
      
      $o=$t->getObject();
      
      if ( is_a($o, 'Literal')) { 
	$l=$o->getLabel();
	if ( strpos($l, LINEFEED) === FALSE ) { 
	  $out.="\"$l\"";
	} else { 
	  $out.="\"\"\"$l\"\"\"";	  
	}
	if ( $o->getLanguage()!='' ) { 
	  $out.='@'.$o->getLanguage();
	}
	if ( $o->getDatatype()!='' ) { 
	  $out.='^^<'.$o->getDatatype().'>';
	}
	
      } 
      
      if (is_a($o, 'Resource')) {
	if ($this->debug) print 'Doing object: '.$o->getURI().LINEFEED;
	if ( is_a($o,'BlankNode')) {

//	$this->doResource($o);
//	  $out.=MAGIC_STRING.$o->getURI().MAGIC_STRING; #$this->resourcetext[$o->getURI()];
//	  $this->resourcetext_taken[$o->getURI()]=1;

	$out .='_:'.$o->getLabel();
	} else { 

	  $this->doURI($o, $out);
	}
      }
    }

	if (isset($rbn) && !count($rbn->triples)>0 && N3SER_BNODE_SHORT) {$out.=' ] ';};
    $this->resourcetext[$r->getURI()]=$out; 

    return TRUE;
  }

  /** 
   * Format a single URI
   * @param string $s
   * @access private
   * @return void
   **/
  function doURI(&$r, &$out) { 
    if ( $r->getURI()=='http://www.w3.org/1999/02/22-rdf-syntax-ns#type') {
      $out.='a';
      return; 
    }
    if ($r->getNamespace()!='') {
      $out.=$this->prefixes[$r->getNamespace()].':'.$r->getLocalName();
    } else { 
      //Will this ever happen? 
      $out.=$r->getURI();
    }
  }

  
  /** 
   * Fix the resourcetext for a blanknode where the _: construct was used
   * @param string $s
   * @param string $a
   * @access private
   * @return void
   **/
  function fixAnon($t,$a) { 
    $t=preg_replace("/( \] $|^\[ )/", '', $t); 

    return $a.$t;
  }

}

?>