/*
 * Carousel.fx
 *
 * Created on 28 Jun 2009, 12:38:03 AM
 */

package org.avoir.realtime.fx;


import javafx.scene.Cursor;
import javafx.scene.CustomNode;
import javafx.scene.effect.Reflection;
import javafx.scene.Group;
import javafx.scene.Node;




public var imageURL : String [] = [
    
];

public class Carousel extends CustomNode {

    var thumbImage : ThumbImage[];
    var indexx:Integer=0;
    public var selectedThumbImage : ThumbImage;
    public var mainScreen:MainScreen;
     public function addImage(url:String){
          insert ThumbImage {
                index: indexx
                thumbIndex:indexx;
                url: url;
                carousel: this;
            } into thumbImage;
            indexx++;
     }

    public override function create(): Node {

        cursor = Cursor.HAND;

        selectedThumbImage = thumbImage[3];

        return Group {
            content: bind thumbImage

            effect: Reflection {
                fraction: 0.5
            }
        };
    }

    public function next() {
        for(tb in thumbImage) {
            
            tb.next();
        }
    }

    override var onMousePressed = function(e) {

        //next();
    }
}
