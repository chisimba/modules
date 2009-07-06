/*
 * ThumbImage.fx
 *
 * Created on 28 Jun 2009, 12:37:17 AM
 */

package org.avoir.realtime.fx;

import javafx.animation.transition.ScaleTransition;
import javafx.animation.transition.TranslateTransition;
import javafx.scene.CustomNode;
import javafx.scene.Group;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.Node;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;

import javafx.scene.text.Text;



/**
 * @author Rakesh Menon
 */

def x : Number [] = [  40, 120, 200, 280, 360, 440,520 ];
def percentage : Number [] = [ 40, 40, 40, 40, 40, 40, 40 ];

public class ThumbImage extends CustomNode {

    public var width = 150.0;
    public var height = 112.5;
    public var index : Integer on replace {
        translateX = x[index];
        scaleX = percentage[index]/100.0;
        scaleY = percentage[index]/100.0;
    }

    public var thumbIndex:Integer;

    public var carousel : Carousel;
 
    public var imageView = ImageView {
        x: 4
        y: 4
        fitWidth: bind (width - 6)
        fitHeight: bind (height - 6)
    };

    public var url : String on replace {
        if(url != null) {
            imageView.image = Image {
                url: "{url}"
                backgroundLoading: true
            }
        }
    }

    var scaleTransition : ScaleTransition;
    var translateTransition : TranslateTransition;
    var toIndex : Integer;
    var title:Text;
    override function create() : Node {

        scaleTransition = ScaleTransition {
            duration: 750ms
            node: this
            fromX: percentage[index]/100.0
            fromY: percentage[index]/100.0
            toX: percentage[toIndex]/100.0
            toY: percentage[toIndex]/100.0
            action:  function() {
                index = toIndex;
                if(index == 6) {
                    toFront();
                } else if(index == 3) {
                    carousel.selectedThumbImage = this;
                }
            }
        }

        translateTransition = TranslateTransition {
            duration: 750ms
            node: this
            fromX: x[index]
            toX: x[toIndex]
        }

        var borderRect = Rectangle {
            x: 0
            y: 0
            width: bind width
            height: bind height
            strokeWidth: 4.0
            stroke: Color.WHITE
            arcWidth: 10
            arcHeight: 10
        }
         title=Text{
             content: "Slide {thumbIndex}"
             fill: Color.WHITE
         }

        Group {
            content: [ borderRect, imageView ,title]
        }
    }

    public function next() {

        scaleTransition.stop();
        translateTransition.stop();

        toIndex = index + 1;
        if(index == 6) {
            toIndex = 0;
        }

        scaleTransition.fromX = percentage[index]/100.0;
        scaleTransition.fromY = percentage[index]/100.0;
        scaleTransition.toX = percentage[toIndex]/100.0;
        scaleTransition.toY = percentage[toIndex]/100.0;
        scaleTransition.play();

        translateTransition.fromX = x[index];
        translateTransition.toX = x[toIndex];
        translateTransition.play();
    }

     override var onMousePressed = function(e) {
         //JOptionPane.showMessageDialog(null,url);
       carousel.mainScreen.getWhiteboard().setSlideImage(url);
     }

} 
