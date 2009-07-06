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
import javafx.scene.control.Label;
import javafx.scene.control.TextBox;
import javafx.scene.layout.VBox;
import javafx.scene.shape.Rectangle;
import javafx.scene.paint.Color;
import javafx.scene.control.Button;
import javafx.scene.input.MouseEvent;

import org.avoir.realtime.startup.Startup;

import javafx.stage.Screen;

public class LoginWindow extends CustomNode {
public var swidth= Screen.primary.visualBounds.width;
    var usernameLabel:Label=Label{
        layoutX:25;
        layoutY:10
        text:"Username";
        textFill:Color.WHITE;
    }
    public var mainScreen:MainScreen;
    var usernameField:TextBox=TextBox{
        layoutX:25;
        layoutY:40
        width:150;
    }
    var passwordLabel:Label=Label{
        text:"Password";
        layoutX:25;
        layoutY:70
       textFill:Color.WHITE;
    }

    var passwordField:TextBox=TextBox{
        layoutX:25;
        layoutY:100
        width:150;
    }


 
var loginButton:Button=Button{

        text:"Login"
        layoutX:25;
        layoutY:150
        onMouseClicked:function (me: MouseEvent) {
          var args: String[]=[
              "localhost",
              "5222",
              "localhost:7070/red5",
              "default",
              usernameField.text,
              "/",
              "yes",
              "12",
              "popo",
              passwordField.text,
              "davidwaf@gmail.com",
              "localhost/presentation",
              "jumped",
              "true",
              "1 "
          ];
         var startup:Startup=new Startup(mainScreen,args);

          mainScreen.headingText.text="Loading ...";
          mainScreen.progressTimeline.playFromStart();
          //startup.start(args);
       }
    }

    var window : Panel=Panel{
       content:[
           Rectangle{
               x:10;
               y:10
               stroke:Color.WHITE;
               arcHeight:10;
               arcWidth:10;

               width:(swidth/4)-20;
               height:200
               }
           VBox{
               translateX:15,
               translateY:10,
               content:[
                   usernameLabel,
                   usernameField,
                   passwordLabel,
                   passwordField,
                   loginButton
                   ]
           }
     ]
    }


    override function create() : Node {
        blocksMouse = true;

        Group {

            content: [ window]
        }
    }
}
