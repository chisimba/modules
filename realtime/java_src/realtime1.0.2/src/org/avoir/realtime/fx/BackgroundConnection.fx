/*
 * BackgroundConnection.fx
 *
 * Created on 2009/07/05, 11:50:47 AM
 */

package org.avoir.realtime.fx;

import javafx.async.JavaTaskBase;

import javafx.async.RunnableFuture;
import org.avoir.realtime.startup.Startup;

/**
 * @author david
 */

public class BackgroundConnection extends JavaTaskBase{
var startup:Startup;
public var args:String[];
public var mainScreen:MainScreen;

override function create(): RunnableFuture {
    startup=new Startup(mainScreen,args);
   
}

override public function start():Void {
        super.start();
}
}
