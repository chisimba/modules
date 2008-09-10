package avoir.realtime.tcp.base;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.awt.Color;
import java.util.Random;
import java.util.logging.Logger;
import javax.swing.JFrame;
import javax.swing.JPanel;

/**
 *
 * @author developer
 */
public class Simulator {

    private static Logger logger = Logger.getLogger(Simulator.class.getName());
    Random random = new Random();
    String userLevel = "admin";
    String fullname = "sim1";
    String userName = "sim1";
    String host = "localhost";
    int port = 22225;
    boolean isPresenter = true;
    String sessionId = "default_1216_1212841216";
    boolean isSlidesHost = false;
    String siteRoot = "";
    String slideServerId = "default";
    String resourcesPath = "/opt/lampp/htdocs/chisimba_modules/realtime/resources/";
    String slidesDir = "/opt/lampp/htdocs/chisimba/app/usrfiles/webpresent/default_1216_1212841216/";
    boolean localhost = true;

    public static void main(String[] args) {

        new Simulator(args[0], new Boolean(args[1]));
    }

    private void createUser() {
        Thread t = new Thread() {

            public void run() {
                RealtimeBase b = new RealtimeBase();
                //  b.setRealtimeHome("avoir-realtime-0.1" + createVer());
                userName = "guest" + Math.abs(random.nextInt(200));
                fullname = userName;
                isPresenter = false;
                b.setSessionTitle("Simulator 1");
                b.init(userLevel, fullname, userName, host, port, isPresenter, sessionId, slidesDir, isSlidesHost, siteRoot, slideServerId, resourcesPath, localhost,null);

            }
        };
        t.start();
    }

   


    public Simulator(String username, boolean presenter) {

        logger.info("Simulator 0.1 started ...");
        RealtimeBase base = new RealtimeBase();
        JPanel p = base.init(userLevel, username, username, host, port, presenter, sessionId, slidesDir, isSlidesHost, siteRoot, slideServerId, resourcesPath, localhost,null);
        JFrame fr = new JFrame(username);
        fr.setBackground(Color.WHITE);
        p.setBackground(Color.WHITE);
        fr.getContentPane().add(p);
        fr.setSize(800, 600);
        fr.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        fr.setVisible(true);
    }
}
