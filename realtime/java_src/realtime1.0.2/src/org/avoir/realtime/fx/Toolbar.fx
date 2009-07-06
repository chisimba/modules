/*
 * Toolbar.fx
 *
 * Created on 26 Jun 2009, 11:02:46 PM
 */

package org.avoir.realtime.fx;

import javafx.animation.transition.FadeTransition;
import javafx.geometry.HPos;
import javafx.scene.effect.Reflection;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.VBox;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import javafx.scene.text.TextAlignment;

import javafx.scene.paint.Color;
import org.avoir.realtime.fx.MainScreen;

import javafx.scene.Cursor;

import javax.swing.JOptionPane;
/**
 * @author david
 */

public class Toolbar {


def reflection = Reflection { fraction: 0.33 }; // effects can be

public var mainScreen:MainScreen;
var label:Text; 
var newRoomLabel:Text;
var deskshareLabel:Text;
var changeroomLabel:Text;
var whiteboardLabel:Text;
var pointerLabel:Text;
var notepadLabel:Text;

var newRoomfadein:FadeTransition = FadeTransition {
    node: bind newRoomLabel
    fromValue: 0 toValue: 1.0
    duration: 100ms // fade-in is fast, don't want UI to feel sluggish
};
var newRoomfadeout:FadeTransition = FadeTransition {
    node: bind newRoomLabel
    fromValue: 1.0 toValue: 0.0
    duration: 500ms // fade-out is slower, like a trailing thought....
};
var desksharefadein:FadeTransition = FadeTransition {
    node: bind deskshareLabel
    fromValue: 0 toValue: 1.0
    duration: 100ms // fade-in is fast, don't want UI to feel sluggish
};
var desksharefadeout:FadeTransition = FadeTransition {
    node: bind deskshareLabel
    fromValue: 1.0 toValue: 0.0
    duration: 500ms // fade-out is slower, like a trailing thought....
};
var changeroomfadein:FadeTransition = FadeTransition {
    node: bind changeroomLabel
    fromValue: 0 toValue: 1.0
    duration: 100ms // fade-in is fast, don't want UI to feel sluggish
};
var changeroomfadeout:FadeTransition = FadeTransition {
    node: bind changeroomLabel
    fromValue: 1.0 toValue: 0.0
    duration: 500ms // fade-out is slower, like a trailing thought....
};
var pointerfadein:FadeTransition = FadeTransition {
    node: bind pointerLabel
    fromValue: 0 toValue: 1.0
    duration: 100ms // fade-in is fast, don't want UI to feel sluggish
};
var pointerfadeout:FadeTransition = FadeTransition {
    node: bind pointerLabel
    fromValue: 1.0 toValue: 0.0
    duration: 500ms // fade-out is slower, like a trailing thought....
};
var notepadfadein:FadeTransition = FadeTransition {
    node: bind notepadLabel
    fromValue: 0 toValue: 1.0
    duration: 100ms // fade-in is fast, don't want UI to feel sluggish
};
var notepadfadeout:FadeTransition = FadeTransition {
    node: bind notepadLabel
    fromValue: 1.0 toValue: 0.0
    duration: 500ms // fade-out is slower, like a trailing thought....
};
var wbfadein:FadeTransition = FadeTransition {
    node: bind whiteboardLabel
    fromValue: 0 toValue: 1.0
    duration: 100ms // fade-in is fast, don't want UI to feel sluggish
};
var wbfadeout:FadeTransition = FadeTransition {
    node: bind whiteboardLabel
    fromValue: 1.0 toValue: 0.0
    duration: 500ms // fade-out is slower, like a trailing thought....
};
/**
 *  Assign a variable for the content elements preparation for the stage
*/
var roomMembersButtonLabel = VBox {
    nodeHPos:HPos.CENTER
     spacing: 4
     content: bind [

             ImageView {
             image: Image {
             url: "{__DIR__}images/virtualroom.png"
             }
             effect: reflection
             cache: true
         },
          newRoomLabel = Text {
           content:"Room Members"
           opacity: 0.0 // label doesn't show until mouse-over
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
        newRoomfadein.playFromStart();
    }
    onMouseExited:function(event:MouseEvent):Void {
        newRoomfadeout.playFromStart();
    }
    onMousePressed:function(event:MouseEvent):Void{
        // mainScreen.swingBinder.showRoomMemberList();
        JOptionPane.showMessageDialog(null,"This feature has been disabled. Search function is not working correctly\nUse the email invitation feature instead");

    }


}
var deskshareButtonLabel = VBox {
    nodeHPos:HPos.CENTER
     spacing: 4
     content: bind [

             ImageView {
             image: Image { url: "{__DIR__}images/desktopsharing.png"}
             effect: reflection
             cache: true
         },
          deskshareLabel = Text {
           content:"Desktop Share"
           opacity: 0.0 // label doesn't show until mouse-over
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
       desksharefadein.playFromStart();
    }
    onMouseExited:function(event:MouseEvent):Void {
        desksharefadeout.playFromStart();
    }

}

var roomListButtonLabel = VBox {
     nodeHPos:HPos.CENTER
     spacing: 4
     cursor: Cursor.HAND
     content: bind [

             ImageView {
             image: Image { url: "{__DIR__}images/join_room.png"}
             effect: reflection
             cache: true
         },
          changeroomLabel = Text {
           content:"Room List"
           opacity: 0.0 // label doesn't show until mouse-over
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
        changeroomfadein.playFromStart();
    }
    onMouseExited:function(event:MouseEvent):Void {
        changeroomfadeout.playFromStart();
    }
    onMouseClicked:function(event:MouseEvent):Void {
      mainScreen.swingBinder.showRoomList(null);

    }
}
var whiteboardButtonLabel = VBox {
     nodeHPos:HPos.CENTER
     spacing: 4
     cursor: Cursor.HAND
     content: bind [

             ImageView {
             image: Image { url: "{__DIR__}images/whiteboard.png"}
             effect: reflection
             fitWidth:32
             fitHeight:32
             cache: true
         },
          whiteboardLabel = Text {
           content:"Whiteboard"
           opacity: 0.0 // label doesn't show until mouse-over
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
        wbfadein.playFromStart();
    }
    onMouseExited:function(event:MouseEvent):Void {
        wbfadeout.playFromStart();
    }
    onMouseClicked:function(event:MouseEvent):Void {
    mainScreen.getSwingBinder().showEditableWhiteboard(mainScreen.getWhiteboard());

    }
}
var pointerButtonLabel = VBox {
    nodeHPos:HPos.CENTER
     spacing: 4
     content: bind [

             ImageView {
             image: Image { url: "{__DIR__}images/arrow_side32.png"}
             effect: reflection
             cache: true
         },
          pointerLabel = Text {
           content:"Pointer"
           opacity: 0.0 // label doesn't show until mouse-over
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
        pointerfadein.playFromStart();
    }
    onMouseExited:function(event:MouseEvent):Void {
        pointerfadeout.playFromStart();
    }

}
var notepadButtonLabel = VBox {
    nodeHPos:HPos.CENTER
     spacing: 4
     content: bind [

             ImageView {
             image: Image { url: "{__DIR__}images/kedit32.png"}
             effect: reflection
             cache: true
         },
          notepadLabel = Text {
           content:"Notepad"
           opacity: 0.0 // label doesn't show until mouse-over
           textAlignment:TextAlignment.CENTER
           font: Font { size: 12 }
           fill: Color.WHITE

        }

     ]
     onMouseEntered:function(event:MouseEvent):Void {
        notepadfadein.playFromStart();
        
    }
    onMouseExited:function(event:MouseEvent):Void {
        notepadfadeout.playFromStart();
    }

}


public def buttons =[
     roomListButtonLabel,
     whiteboardButtonLabel,
     roomMembersButtonLabel,
     pointerButtonLabel,
     deskshareButtonLabel,
     notepadButtonLabel
   ]


}
