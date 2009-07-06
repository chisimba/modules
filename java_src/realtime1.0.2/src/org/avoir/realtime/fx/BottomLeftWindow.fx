/*
 * ProgressBar.fx
 *
 * Created on 2009/07/02, 7:39:17 PM
 */

package org.avoir.realtime.fx;

import javafx.scene.Node;
import javafx.scene.CustomNode;
import javafx.scene.Group;
import javafx.scene.layout.Panel;
import javafx.scene.shape.Rectangle;
import javafx.scene.paint.Color;
import javafx.stage.Screen;
import javafx.scene.effect.GaussianBlur;
import javafx.scene.paint.LinearGradient;
import javafx.scene.paint.Stop;

public class BottomLeftWindow extends CustomNode {
    var swidth= Screen.primary.visualBounds.width;
    var sheight= Screen.primary.visualBounds.height;
    public var mainScreen:MainScreen;
    public var chatRoomNodes:Node[];
    var window : Panel=Panel{
       content:[
           Rectangle{
               x:0;
               y:0
               //stroke:Color.WHITE;
                fill: LinearGradient {
                        startX: 0 endX: 0
                        stops: [
                            Stop { color: Color.LIGHTGRAY offset: 0 },
                            Stop { color: Color.WHITE offset: 1 }
                        ]  }
              //strokeWidth:1.5
               effect: GaussianBlur{ radius: 5 }
               arcHeight:10;
               arcWidth:10;

               width:swidth/4;
               height:300
               }
        
     ]
    }


    override function create() : Node {
        blocksMouse = true;
        
        Group {
          content:bind chatRoomNodes;
        }
    }
}
