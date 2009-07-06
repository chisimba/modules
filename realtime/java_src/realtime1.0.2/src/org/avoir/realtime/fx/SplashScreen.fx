/*
 * SplashScreen.fx
 *
 * Created on 2009/07/05, 2:20:36 PM
 */

package org.avoir.realtime.fx;

import javafx.geometry.HPos;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.VBox;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import javafx.scene.text.TextAlignment;

import javafx.scene.paint.Color;


/**
 * @author david
 */

public class SplashScreen {
public var  content = VBox {
    nodeHPos:HPos.CENTER
     spacing: 4
     content: bind [

             ImageView {
             image: Image { url: "{__DIR__}images/logo.png"}
             //effect: reflection
             cache: true
         },
           Text {
           content:"Loading ..."
           opacity: 1.0
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
      // desksharefadein.playFromStart();
    }
    onMouseExited:function(event:MouseEvent):Void {
      //  desksharefadeout.playFromStart();
    }

}
}
