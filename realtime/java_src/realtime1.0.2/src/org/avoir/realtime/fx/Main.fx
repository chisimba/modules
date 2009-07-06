/*
 * Main.fx
 *
 * Created on 28 Jun 2009, 12:40:51 AM
 */

package org.avoir.realtime.fx;

import javafx.scene.paint.Color;
import javafx.scene.Scene;
import javafx.stage.Stage;
import javafx.stage.Screen;

import javafx.stage.StageStyle;


var swidth= Screen.primary.visualBounds.width;
var sheight= Screen.primary.visualBounds.height;
var splashScreen:SplashScreen=SplashScreen{}


function startConnecting(xargs: String[]){
var backgroundConnection:BackgroundConnection=BackgroundConnection{
    args:xargs;
    mainScreen:mainScreen;
}

backgroundConnection.start();
}

public var stage=
Stage {
    title: "Realtime Virtual Classroom 1.0.2"
     //x: Screen.primary.visualBounds.minX
     //y: Screen.primary.visualBounds.minY
     //width: (swidth)
     //height:(sheight)
     width:400
     height:300
     
     scene: Scene {
        fill: Color.BLACK;//Color.web("#2b2b2b");
        content: [
           // mainScreen.createView(),
           splashScreen.content

        ]
    }
 };
var mainScreen:MainScreen=MainScreen{
    stage:stage
    };

function run(args: String[]) {
 stage;
 startConnecting(args);
}
