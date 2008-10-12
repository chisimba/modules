package avoir.realtime.tcp.base;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Toolkit;
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
    String fullname = "sim";
    String userName = "sim";
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
                Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
                JFrame mainFrame = new JFrame("Simulator 1.0");
                mainFrame.setJMenuBar(b.getMenuMananger());

                mainFrame.setSize((ss.width / 8) * 8, (ss.height / 8) * 8);
                mainFrame.setLocationRelativeTo(null);
                // b.init(userLevel, fullname, userName, host, port, isPresenter, sessionId, slidesDir, isSlidesHost, siteRoot, slideServerId, resourcesPath, localhost,null);
                b.initAsClassroom(host, port, userName, fullname, isPresenter,
                        sessionId, userLevel, slidesDir, siteRoot, slideServerId,
                        resourcesPath, mainFrame);
                mainFrame.setVisible(true);
            }
        };
        t.start();
    }

    public Simulator(String username, boolean presenter) {
        this.userName = username;
        this.isPresenter = presenter;
        init();
    }

    public void init() {
        Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
        logger.info("Simulator 0.1 started ...");
        RealtimeBase base = new RealtimeBase();
        JFrame fr = new JFrame(fullname);

        JPanel p = base.initAsClassroom(host, port, userName, fullname, isPresenter,
                sessionId, userLevel, slidesDir, siteRoot, slideServerId,
                resourcesPath, fr);
        fr.setJMenuBar(base.getMenuMananger());
        fr.setSize(ss);//(ss.width / 8) * 5, (ss.height / 8) * 5);
        fr.setBackground(Color.WHITE);
        p.setBackground(Color.WHITE);
        fr.getContentPane().add(p);

        fr.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        fr.setVisible(true);
    }

    public Simulator(String username, boolean presenter, String host, int port) {
        this.userName = username;
        this.fullname=username;
        this.isPresenter = presenter;
        this.host = host;
        this.port = port;
        init();
    }
}
