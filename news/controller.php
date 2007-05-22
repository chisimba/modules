<?php

class news extends controller
{
    
    /**
    *
    *
    */
    public function init()
    {
        $this->objNewsCategories = $this->getObject('dbnewscategories');
        $this->objNewsLocations = $this->getObject('dbnewslocations');
    }
    
    /**
    *
    *
    */
    public function dispatch($action)
    {
        $this->setLayoutTemplate('layout.php');
        switch($action)
        {
            case 'managecategories':
                return $this->manageCategories();
            case 'addcategory':
                return $this->addCategory();
            case 'managelocations':
                return $this->manageLocations();
            case 'addlocation':
                return $this->addLocation();
            case 'savelocation':
                return $this->saveLocation();
            case 'viewlocation':
                return $this->viewLocation($this->getParam('id'));
            default:
                return $this->newsHome();
        }
    }
    
    /**
    *
    *
    */
    private function newsHome()
    {
        return 'main.php';
    }
    
    /**
    *
    *
    */
    private function manageCategories()
    {
        $categories = $this->objNewsCategories->getCategories();
        $this->setVarByRef('categories', $categories);
        
        return 'managecategories.php';
    }
    
    private function addCategory()
    {
        $result = $this->objNewsCategories->addCategory($this->getParam('category'));
        
        if ($result == 'emptystring' || $result == 'categoryexists') {
            return $this->nextAction('managecategories', array('error'=>$result));
        } else {
            return $this->nextAction('managecategories', array('newrecord'=>$result));
        }
    }
    
    private function manageLocations()
    {
        $tree = $this->objNewsLocations->getLocationsTree('id');
        $this->setVarByRef('tree', $tree);
        
        return 'managelocations.php';
    }
    
    private function addLocation()
    {
        $tree = $this->objNewsLocations->getLocationsTree();
        $this->setVarByRef('tree', $tree);
        
        $this->setVar('mode', 'add');
        
        return 'addeditlocation.php';
    }
    
    private function saveLocation()
    {
        $location = $this->getParam('location');
        $parentLocation = $this->getParam('parentlocation');
        $locationType = $this->getParam('locationtype');
        $locationImage = $this->getParam('imageselect');
        $latitude = $this->getParam('latitude');
        $longitude = $this->getParam('longitude');
        $zoomlevel = $this->getParam('zoomlevel');
        $viewbounds = $this->getParam('viewbounds');
        $currentcenter = $this->getParam('currentcenter');
        
        //echo '<pre>';
        //print_r($_POST);
        // To do, check whether item exists on level - avoid duplication
        
        
        $result = $this->objNewsLocations->addLocation($location, $parentLocation, $locationType, $locationImage, $latitude, $longitude, $zoomlevel, $viewbounds, $currentcenter);
        
        if ($result == 'emptystring' || $result == 'parentdoesnotexist'){
            echo $result; // Fix Up Error Message
        } else {
            return $this->nextAction('viewlocation', array('id'=>$result));
        }
    }
    
    private function viewLocation($id)
    {
        $location = $this->objNewsLocations->getLocation($id);
        
        if ($location == FALSE) {
            return $this->nextAction(NULL, array('error'=>'locationdoesnotexist', 'requestedaction'=>'viewlocation', 'requestedid'=>$id));
        }
        
        $this->setVarByRef('location', $location);
        
        return 'viewlocation.php';
    }
    
}

?>