/*
 * MainScreen.fx
 *
 * Created on 28 Jun 2009, 1:28:54 PM
 */

package org.avoir.realtime.fx;

import javafx.geometry.HPos;
import javafx.geometry.VPos;
import javafx.scene.control.Label;
import javafx.scene.control.ToggleGroup;
import javafx.scene.layout.HBox;
import javafx.scene.layout.LayoutInfo;
import javafx.scene.layout.VBox;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Font;
import javafx.stage.Screen;
import javafx.scene.layout.Panel;
import javafx.ext.swing.SwingComponent;
import javax.swing.JScrollPane;
import java.awt.Dimension;
import javax.swing.BorderFactory;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.paint.LinearGradient;
import javafx.scene.paint.Stop;
import javafx.scene.input.MouseEvent;
import javafx.scene.effect.GaussianBlur;
import org.avoir.realtime.gui.whiteboard.Whiteboard;
import org.avoir.realtime.common.RealtimeFxInt;
import org.avoir.realtime.chat.ChatRoomManager;
import org.avoir.realtime.net.ConnectionManager;
import javax.swing.JTextArea;
import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.control.ProgressBar;
import javafx.scene.Node;
import org.avoir.realtime.startup.SwingBinder;
import org.avoir.realtime.gui.room.RoomListFrame;
import org.avoir.realtime.gui.userlist.ParticipantListTree;

import javafx.stage.Stage;

import javax.swing.JTabbedPane;

import javafx.scene.Group;
import javafx.scene.shape.Path;


import org.avoir.realtime.gui.room.RoomMemberListFrame;




/**
 * Shows the list of tasks categorized as High, Medium and Low.
 * Provides options to add new tasks, to view projects and to delete any existing
 * tasks.
 */

public class MainScreen extends RealtimeFxInt{

    public var swingBinder:SwingBinder=new SwingBinder(this);
    public var bottomLeftPanelContents: Node[];
    public var leftPanelContents: Node[];
    public var bodyPanelContents: Node[];
    public var buttonGroup: ToggleGroup = new ToggleGroup;
    public var swidth= Screen.primary.visualBounds.width;
    public var stage:Stage;

    var contents: Node[];
    var tabbedPane=new JTabbedPane();
    var participantListFrame:ParticipantListTree=new ParticipantListTree();
    var sheight= Screen.primary.visualBounds.height;
    var whiteboard=new Whiteboard();
    var scrollPane=new JScrollPane();
    var chatRoomManager:ChatRoomManager=new ChatRoomManager(this);
    var chatInScrollPane=new JScrollPane(chatRoomManager.getChatRoom().getChatInputField());
    var trScrollPane=new JScrollPane(chatRoomManager.getChatRoom().getChatTranscriptField());
    var borderRect = Rectangle {
        x: 10
        y: 12
        width: swidth/4
        height:sheight * 0.78
        strokeWidth: 1.5
        effect: GaussianBlur{ radius: 3 }
        //stroke: Color.WHITE;// bind Color.web("#0092ff")
        //fill: Color.TRANSPARENT
         fill: LinearGradient {
                        startX: 0 endX: 0
                        stops: [
                            Stop { color: Color.LIGHTGRAY offset: 0 },
                            //Stop { color: Color.BLACK offset: 0.5 }
                            Stop { color: Color.WHITE offset: 1 }
                        ]  }
         
        arcWidth: 10
        arcHeight: 10
    };

    public var headingText: Label = Label {
        text: "You are not in any room"
        textFill: Color.ORANGERED
        layoutInfo: LayoutInfo {
            hpos: HPos.CENTER,
            vpos: VPos.CENTER
            height: 30
        }
        font: Font {
            name: "Bitstream Vera Sans Bold"
            size: 14
        }
    }

    public var statusText: Label = Label {
        text: ""
        textFill: Color.WHITE
        layoutInfo: LayoutInfo {
            hpos: HPos.CENTER,
            vpos: VPos.CENTER
            height: 30
        }
        font: Font {
            name: "Bitstream Vera Sans Bold"
            size: 14
        }


    }

    override public function setStatusText(txt:String){
        statusText.text=txt;
    }

   override public function getSwingBinder(): SwingBinder{
       return swingBinder;
   }

    override public function setSysText(txt: String){
      
    }

    override public function joinRoom(
    roomName:String,
    username:String,
    names:String,
    email:String){
   
     if(chatRoomManager.doActualJoin(ConnectionManager.getUsername(), roomName, true)){
        headingText.text="You are in room {roomName}";
        participantListFrame.addUser(username, names);
        chatRoomManager.getChatRoom().getChatInputField().setEditable(true);
        chatRoomManager.requestRoomResources();
    }

    }

    /**
     * replaces the splash screen in the scene with main screen contents
     */
    override public function showMainScreen(
    roomName:String,
    username:String,
    names:String,
    email:String){
     if(chatRoomManager.doActualJoin(ConnectionManager.getUsername(), roomName, true)){

        delete stage.scene.content;
        stage.x=0;
        stage.y=0;
        stage.width=swidth;
        stage.height=sheight;
       
        insert createView() into stage.scene.content ;
        headingText.text="You are in room {roomName}";
        participantListFrame.addUser(username, names);
        chatRoomManager.getChatRoom().getChatInputField().setEditable(true);
        chatRoomManager.requestRoomResources();
    }

    }
    /**
    String username,String names,
            String email,String location
    */

    override public function addUserToParticipantList(
    username:String,
    names:String,
    email:String,
    location:String){
    //publicView.addUser(username, names);
    participantListFrame.addUser(username, names);
    }


    override public function  addSlide(url:String){
     carousel.addImage(url);
    }

    override public function getChatRoomManager(): ChatRoomManager{
        return chatRoomManager;
    }
    override public function getRoomListFrame(): RoomListFrame{
        return swingBinder.getRoomListFrame();
    }

    override public function getRoomMemberListFrame():RoomMemberListFrame{
        return swingBinder.getRoomMemberListFrame();
    }

    var whiteboardComponent:HBox =HBox{
        translateX: 20
        translateY:50
        content:[
        SwingComponent.wrap(scrollPane)
        ]
    }
   override public function addWhiteboardToScreenGraph(){
     whiteboard.setDrawEnabled(false);
     scrollPane.getViewport().add(whiteboard);
     delete whiteboardComponent from bodyPanelContents;
     delete carousel from bodyPanelContents;
     insert   whiteboardComponent into bodyPanelContents;
     insert carousel into bodyPanelContents;
     
   }

    override public function getWhiteboard(): Whiteboard{
    return whiteboard;
    }
    var loginWindow:LoginWindow=LoginWindow{
        mainScreen:this;
        };
    public var loginWindowRect:VBox= VBox{
    translateX:10
    translateY: sheight/2
    content:[loginWindow]

    }
   var bottomLeftWindow:BottomLeftWindow =BottomLeftWindow{mainScreen:this};
   
    public var chatWindowRect:VBox= VBox{
    translateX:20
    translateY: sheight * 0.41
    content: bind bottomLeftWindow

    }

   
   public var leftPanel:Panel =Panel{
     content: bind leftPanelContents
   }

    var toolbar:Toolbar= Toolbar{mainScreen:this};
    var middleBox : VBox =VBox{
     translateX:10
     translateY: 10

     content:[
         
     Rectangle {
           width:(swidth * 0.71)
           height:sheight * 0.78
           //fill:Color.WHITE
           fill: LinearGradient {
                        startX: 0 endX: 0
                        stops: [
                            Stop { color: Color.LIGHTGRAY offset: 0 },
                            Stop { color: Color.WHITE offset: 1 }
                        ]  }
           strokeWidth:1.5
           effect: GaussianBlur{ radius: 5 }
           arcHeight:20
           arcWidth:20
         },
        
        ]
      }

var carousel = Carousel {
    translateX: 70
    translateY: sheight * 0.62
    mainScreen:this
    
};

var backButton:ImageView=
             ImageView {
             image: Image { url: "{__DIR__}images/back_n.png"}
             translateX: 75
             translateY: sheight * 0.67
             cache: true
             onMouseEntered:function (me: MouseEvent) {
                backButton.image=Image { url: "{__DIR__}images/back_h.png"}
             }

             onMouseExited:function (me: MouseEvent) {
                backButton.image=Image { url: "{__DIR__}images/back_n.png"}
             }
           }

             var nextButton:ImageView=
             ImageView {
             image: Image { url: "{__DIR__}images/next_n.png"}
             translateX: 100
             translateY: sheight * 0.67
             cache: true
                onMouseEntered:function (me: MouseEvent) {
                nextButton.image=Image { url: "{__DIR__}images/next_h.png"}
             }

             onMouseExited:function (me: MouseEvent) {
                nextButton.image=Image { url: "{__DIR__}images/next_n.png"}
             }
         }

var lines = Group{};
var currentPath:Path;

var body:Panel=Panel{

    content: bind bodyPanelContents;
    
};

var presenceRect=Rectangle{
    x:20
    y: sheight * 0.35
    
    width: swidth * 0.23
    height: 20
    opacity:0.3
    fill:LinearGradient {
                        startX: 0 endX: 0
                        stops: [
                            Stop { color: Color.LIGHTGRAY offset: 0 },
                            Stop { color: Color.BLACK offset: 1 }
                        ]  }
    arcWidth:7;
    arcHeight:7
}


public function getChatinput():JTextArea{
    return chatRoomManager.getChatRoom().getChatInputField();
}

var loadCount:Number = 0;
public var loadProgressBar = ProgressBar {
   progress: bind ProgressBar.computeProgress(100, loadCount);
}
public var progressTimeline:Timeline = Timeline {
    repeatCount: Timeline.INDEFINITE
    keyFrames: [
        KeyFrame {
            time:100ms
            action: function() {
                loadCount++;
                if(loadCount == 100){
                    loadCount=0;
                }

            }
        }
    ]
};

public function createView(): VBox {
var ss:Dimension=java.awt.Toolkit.getDefaultToolkit().getScreenSize();

whiteboard.setPreferredSize(new Dimension((ss.width * 0.69),(ss.height * 0.7)));
whiteboard.setBorder(BorderFactory.createLoweredBevelBorder());
//chatRoomManager.getChatRoom().getChatTranscriptField().setText("Chat TR");
chatRoomManager.getChatRoom().getChatTranscriptField().setPreferredSize(new Dimension((ss.width * 0.23),(ss.height * 0.2)));
//chatRoomManager.getChatRoom().getChatTranscriptField().setOpaque(false);
//chatRoomManager.getChatRoom().getChatInputField().setOpaque(false);
//chatInScrollPane.getViewport().setOpaque(false);
//trScrollPane.setOpaque(false);
//chatRoomManager.getChatRoom().getChatInputField().setForeground(java.awt.Color.WHITE);
//chatRoomManager.getChatRoom().getChatInputField().setPreferredSize(new Dimension((ss.width *.23), ss.height*0.125));
chatRoomManager.getChatRoom().getChatInputField().setPreferredSize(new Dimension((ss.width *.23), 100));
chatRoomManager.getChatRoom().getChatInputField().setEditable(false);
participantListFrame.setPreferredSize(new Dimension((ss.width *.23), 300));
tabbedPane.setPreferredSize(new Dimension((ss.width *.23), 300));
tabbedPane.addTab("Users", participantListFrame);
scrollPane.getViewport().setView(whiteboard);
//chatInScrollPane.setPreferredSize(new Dimension((ss.width *.23), ss.height*0.125));
chatInScrollPane.setPreferredSize(new Dimension((ss.width *.23), 60));
chatInScrollPane.getViewport().setBackground(java.awt.Color.BLACK);
trScrollPane.setPreferredSize(new Dimension((ss.width * 0.23),(ss.height * 0.2)));
chatRoomManager.getChatRoom().getSysTextField().setPreferredSize(new Dimension((ss.width * 0.23),20));
chatRoomManager.getChatRoom().getSysTextField().setOpaque(false);




insert headingText into contents;
insert HBox{
                content:[
             toolbar.buttons
            ]
            } into contents;

insert Rectangle {
           translateX:10
           width:(swidth-30)
           height:2
            effect: GaussianBlur{ radius: 5 }
           fill:Color.ORANGERED
         } into contents;
insert borderRect into leftPanelContents ;

insert VBox {
           translateX:20
           translateY:20
           content:[
           SwingComponent.wrap(tabbedPane)
           ]
           }into leftPanelContents ;
insert chatWindowRect into leftPanelContents;

insert VBox {
           spacing: 7
           translateX:10
           translateY:sheight * 0.3
           content:[
            presenceRect,
           SwingComponent.wrap(trScrollPane),
           SwingComponent.wrap(chatInScrollPane),
           SwingComponent.wrap(chatRoomManager.getChatRoom().getSysTextField())
           ]
           } into bottomLeftWindow.chatRoomNodes;

insert middleBox into bodyPanelContents;
insert    whiteboardComponent into bodyPanelContents;

insert     Rectangle {
           translateX: 15
           translateY: sheight * 0.65
           width:(swidth * 0.70)
           height:100
           opacity:0.3
           fill:LinearGradient {
                        startX: 0 endX: 0
                        stops: [
                            Stop { color: Color.LIGHTGRAY offset: 0 },
                            Stop { color: Color.BLACK offset: 1 }
                        ]  }
           arcHeight:20
           arcWidth:20
         } into bodyPanelContents;

insert HBox{translateX: 15 translateY: sheight * 0.71 content:[ statusText]} into bodyPanelContents;
insert carousel into bodyPanelContents;
insert backButton into bodyPanelContents;
insert nextButton into bodyPanelContents;

insert   HBox{
           content:[
                   leftPanel, body
               ]
         } into contents;
         

//progressTimeline.playFromStart();
headingText.text="Loading ...";
var view=  VBox {
            content: bind contents;
        };

        return view;
    }
}
