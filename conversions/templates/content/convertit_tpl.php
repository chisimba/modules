<?php
// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');

// Initialize left column
$leftSideColumn = $this->leftMenu->show();
$rightSideColumn = NULL;
$middleColumn = NULL;
$this->objUser = $this->getObject('user', 'security');
$this->objNav = $this->getObject('navigate');

				
				if($goTo == NULL){
					$middleColumn = "CONVERSIONS MAIN PAGE.  This module provides simplified converters in the fields
                          of temperature, volume, distance and weight.
                      Please procced to the 'Go to' drop down list to select the converter that you require.";
				}
				elseif($goTo == "dist"){
					$middleColumn = $this->objNav->dist();
					$middleColumn .= $this->objNav->answer($value, $from, $to, $action);
				}
				elseif($goTo == "temp"){
					$middleColumn = $this->objNav->temp();
					$middleColumn .= $this->objNav->answer($value, $from, $to, $action);
				}
				elseif($goTo == "vol"){
					$middleColumn = $this->objNav->vol();
					$middleColumn .= $this->objNav->answer($value, $from, $to, $action);
				}
				elseif($goTo == "weight"){
					$middleColumn = $this->objNav->weight();
					$middleColumn .= $this->objNav->answer($value, $from, $to, $action);	
				}
$rightSideColumn = $this->objNav->conversionsFormNav();
//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>
