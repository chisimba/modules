package avoir.realtime.classroom;

import avoir.realtime.common.user.User;
import avoir.realtime.common.user.UserLevel;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.util.Timer;
import java.util.TimerTask;

/**
 * this is not a server actually...but a server in that it only
 * gives out slides
 * @author developer
 */
public class SlidesServer {

    Timer timer = new Timer();
    int DURATION = 1000 * 60 * 5;

    public static void main(String[] args) {
        if (args.length > 1) {
            int port = Integer.parseInt(args[2]);
            new SlidesServer(args[0], args[1], port);
        }
    }
    //TCPClient client;
    SlidesConsumer client;
    String slideServerId;

    public String getSlideServerId() {
        return slideServerId;
    }


    public SlidesServer(String id, String superNodeHost, int superNodePort) {
        client = new SlidesConsumer(superNodeHost, superNodePort);
        if (client.connect()) {
            System.out.println("Slide server running ...");
            //publish this user
            client.publish(createUser(id));
            timer.schedule(new SlidesServerMonitor(), DURATION);
        } else {
            //nothing else, just go off
            System.exit(0);
        }
    }

    public void exit() {
        System.exit(0);
    }

    class SlidesServerMonitor extends TimerTask {

        public void run() {
            //first, request for remove
            client.removeMe(slideServerId);
            timer.cancel(); //Terminate the thread
            System.out.println("Dying ...");

            //then quit
            System.exit(0);
        }
    }

    public void reconnect() {
        if (client.connect()) {
            System.out.println("Slide server running ...");
            //publish this user
            client.publish(createUser(slideServerId));
        //   monitor();
        } else {
            //nothing else, just go off
            System.exit(0);
        }
    }

    /**
     * Creates the slides server 'user'
     * @return
     */
    private User createUser(String id) {
        slideServerId = id;
        User user = new User(UserLevel.ADMIN, "slide-server",
                id, "xxxx", 22224, false, "", "", "", true, "", "");
        return user;
    }
}
